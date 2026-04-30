<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = ['work_order_id', 'product_id', 'description', 'quantity', 'price', 'subtotal'];

    protected $casts = ['price' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
