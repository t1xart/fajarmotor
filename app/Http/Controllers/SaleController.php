<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use App\Services\InvoiceService;
use App\Services\StockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Sale::class);

        $sales = Sale::with(['user', 'branch'])
            ->when($request->search, fn ($q) => $q->where('invoice_number', 'like', "%{$request->search}%")->orWhere('customer_name', 'like', "%{$request->search}%"))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()->paginate(20)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function pos()
    {
        $this->authorize('create', Sale::class);
        $products = Product::with(['stockRecords', 'primaryImage'])->where('is_active', true)->get();

        return view('sales.pos', compact('products'));
    }

    public function store(SaleRequest $request)
    {
        try {
            $sale = DB::transaction(function () use ($request) {
                $items = collect($request->items);
                $warehouse = Warehouse::where('branch_id', auth()->user()->branch_id)->firstOrFail();

                // Validate stock availability BEFORE creating the sale
                $products = [];
                foreach ($items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $products[$item['product_id']] = $product;
                    if (! $this->stockService->hasSufficientStock($product, $warehouse, $item['quantity'])) {
                        $currentStock = $this->stockService->getStockLevel($product, $warehouse);
                        throw new \Exception("Stok {$product->name} tidak cukup. Stok tersedia: {$currentStock}, diminta: {$item['quantity']}");
                    }
                }

                $subtotal = $items->sum(fn ($i) => ($i['sell_price'] - ($i['discount'] ?? 0)) * $i['quantity']);
                $discount = $request->discount ?? 0;
                $tax = 0; // TODO: Implement configurable tax rates
                $total = $subtotal - $discount + $tax;

                $paidAmount = $request->paid_amount ?? 0;
                $status = $paidAmount >= $total ? Sale::STATUS_PAID : ($paidAmount > 0 ? Sale::STATUS_PARTIAL : Sale::STATUS_PENDING);

                // Create sale
                $sale = Sale::create([
                    'invoice_number' => InvoiceService::generate(),
                    'branch_id' => auth()->user()->branch_id,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'payment_method' => $request->payment_method,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $total,
                    'paid_amount' => $paidAmount,
                    'change_amount' => max(0, $paidAmount - $total),
                    'status' => $status,
                    'notes' => $request->notes,
                    'user_id' => auth()->id(),
                ]);

                // Create sale items and stock mutations
                foreach ($items as $item) {
                    $product = $products[$item['product_id']];
                    $itemSubtotal = ($item['sell_price'] - ($item['discount'] ?? 0)) * $item['quantity'];

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'sell_price' => $item['sell_price'],
                        'discount_per_item' => $item['discount'] ?? 0,
                        'subtotal' => $itemSubtotal,
                    ]);

                    // Create stock mutation using StockService
                    $this->stockService->createMutation(
                        $product,
                        $warehouse,
                        'out',
                        -$item['quantity'],
                        $sale,
                        ['notes' => "Penjualan {$sale->invoice_number}"]
                    );
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale)->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);
        $sale->load(['items.product', 'user', 'branch']);

        return view('sales.show', compact('sale'));
    }

    public function returnSale(Sale $sale)
    {
        $this->authorize('processReturn', $sale);

        if ($sale->status === Sale::STATUS_RETURNED) {
            return back()->withErrors(['error' => 'Transaksi ini sudah diretur sebelumnya.']);
        }

        try {
            DB::transaction(function () use ($sale) {
                $warehouse = Warehouse::where('branch_id', $sale->branch_id)->firstOrFail();
                $sale->load('items.product');
                $sale->update(['status' => Sale::STATUS_RETURNED]);

                // Restore stock for all items
                foreach ($sale->items as $item) {
                    $this->stockService->createMutation(
                        $item->product,
                        $warehouse,
                        'in',
                        $item->quantity,
                        $sale,
                        ['notes' => "Retur penjualan {$sale->invoice_number}"]
                    );
                }
            });

            return back()->with('success', 'Retur berhasil diproses.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memproses retur: '.$e->getMessage()]);
        }
    }

    public function invoicePdf(Sale $sale)
    {
        $this->authorize('view', $sale);
        $sale->load(['items.product', 'user', 'branch']);
        $pdf = Pdf::loadView('reports.pdf.invoice', compact('sale'));

        return $pdf->stream("invoice-{$sale->invoice_number}.pdf");
    }

    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);

        if ($sale->status === Sale::STATUS_RETURNED) {
            return back()->withErrors(['error' => 'Transaksi yang sudah diretur tidak bisa dihapus.']);
        }

        try {
            DB::transaction(function () use ($sale) {
                $warehouse = Warehouse::where('branch_id', $sale->branch_id)->firstOrFail();

                // If not already returned, restore stock
                if ($sale->status !== Sale::STATUS_RETURNED) {
                    foreach ($sale->items as $item) {
                        $this->stockService->createMutation(
                            $item->product,
                            $warehouse,
                            'in',
                            $item->quantity,
                            $sale,
                            ['notes' => "Pembatalan penjualan {$sale->invoice_number}"]
                        );
                    }
                }

                $sale->delete();
            });

            return redirect()->route('sales.index')->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi: '.$e->getMessage()]);
        }
    }
}
