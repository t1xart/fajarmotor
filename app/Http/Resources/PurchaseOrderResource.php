<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'po_number' => $this->po_number,
            'supplier' => $this->whenLoaded('supplier', fn () => ['id' => $this->supplier->id, 'name' => $this->supplier->name]),
            'branch' => $this->whenLoaded('branch', fn () => ['id' => $this->branch->id, 'name' => $this->branch->name]),
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount,
            'notes' => $this->notes,
            'ordered_at' => $this->ordered_at?->toISOString(),
            'received_at' => $this->received_at?->toISOString(),
            'created_by' => $this->whenLoaded('user', fn () => $this->user?->name),
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'id' => $i->id,
                'product_id' => $i->product_id,
                'product_name' => $i->product?->name,
                'sku' => $i->product?->sku,
                'quantity' => $i->quantity,
                'buy_price' => (float) $i->buy_price,
                'received_quantity' => $i->received_quantity,
            ])),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
