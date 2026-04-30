<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class SkuService
{
    /**
     * Generate unique SKU: CAT-YYYYMM-XXXX
     * e.g. ENG-202604-0001
     */
    public static function generate(int $categoryId): string
    {
        $category = Category::find($categoryId);
        $prefix = $category
            ? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category->name), 0, 3))
            : 'PRD';

        $yearMonth = now()->format('Ym');
        $base = "{$prefix}-{$yearMonth}-";

        $last = Product::withTrashed()
            ->where('sku', 'like', "{$base}%")
            ->orderByDesc('sku')
            ->value('sku');

        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return $base.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
