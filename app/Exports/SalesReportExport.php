<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private string $from,
        private string $to
    ) {}

    public function collection()
    {
        return Sale::with(['items.product', 'user', 'branch'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->from, $this->to])
            ->where('status', 'paid')
            ->get();
    }

    public function headings(): array
    {
        return ['Invoice', 'Pelanggan', 'No. HP', 'Cabang', 'Pembayaran', 'Subtotal', 'Diskon', 'Total', 'Bayar', 'Kembalian', 'Kasir', 'Tanggal'];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->customer_name ?: 'Umum',
            $sale->customer_phone,
            $sale->branch?->name ?? '-',
            strtoupper($sale->payment_method),
            $sale->subtotal,
            $sale->discount,
            $sale->total,
            $sale->paid_amount,
            $sale->change_amount,
            $sale->user?->name ?? '-',
            $sale->created_at->format('d/m/Y H:i'),
        ];
    }

    public function title(): string
    {
        return "Penjualan {$this->from} - {$this->to}";
    }
}
