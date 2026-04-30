<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('po.create');
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'notes' => 'nullable|string|max:1000',
            'ordered_at' => 'nullable|date|before_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:99999',
            'items.*.buy_price' => 'required|numeric|min:0|max:999999999',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier harus dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'ordered_at.date' => 'Format tanggal tidak valid.',
            'ordered_at.before_or_equal' => 'Tanggal pemesanan tidak boleh lebih dari hari ini.',
            'items.required' => 'Minimal satu item harus ditambahkan.',
            'items.min' => 'Minimal satu item harus ditambahkan.',
            'items.*.product_id.required' => 'Produk harus dipilih untuk setiap item.',
            'items.*.quantity.required' => 'Jumlah produk harus diisi.',
            'items.*.quantity.min' => 'Jumlah produk minimal 1.',
            'items.*.buy_price.required' => 'Harga beli harus diisi.',
        ];
    }
}
