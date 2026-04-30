<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = ['brand_id', 'name', 'type', 'cc', 'year_start', 'year_end', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_compatibility')->withPivot('notes')->withTimestamps();
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
