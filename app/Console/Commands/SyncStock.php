<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StockRecord;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Console\Command;

class SyncStock extends Command
{
    protected $signature = 'stock:sync {--product= : Product ID to sync} {--warehouse= : Warehouse ID to sync}';

    protected $description = 'Recalculate stock records based on mutation history';

    public function handle(StockService $stockService)
    {
        $productId = $this->option('product');
        $warehouseId = $this->option('warehouse');

        $query = StockRecord::query();

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            // If no records found but we have mutations, we should still sync
            if ($productId && $warehouseId) {
                $product = Product::find($productId);
                $warehouse = Warehouse::find($warehouseId);
                if ($product && $warehouse) {
                    $this->info("Syncing {$product->name} in {$warehouse->name}...");
                    $newQty = $stockService->recalculateStock($product, $warehouse);
                    $this->line("Result: {$newQty}");

                    return 0;
                }
            }
            $this->warn('No records found to sync.');

            return 0;
        }

        $this->withProgressBar($records, function ($record) use ($stockService) {
            $stockService->recalculateStock($record->product, $record->warehouse);
        });

        $this->newLine();
        $this->info('Stock synchronization complete.');

        return 0;
    }
}
