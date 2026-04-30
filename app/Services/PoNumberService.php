<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PoNumberService
{
    /**
     * Generate sequential PO number: PO-YYYYMM-XXXX
     * Uses withTrashed() to avoid duplicate numbers after soft deletes.
     */
    public static function generate(): string
    {
        $prefix = 'PO-'.now()->format('Ym').'-';
        $last = PurchaseOrder::withTrashed()
            ->where('po_number', 'like', "{$prefix}%")
            ->orderByDesc('po_number')
            ->value('po_number');
        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
