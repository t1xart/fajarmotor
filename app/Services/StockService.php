<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMutation;
use App\Models\StockRecord;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Create a stock mutation for product movement
     *
     * @param  string  $type  ('in', 'out', 'adjustment', 'transfer')
     * @param  Model|null  $reference  (Sale, PurchaseOrder, WorkOrder, etc.)
     * @param  array  $options  Additional options
     *
     * @throws \Exception
     */
    public function createMutation(
        Product $product,
        Warehouse $warehouse,
        string $type,
        int $quantity,
        ?Model $reference = null,
        array $options = []
    ): StockMutation {
        return DB::transaction(function () use ($product, $warehouse, $type, $quantity, $reference, $options) {
            // Lock stock record row to prevent race conditions that can desynchronize stock
            $stock = StockRecord::where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                StockRecord::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => 0,
                    'last_updated' => now(),
                ]);

                $stock = StockRecord::where('product_id', $product->id)
                    ->where('warehouse_id', $warehouse->id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            $quantityBefore = $stock->quantity;
            $quantityAfter = $quantityBefore + $quantity;

            // Validate stock for outgoing transactions
            if (in_array($type, ['out', 'transfer']) && $quantityAfter < 0) {
                throw new \Exception("Stok tidak cukup untuk produk {$product->name} di gudang {$warehouse->name}. Stok tersedia: {$quantityBefore}");
            }

            // Update stock record
            $stock->update([
                'quantity' => max(0, $quantityAfter),
                'last_updated' => now(),
            ]);

            // Create mutation record
            $mutation = StockMutation::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'type' => $type,
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => max(0, $quantityAfter),
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->id,
                'notes' => $options['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            return $mutation;
        });
    }

    /**
     * Recalculate stock for a product in a warehouse based on mutation history
     */
    public function recalculateStock(Product $product, Warehouse $warehouse): int
    {
        return DB::transaction(function () use ($product, $warehouse) {
            $totalQuantity = (int) StockMutation::where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->sum('quantity');

            $totalQuantity = max(0, $totalQuantity);

            StockRecord::updateOrCreate(
                ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                [
                    'quantity' => $totalQuantity,
                    'last_updated' => now(),
                ]
            );

            return $totalQuantity;
        });
    }

    /**
     * Create mutation for stock transfer between warehouses
     */
    public function transferStock(
        Product $product,
        Warehouse $fromWarehouse,
        Warehouse $toWarehouse,
        int $quantity,
        ?Model $reference = null,
        array $options = []
    ): array {
        return DB::transaction(function () use ($product, $fromWarehouse, $toWarehouse, $quantity, $reference, $options) {
            // Create outgoing mutation
            $outMutation = $this->createMutation(
                $product,
                $fromWarehouse,
                'transfer',
                -$quantity,
                $reference,
                ['notes' => $options['notes'] ?? "Transfer ke {$toWarehouse->name}"]
            );

            // Create incoming mutation
            $inMutation = $this->createMutation(
                $product,
                $toWarehouse,
                'transfer',
                $quantity,
                $reference,
                ['notes' => $options['notes'] ?? "Transfer dari {$fromWarehouse->name}"]
            );

            return [$outMutation, $inMutation];
        });
    }

    /**
     * Adjust stock quantity (for inventory counts, corrections, etc.)
     */
    public function adjustStock(
        Product $product,
        Warehouse $warehouse,
        int $quantity,
        ?Model $reference = null,
        array $options = []
    ): StockMutation {
        return $this->createMutation(
            $product,
            $warehouse,
            'adjustment',
            $quantity,
            $reference,
            ['notes' => $options['notes'] ?? null]
        );
    }

    /**
     * Get stock level for a product in a warehouse
     */
    public function getStockLevel(Product $product, Warehouse $warehouse): int
    {
        return (int) (StockRecord::where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->value('quantity') ?? 0);
    }

    /**
     * Get total stock for a product across all warehouses in a branch
     */
    public function getTotalStockByBranch(Product $product, $branch): int
    {
        return (int) DB::table('stock_records')
            ->join('warehouses', 'stock_records.warehouse_id', '=', 'warehouses.id')
            ->where('stock_records.product_id', $product->id)
            ->where('warehouses.branch_id', $branch->id ?? $branch)
            ->sum('stock_records.quantity');
    }

    /**
     * Check if product has sufficient stock
     */
    public function hasSufficientStock(Product $product, Warehouse $warehouse, int $quantity): bool
    {
        $currentStock = $this->getStockLevel($product, $warehouse);

        return $currentStock >= $quantity;
    }

    /**
     * Get low stock products for a warehouse
     */
    public function getLowStockProducts(Warehouse $warehouse): array
    {
        return DB::table('stock_records')
            ->join('products', 'stock_records.product_id', '=', 'products.id')
            ->where('stock_records.warehouse_id', $warehouse->id)
            ->whereRaw('stock_records.quantity <= products.min_stock')
            ->select('products.*', 'stock_records.quantity as current_stock')
            ->get()
            ->toArray();
    }

    /**
     * Get stock mutation history for a product
     */
    public function getMutationHistory(Product $product, ?Warehouse $warehouse = null, int $limit = 50): Collection
    {
        $query = StockMutation::where('product_id', $product->id)
            ->with(['warehouse', 'user', 'reference']);

        if ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        }

        return $query->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * Get stock value for accounting
     */
    public function getStockValue(Product $product, ?Warehouse $warehouse = null): float
    {
        $query = StockRecord::where('product_id', $product->id);

        if ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        }

        return (float) $query->get()
            ->sum(fn ($record) => $record->quantity * $product->buy_price);
    }
}
