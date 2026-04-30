<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('sales.create');
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:30|regex:/^[0-9+\s\-()]{9,}$/',
            'payment_method' => 'required|in:cash,transfer,qris',
            'discount' => 'nullable|numeric|min:0|max:999999999', // nominal, bukan persentase
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:99999',
            'items.*.sell_price' => 'required|numeric|min:0|max:999999999',
            'items.*.discount' => 'nullable|numeric|min:0|max:999999999', // nominal per item
        ];
    }

    public function messages(): array
    {
        return [
            'customer_phone.regex' => 'Nomor telepon tidak valid (gunakan format 08xx atau +62xxx).',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'paid_amount.required' => 'Jumlah pembayaran harus diisi.',
            'items.required' => 'Minimal satu item harus ditambahkan.',
            'items.min' => 'Minimal satu item harus ditambahkan.',
            'items.*.product_id.required' => 'Produk harus dipilih untuk setiap item.',
            'items.*.quantity.required' => 'Jumlah produk harus diisi.',
            'items.*.quantity.min' => 'Jumlah produk minimal 1.',
            'items.*.sell_price.required' => 'Harga jual harus diisi.',
        ];
    }
}
