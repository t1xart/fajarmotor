<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'sell_price', 'discount_per_item', 'subtotal'];

    protected $casts = ['sell_price' => 'decimal:2', 'discount_per_item' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
