<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'address', 'city', 'province', 'phone', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
