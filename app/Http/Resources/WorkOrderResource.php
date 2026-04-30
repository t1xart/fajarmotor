<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'wo_number' => $this->wo_number,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'vehicle_plate' => $this->vehicle_plate,
            'vehicle' => $this->whenLoaded('vehicleType', fn () => $this->vehicleType ? $this->vehicleType->brand?->name.' '.$this->vehicleType->name : null),
            'vehicle_year' => $this->vehicle_year,
            'complaint' => $this->complaint,
            'diagnosis' => $this->diagnosis,
            'service_fee' => (float) $this->service_fee,
            'parts_total' => (float) $this->parts_total,
            'total' => (float) $this->total,
            'status' => $this->status,
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'description' => $i->description,
                'product' => $i->product?->name,
                'quantity' => $i->quantity,
                'price' => (float) $i->price,
                'subtotal' => (float) $i->subtotal,
            ])),
            'started_at' => $this->started_at?->toISOString(),
            'finished_at' => $this->finished_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
