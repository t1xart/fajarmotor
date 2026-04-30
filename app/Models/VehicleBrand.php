<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $fillable = ['name', 'logo', 'country', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function types()
    {
        return $this->hasMany(VehicleType::class, 'brand_id');
    }
}
