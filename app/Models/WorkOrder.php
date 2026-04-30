<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WorkOrder extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = ['wo_number', 'branch_id', 'customer_name', 'customer_phone', 'vehicle_plate', 'vehicle_type_id', 'vehicle_year', 'complaint', 'diagnosis', 'service_fee', 'parts_total', 'total', 'status', 'user_id', 'started_at', 'finished_at'];

    protected $casts = ['service_fee' => 'decimal:2', 'parts_total' => 'decimal:2', 'total' => 'decimal:2', 'started_at' => 'datetime', 'finished_at' => 'datetime'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    /**
     * Track stock mutations from work order items
     */
    public function stockMutations()
    {
        return $this->morphMany(StockMutation::class, 'reference');
    }

    /**
     * Calculate parts total from items
     */
    public function calculatePartsTotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    /**
     * Update work order total
     */
    public function updateTotal(): void
    {
        $this->parts_total = $this->calculatePartsTotal();
        $this->total = (float) $this->service_fee + (float) $this->parts_total;
        $this->save();
    }
}
