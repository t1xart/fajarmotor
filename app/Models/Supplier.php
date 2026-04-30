<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'code', 'contact_person', 'phone', 'email', 'address', 'city', 'province', 'bank_name', 'bank_account', 'rating', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Generate a unique supplier code: SUP-XXX-XXXX
     * Uses withTrashed() to avoid duplicate codes after soft deletes.
     */
    public static function generateCode(string $name): string
    {
        $prefix = 'SUP-'.strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3)).'-';
        $last = static::withTrashed()
            ->where('code', 'like', "{$prefix}%")
            ->orderByDesc('code')
            ->value('code');
        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
