<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        $sales = Sale::with('items.product')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'paid')->get();

        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('sale', fn ($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])->where('status', 'paid'))
            ->with('product:id,name,sku')->groupBy('product_id')->orderByDesc('total_qty')->limit(10)->get();

        return response()->json([
            'period' => ['from' => $from, 'to' => $to],
            'summary' => [
                'total_transactions' => $sales->count(),
                'total_revenue' => (float) $sales->sum('total'),
                'total_discount' => (float) $sales->sum('discount'),
                'total_items' => $sales->sum(fn ($s) => $s->items->sum('quantity')),
            ],
            'top_products' => $topProducts->map(fn ($i) => [
                'product_id' => $i->product_id,
                'product_name' => $i->product?->name,
                'sku' => $i->product?->sku,
                'total_qty' => (int) $i->total_qty,
                'total_revenue' => (float) $i->total_revenue,
            ]),
        ]);
    }

    public function stock(Request $request)
    {
        $products = Product::with(['category', 'stockRecords'])
            ->when($request->low_stock, fn ($q) => $q->whereHas('stockRecords', fn ($s) => $s->whereColumn('quantity', '<=', DB::raw('products.min_stock'))))
            ->get()->map(fn ($p) => [
                'id' => $p->id,
                'sku' => $p->sku,
                'name' => $p->name,
                'category' => $p->category?->name,
                'total_stock' => $p->totalStock(),
                'min_stock' => $p->min_stock,
                'buy_price' => (float) $p->buy_price,
                'sell_price' => (float) $p->sell_price,
                'status' => $p->totalStock() <= 0 ? 'empty' : ($p->isLowStock() ? 'low' : 'ok'),
            ]);

        return response()->json(['data' => $products, 'total' => $products->count()]);
    }

    public function profit(Request $request)
    {
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        $items = SaleItem::with('product:id,name,sku,buy_price')
            ->whereHas('sale', fn ($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])->where('status', 'paid'))
            ->get();

        $data = $items->groupBy('product_id')->map(function ($group) {
            $product = $group->first()->product;
            $qty = $group->sum('quantity');
            $revenue = (float) $group->sum('subtotal');
            $cogs = (float) $product->buy_price * $qty;
            $profit = $revenue - $cogs;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'qty_sold' => $qty,
                'revenue' => $revenue,
                'cogs' => $cogs,
                'profit' => $profit,
                'margin_pct' => $revenue > 0 ? round(($profit / $revenue) * 100, 2) : 0,
            ];
        })->sortByDesc('profit')->values();

        return response()->json([
            'period' => ['from' => $from, 'to' => $to],
            'summary' => [
                'total_revenue' => (float) $data->sum('revenue'),
                'total_cogs' => (float) $data->sum('cogs'),
                'total_profit' => (float) $data->sum('profit'),
            ],
            'data' => $data,
        ]);
    }
}
