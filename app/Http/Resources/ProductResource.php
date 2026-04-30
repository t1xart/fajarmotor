<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->whenLoaded('category', fn () => ['id' => $this->category->id, 'name' => $this->category->name]),
            'description' => $this->description,
            'buy_price' => (float) $this->buy_price,
            'sell_price' => (float) $this->sell_price,
            'unit' => $this->unit,
            'min_stock' => $this->min_stock,
            'shelf_code' => $this->shelf_code,
            'warranty_days' => $this->warranty_days,
            'weight' => $this->weight,
            'image' => $this->image ? Storage::url($this->image) : null,
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($i) => [
                'id' => $i->id,
                'url' => Storage::url($i->image_path),
                'is_primary' => $i->is_primary,
            ])),
            'total_stock' => $this->whenLoaded('stockRecords', fn () => $this->totalStock()),
            'is_low_stock' => $this->whenLoaded('stockRecords', fn () => $this->isLowStock()),
            'compatibility' => $this->whenLoaded('compatibilities', fn () => $this->compatibilities->map(fn ($v) => [
                'id' => $v->id, 'name' => $v->name, 'brand' => $v->brand?->name, 'type' => $v->type,
            ])),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
