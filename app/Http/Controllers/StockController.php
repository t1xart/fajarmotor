<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockAdjustRequest;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\StockRecord;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StockController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request): View
    {
        Gate::authorize('stock.view');

        $stocks = StockRecord::with(['product.category', 'warehouse.branch'])
            ->when($request->warehouse_id, fn ($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->search, fn ($q) => $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$request->search}%")->orWhere('sku', 'like', "%{$request->search}%")))
            ->paginate(20)->withQueryString();

        $warehouses = Warehouse::with('branch')->where('is_active', true)->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('stock.index', compact('stocks', 'warehouses', 'products'));
    }

    public function mutations(Request $request): View
    {
        $mutations = StockMutation::with(['product', 'warehouse', 'user'])
            ->when($request->product_id, fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->latest()->paginate(30)->withQueryString();

        return view('stock.mutations', compact('mutations'));
    }

    public function adjust(StockAdjustRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
                $warehouse = Warehouse::findOrFail($request->warehouse_id);
                $quantity = (int) $request->quantity;

                if ($request->type === 'transfer') {
                    $toWarehouse = Warehouse::findOrFail($request->to_warehouse_id);

                    // Validate sufficient stock for transfer
                    if (! $this->stockService->hasSufficientStock($product, $warehouse, $quantity)) {
                        $currentStock = $this->stockService->getStockLevel($product, $warehouse);
                        throw new \Exception("Stok tidak cukup untuk transfer. Stok tersedia: {$currentStock}, diminta: {$quantity}");
                    }

                    // Transfer stock between warehouses
                    $this->stockService->transferStock(
                        $product,
                        $warehouse,
                        $toWarehouse,
                        $quantity,
                        null,
                        ['notes' => $request->notes]
                    );
                } else {
                    // Create mutation for in/out/adjustment
                    $mutationQuantity = $request->type === 'out' ? -$quantity : $quantity;

                    // Validate stock for 'out' type
                    if ($request->type === 'out' && ! $this->stockService->hasSufficientStock($product, $warehouse, $quantity)) {
                        $currentStock = $this->stockService->getStockLevel($product, $warehouse);
                        throw new \Exception("Stok tidak cukup untuk pengeluaran. Stok tersedia: {$currentStock}, diminta: {$quantity}");
                    }

                    $this->stockService->createMutation(
                        $product,
                        $warehouse,
                        $request->type,
                        $mutationQuantity,
                        null,
                        ['notes' => $request->notes]
                    );
                }
            });

            return back()->with('success', 'Stok berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
