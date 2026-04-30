<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockAdjustRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('stock.adjust');
    }

    public function rules(): array
    {
        $type = $this->input('type');

        return [
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            // adjustment: set absolute quantity (can be 0+), others: positive delta
            'quantity' => 'required|integer|min:'.($type === 'adjustment' ? '0' : '1').'|max:9999999',
            'type' => 'required|in:in,out,adjustment,transfer',
            'notes' => 'nullable|string|max:1000',
            'to_warehouse_id' => [
                'required_if:type,transfer',
                'nullable',
                'exists:warehouses,id',
                Rule::notIn([$this->input('warehouse_id')]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk harus dipilih.',
            'warehouse_id.required' => 'Gudang sumber harus dipilih.',
            'warehouse_id.exists' => 'Gudang yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah stok harus diisi.',
            'quantity.integer' => 'Jumlah stok harus berupa angka bulat.',
            'quantity.min' => 'Jumlah stok minimal 1.',
            'type.required' => 'Jenis mutasi harus dipilih.',
            'type.in' => 'Jenis mutasi tidak valid.',
            'to_warehouse_id.required_if' => 'Gudang tujuan harus dipilih untuk transfer stok.',
            'to_warehouse_id.exists' => 'Gudang tujuan yang dipilih tidak valid.',
            'to_warehouse_id.not_in' => 'Gudang tujuan harus berbeda dengan gudang sumber.',
        ];
    }
}
