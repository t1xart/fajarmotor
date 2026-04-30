<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrder extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = ['po_number', 'supplier_id', 'branch_id', 'status', 'total_amount', 'notes', 'ordered_at', 'received_at', 'user_id'];

    protected $casts = ['ordered_at' => 'datetime', 'received_at' => 'datetime', 'total_amount' => 'decimal:2'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function stockMutations()
    {
        return $this->morphMany(StockMutation::class, 'reference');
    }

    /**
     * Calculate total amount from items
     */
    public function calculateTotalAmount(): float
    {
        return (float) $this->items()->sum(fn ($item) => $item->quantity * $item->buy_price);
    }

    /**
     * Update purchase order total based on current items
     */
    public function updateSummary(): void
    {
        $this->total_amount = $this->calculateTotalAmount();
        $this->save();
    }
}
