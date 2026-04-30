<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Exports\StockReportExport;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        // Optimize: Use single query with eager loading
        $sales = Sale::with(['items.product', 'branch'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_transactions' => $sales->count(),
            'total_revenue' => $sales->sum('total'),
            'total_items' => $sales->sum(fn ($s) => $s->items->sum('quantity')),
        ];

        // Optimize: Use database aggregation instead of PHP grouping
        $topProducts = SaleItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(subtotal) as total_revenue'),
            DB::raw('SUM(subtotal) / SUM(quantity) as avg_price')
        )
            ->whereHas('sale', fn ($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])->where('status', 'paid'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return view('reports.sales', compact('sales', 'summary', 'topProducts', 'from', 'to'));
    }

    public function stock(Request $request)
    {
        // Optimize: Use single query with eager loading and conditional filtering
        $query = Product::with([
            'category',
            'stockRecords.warehouse.branch',
        ])->where('is_active', true);

        if ($request->low_stock) {
            $query->whereHas('stockRecords', fn ($s) => $s->whereColumn('quantity', '<=', DB::raw('products.min_stock'))
            );
        }

        $products = $query->orderBy('name')->get();

        return view('reports.stock', compact('products'));
    }

    public function profit(Request $request)
    {
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        // Optimize: Use database aggregation to calculate profit instead of PHP grouping
        $profitData = DB::table('sale_items')
            ->select(
                'sale_items.product_id',
                DB::raw('SUM(sale_items.quantity) as qty'),
                DB::raw('SUM(sale_items.subtotal) as revenue'),
                DB::raw('products.buy_price'),
                DB::raw('SUM(sale_items.quantity) * products.buy_price as cogs'),
                DB::raw('SUM(sale_items.subtotal) - (SUM(sale_items.quantity) * products.buy_price) as profit')
            )
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween(DB::raw('DATE(sales.created_at)'), [$from, $to])
            ->where('sales.status', 'paid')
            ->groupBy('sale_items.product_id', 'products.buy_price', 'products.name')
            ->orderByDesc('profit')
            ->get();

        // Load product details for display
        $productIds = $profitData->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $profitData = $profitData->map(function ($item) use ($products) {
            return [
                'product' => $products[$item->product_id] ?? null,
                'qty' => $item->qty,
                'revenue' => $item->revenue,
                'cogs' => $item->cogs,
                'profit' => $item->profit,
            ];
        });

        return view('reports.profit', compact('profitData', 'from', 'to'));
    }

    public function exportSalesPdf(Request $request)
    {
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        // Optimize: Eager load relationships
        $sales = Sale::with(['items.product', 'branch', 'user'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.sales', compact('sales', 'from', 'to'));

        return $pdf->download("laporan-penjualan-{$from}-{$to}.pdf");
    }

    public function exportSalesExcel(Request $request)
    {
        try {
            $from = $request->date_from ?? now()->startOfMonth()->toDateString();
            $to = $request->date_to ?? now()->toDateString();

            return Excel::download(new SalesReportExport($from, $to), "laporan-penjualan-{$from}-{$to}.xlsx");
        } catch (\Exception $e) {
            return back()->withErrors(['excel' => 'Export Excel gagal: '.$e->getMessage().'. Pastikan ekstensi GD terinstall.']);
        }
    }

    public function exportStockExcel()
    {
        try {
            return Excel::download(new StockReportExport, 'laporan-stok-'.now()->format('Y-m-d').'.xlsx');
        } catch (\Exception $e) {
            return back()->withErrors(['excel' => 'Export Excel gagal: '.$e->getMessage().'. Pastikan ekstensi GD terinstall.']);
        }
    }
}
