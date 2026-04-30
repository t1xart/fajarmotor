<?php

namespace App\Services;

use App\Models\WorkOrder;

class WoNumberService
{
    /**
     * Generate sequential WO number: WO-YYYYMM-XXXX
     * Uses withTrashed() to avoid duplicate numbers after soft deletes.
     */
    public static function generate(): string
    {
        $prefix = 'WO-'.now()->format('Ym').'-';
        $last = WorkOrder::withTrashed()
            ->where('wo_number', 'like', "{$prefix}%")
            ->orderByDesc('wo_number')
            ->value('wo_number');
        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
