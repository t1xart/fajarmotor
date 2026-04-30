<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
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
            ->latest()->paginate($request->per_page ?? 20);

        return PurchaseOrderResource::collection($orders);
    }

    public function store(PurchaseOrderRequest $request)
    {
        $po = DB::transaction(function () use ($request) {
            $items = collect($request->items);
            $po = PurchaseOrder::create([
                'po_number' => PoNumberService::generate(),
                'supplier_id' => $request->supplier_id,
                'branch_id' => auth()->user()->branch_id,
                'status' => 'draft',
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

            return $po->load(['supplier', 'items.product', 'branch']);
        });

        return new PurchaseOrderResource($po);
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return new PurchaseOrderResource($purchaseOrder->load(['supplier', 'items.product', 'user', 'branch']));
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

                        $product->update(['buy_price' => $item->buy_price]);
                    }
                }

                $purchaseOrder->update([
                    'status' => $allReceived ? 'received' : 'partial',
                    'received_at' => now(),
                ]);
            });

            return new PurchaseOrderResource($purchaseOrder->fresh(['supplier', 'items.product']));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mencatat penerimaan: '.$e->getMessage()], 422);
        }
    }
}
