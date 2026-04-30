<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = ['sku', 'name', 'slug', 'category_id', 'description', 'buy_price', 'sell_price', 'unit', 'min_stock', 'shelf_code', 'warranty_days', 'weight', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'buy_price' => 'decimal:2', 'sell_price' => 'decimal:2'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function compatibilities()
    {
        return $this->belongsToMany(VehicleType::class, 'product_compatibility')->withPivot('notes')->withTimestamps();
    }

    public function stockRecords()
    {
        return $this->hasMany(StockRecord::class);
    }

    public function stockMutations()
    {
        return $this->hasMany(StockMutation::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function totalStock(): int
    {
        return $this->stockRecords()->sum('quantity');
    }

    public function isLowStock(): bool
    {
        return $this->totalStock() <= $this->min_stock;
    }
}
