<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'branch' => $this->whenLoaded('branch', fn () => ['id' => $this->branch->id, 'name' => $this->branch->name]),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'payment_method' => $this->payment_method,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'paid_amount' => (float) $this->paid_amount,
            'change_amount' => (float) $this->change_amount,
            'status' => $this->status,
            'notes' => $this->notes,
            'cashier' => $this->whenLoaded('user', fn () => $this->user?->name),
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'product_id' => $i->product_id,
                'product_name' => $i->product?->name,
                'sku' => $i->product?->sku,
                'quantity' => $i->quantity,
                'sell_price' => (float) $i->sell_price,
                'discount' => (float) $i->discount_per_item,
                'subtotal' => (float) $i->subtotal,
            ])),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
