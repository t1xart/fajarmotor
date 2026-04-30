<?php

namespace App\Services;

use App\Models\Sale;

class InvoiceService
{
    /**
     * Generate sequential invoice: INV-YYYYMMDD-XXXX
     * Uses withTrashed() to avoid duplicate numbers after soft deletes.
     */
    public static function generate(): string
    {
        $prefix = 'INV-'.now()->format('Ymd').'-';
        $last = Sale::withTrashed()
            ->where('invoice_number', 'like', "{$prefix}%")
            ->orderByDesc('invoice_number')
            ->value('invoice_number');
        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
