<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['branch_id', 'name', 'code', 'address', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function stockRecords()
    {
        return $this->hasMany(StockRecord::class);
    }

    public function stockMutations()
    {
        return $this->hasMany(StockMutation::class);
    }
}
