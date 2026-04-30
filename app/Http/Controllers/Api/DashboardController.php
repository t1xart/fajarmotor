<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'stats' => [
                'total_products' => Product::where('is_active', true)->count(),
                'low_stock' => Product::whereHas('stockRecords', fn ($q) => $q->whereColumn('quantity', '<=', DB::raw('products.min_stock')))->count(),
                'sales_today' => Sale::whereDate('created_at', today())->where('status', 'paid')->sum('total'),
                'sales_month' => Sale::whereMonth('created_at', now()->month)->where('status', 'paid')->sum('total'),
                'pending_po' => PurchaseOrder::whereIn('status', ['draft', 'sent'])->count(),
                'pending_wo' => WorkOrder::whereIn('status', ['pending', 'in_progress'])->count(),
            ],
            'recent_sales' => Sale::with('user')->latest()->limit(5)->get()->map(fn ($s) => [
                'id' => $s->id,
                'invoice_number' => $s->invoice_number,
                'customer' => $s->customer_name ?: 'Umum',
                'total' => (float) $s->total,
                'cashier' => $s->user?->name,
                'created_at' => $s->created_at->toISOString(),
            ]),
            'low_stock_products' => Product::with(['stockRecords', 'category'])
                ->whereHas('stockRecords', fn ($q) => $q->whereColumn('quantity', '<=', DB::raw('products.min_stock')))
                ->limit(10)->get()->map(fn ($p) => [
                    'id' => $p->id,
                    'sku' => $p->sku,
                    'name' => $p->name,
                    'category' => $p->category?->name,
                    'total_stock' => $p->totalStock(),
                    'min_stock' => $p->min_stock,
                ]),
        ]);
    }
}
