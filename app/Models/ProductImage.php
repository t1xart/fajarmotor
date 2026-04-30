<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'is_primary', 'sort_order'];

    protected $casts = ['is_primary' => 'boolean'];

    // Boot the model to handle primary image constraints
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $image) {
            // If this image is being set as primary, unset other primary images
            if ($image->is_primary && $image->isDirty('is_primary')) {
                ProductImage::where('product_id', $image->product_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_primary' => false]);
            }
        });

        static::saved(function (self $image) {
            // Ensure at least one image is primary if none exists
            if (! $image->product->images()->where('is_primary', true)->exists()) {
                $image->product->images()
                    ->orderBy('sort_order')
                    ->first()?->update(['is_primary' => true]);
            }
        });

        static::deleting(function (self $image) {
            // If deleting the primary image, set the next image as primary
            if ($image->is_primary) {
                $image->product->images()
                    ->where('id', '!=', $image->id)
                    ->orderBy('sort_order')
                    ->first()?->update(['is_primary' => true]);
            }
        });
    }

    /**
     * Relationship to Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Set this image as primary (and unset others)
     */
    public function setPrimary(): bool
    {
        return $this->update(['is_primary' => true]);
    }
}
