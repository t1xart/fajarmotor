<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrderRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\PoNumberService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request)
    {
        $orders = PurchaseOrder::with(['supplier', 'branch', 'user'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->supplier_id, fn ($q) => $q->where('supplier_id', $request->supplier_id))
            ->latest()->paginate(20)->withQueryString();

        $suppliers = Supplier::where('is_active', true)->get();

        return view('purchase-orders.index', compact('orders', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(PurchaseOrderRequest $request)
    {
        DB::transaction(function () use ($request) {
            $items = collect($request->items);
            $po = PurchaseOrder::create([
                'po_number' => PoNumberService::generate(),
                'supplier_id' => $request->supplier_id,
                'branch_id' => auth()->user()->branch_id,
                'status' => PurchaseOrder::STATUS_DRAFT,
                'total_amount' => $items->sum(fn ($i) => $i['buy_price'] * $i['quantity']),
                'notes' => $request->notes,
                'ordered_at' => $request->ordered_at ?? now(),
                'user_id' => auth()->id(),
            ]);
            foreach ($items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'buy_price' => $item['buy_price'],
                ]);
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'PO berhasil dibuat.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product', 'user', 'branch']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_items,id',
            'items.*.received_quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $purchaseOrder) {
                $warehouse = Warehouse::where('branch_id', $purchaseOrder->branch_id)->firstOrFail();
                $allReceived = true;

                foreach ($request->items as $itemData) {
                    $item = PurchaseOrderItem::findOrFail($itemData['id']);
                    $receivedQty = (int) $itemData['received_quantity'];

                    $item->update(['received_quantity' => $item->received_quantity + $receivedQty]);
                    if ($item->received_quantity < $item->quantity) {
                        $allReceived = false;
                    }

                    if ($receivedQty > 0) {
                        $product = Product::findOrFail($item->product_id);

                        $this->stockService->createMutation(
                            $product, $warehouse, 'in', $receivedQty, $purchaseOrder,
                            ['notes' => "Penerimaan PO {$purchaseOrder->po_number}"]
                        );

                        // Update buy price to latest received price
                        $product->update(['buy_price' => $item->buy_price]);
                    }
                }

                $purchaseOrder->update([
                    'status' => $allReceived ? PurchaseOrder::STATUS_RECEIVED : PurchaseOrder::STATUS_PARTIAL,
                    'received_at' => now(),
                ]);
            });

            return back()->with('success', 'Penerimaan barang berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mencatat penerimaan: '.$e->getMessage()]);
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (in_array($purchaseOrder->status, [PurchaseOrder::STATUS_RECEIVED, PurchaseOrder::STATUS_PARTIAL])) {
            return back()->withErrors(['error' => 'PO yang sudah diterima tidak bisa dihapus.']);
        }

        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')->with('success', 'PO berhasil dihapus.');
    }
}
