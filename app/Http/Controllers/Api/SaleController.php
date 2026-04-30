<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use App\Services\InvoiceService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request)
    {
        $sales = Sale::with(['user', 'branch'])
            ->when($request->search, fn ($q) => $q->where('invoice_number', 'like', "%{$request->search}%")->orWhere('customer_name', 'like', "%{$request->search}%"))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()->paginate($request->per_page ?? 20);

        return SaleResource::collection($sales);
    }

    public function store(SaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $items = collect($request->items);
            $warehouse = Warehouse::where('branch_id', auth()->user()->branch_id)->firstOrFail();

            // Validate stock availability before creating the sale
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if (! $this->stockService->hasSufficientStock($product, $warehouse, $item['quantity'])) {
                    $available = $this->stockService->getStockLevel($product, $warehouse);
                    throw new \Exception("Stok {$product->name} tidak cukup. Tersedia: {$available}, diminta: {$item['quantity']}");
                }
            }

            $subtotal = $items->sum(fn ($i) => ($i['sell_price'] - ($i['discount'] ?? 0)) * $i['quantity']);
            $discount = $request->discount ?? 0;
            $total = max(0, $subtotal - $discount);

            $sale = Sale::create([
                'invoice_number' => InvoiceService::generate(),
                'branch_id' => auth()->user()->branch_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => 0,
                'total' => $total,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $total,
                'status' => $request->paid_amount >= $total ? 'paid' : 'partial',
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $sub = ($item['sell_price'] - ($item['discount'] ?? 0)) * $item['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sell_price' => $item['sell_price'],
                    'discount_per_item' => $item['discount'] ?? 0,
                    'subtotal' => $sub,
                ]);

                $this->stockService->createMutation(
                    $product, $warehouse, 'out', -$item['quantity'], $sale,
                    ['notes' => "Penjualan {$sale->invoice_number}"]
                );
            }

            return $sale->load(['items.product', 'user', 'branch']);
        });

        return new SaleResource($sale);
    }

    public function show(Sale $sale)
    {
        return new SaleResource($sale->load(['items.product', 'user', 'branch']));
    }
}
