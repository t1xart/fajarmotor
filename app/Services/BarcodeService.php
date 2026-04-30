<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    /**
     * Generate SVG barcode for a SKU (for inline HTML display)
     */
    public static function svg(string $sku): string
    {
        $generator = new BarcodeGeneratorSVG;

        return $generator->getBarcode($sku, $generator::TYPE_CODE_128, 2, 50);
    }

    /**
     * Generate PNG barcode as base64 (for PDF embedding)
     */
    public static function base64(string $sku): string
    {
        $generator = new BarcodeGeneratorPNG;
        $png = $generator->getBarcode($sku, $generator::TYPE_CODE_128, 2, 50);

        return 'data:image/png;base64,'.base64_encode($png);
    }
}
