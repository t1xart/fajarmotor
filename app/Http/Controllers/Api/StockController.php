<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockAdjustRequest;
use App\Http\Resources\StockMutationResource;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\StockRecord;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request)
    {
        $stocks = StockRecord::with(['product.category', 'warehouse.branch'])
            ->when($request->warehouse_id, fn ($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->search, fn ($q) => $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$request->search}%")->orWhere('sku', 'like', "%{$request->search}%")))
            ->paginate($request->per_page ?? 50);

        return $this->successResponse([
            'items' => $stocks->map(fn ($r) => [
                'product_id' => $r->product_id,
                'sku' => $r->product?->sku,
                'product_name' => $r->product?->name,
                'category' => $r->product?->category?->name,
                'warehouse_id' => $r->warehouse_id,
                'warehouse' => $r->warehouse?->name,
                'branch' => $r->warehouse?->branch?->name,
                'quantity' => $r->quantity,
                'min_stock' => $r->product?->min_stock,
                'is_low' => $r->quantity <= $r->product?->min_stock,
                'last_updated' => $r->last_updated?->toISOString(),
            ]),
            'meta' => [
                'total' => $stocks->total(),
                'per_page' => $stocks->perPage(),
                'current_page' => $stocks->currentPage(),
                'last_page' => $stocks->lastPage(),
            ],
        ]);
    }

    public function mutations(Request $request)
    {
        $mutations = StockMutation::with(['product', 'warehouse', 'user'])
            ->when($request->product_id, fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->warehouse_id, fn ($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->latest()->paginate($request->per_page ?? 30);

        return StockMutationResource::collection($mutations);
    }

    public function adjust(StockAdjustRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
                $warehouse = Warehouse::findOrFail($request->warehouse_id);
                $quantity = (int) $request->quantity;

                if ($request->type === 'transfer') {
                    $toWarehouse = Warehouse::findOrFail($request->to_warehouse_id);

                    if (! $this->stockService->hasSufficientStock($product, $warehouse, $quantity)) {
                        $available = $this->stockService->getStockLevel($product, $warehouse);
                        throw new \Exception("Stok tidak cukup untuk transfer. Tersedia: {$available}, diminta: {$quantity}");
                    }

                    $this->stockService->transferStock($product, $warehouse, $toWarehouse, $quantity, null, ['notes' => $request->notes]);

                    return;
                }

                if ($request->type === 'adjustment') {
                    // Calculate delta from current stock to target absolute quantity
                    $current = $this->stockService->getStockLevel($product, $warehouse);
                    $delta = $quantity - $current;
                    $this->stockService->createMutation($product, $warehouse, 'adjustment', $delta, null, ['notes' => $request->notes]);

                    return;
                }

                // in / out
                if ($request->type === 'out' && ! $this->stockService->hasSufficientStock($product, $warehouse, $quantity)) {
                    $available = $this->stockService->getStockLevel($product, $warehouse);
                    throw new \Exception("Stok tidak cukup. Tersedia: {$available}, diminta: {$quantity}");
                }

                $mutationQty = $request->type === 'out' ? -$quantity : $quantity;
                $this->stockService->createMutation($product, $warehouse, $request->type, $mutationQty, null, ['notes' => $request->notes]);
            });

            return $this->successResponse(null, 'Stok berhasil diperbarui.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), null, 422);
        }
    }
}
