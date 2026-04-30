<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $fillable = ['product_id', 'warehouse_id', 'type', 'quantity', 'quantity_before', 'quantity_after', 'reference_type', 'reference_id', 'notes', 'user_id'];

    protected static function booted()
    {
        static::saving(function ($mutation) {
            if ($mutation->reference_type && ! array_key_exists($mutation->reference_type, \Illuminate\Database\Eloquent\Relations\Relation::morphMap())) {
                // Jika tidak ada di morphMap, mungkin masih menggunakan full class name (sebelum migrasi data)
                // Tapi kita ingin memaksa penggunaan map untuk data baru
                $allowed = ['sale', 'purchase_order', 'work_order'];
                if (! in_array($mutation->reference_type, $allowed)) {
                    throw new \Exception("Tipe referensi '{$mutation->reference_type}' tidak diizinkan untuk mutasi stok.");
                }
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
