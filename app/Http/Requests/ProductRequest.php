<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return $this->user() && $this->user()->can($isUpdate ? 'products.edit' : 'products.create');
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name' => 'required|string|max:255',
            // SKU is auto-generated on create; only validate on update
            'sku' => $isUpdate ? [
                'sometimes', 'required', 'string', 'max:50',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ] : ['nullable', 'string', 'max:50', 'regex:/^[A-Z0-9\-]+$/'],
            // Slug is auto-generated from name; only validate on update
            'slug' => $isUpdate ? [
                'sometimes', 'required', 'string', 'max:255',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ] : ['nullable', 'string', 'max:255'],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0|max:999999999',
            'sell_price' => 'required|numeric|min:0|max:999999999',
            'unit' => 'required|in:pcs,set,liter,meter,roll',
            'min_stock' => 'required|integer|min:0',
            'shelf_code' => 'nullable|string|max:50',
            'warranty_days' => 'nullable|integer|min:0|max:3650',
            'weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'compatibility' => 'nullable|array',
            'compatibility.*' => 'exists:vehicle_types,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi.',
            'sku.unique' => 'SKU sudah digunakan produk lain.',
            'sku.regex' => 'SKU hanya boleh mengandung huruf besar, angka, dan tanda hubung.',
            'slug.unique' => 'Slug sudah digunakan produk lain.',
            'category_id.required' => 'Kategori harus dipilih.',
            'buy_price.required' => 'Harga beli harus diisi.',
            'buy_price.numeric' => 'Harga beli harus berupa angka.',
            'sell_price.required' => 'Harga jual harus diisi.',
            'sell_price.numeric' => 'Harga jual harus berupa angka.',
            'unit.required' => 'Satuan harus dipilih.',
            'min_stock.required' => 'Stok minimum harus diisi.',
            'compatibility.*.exists' => 'Tipe kendaraan yang dipilih tidak valid.',
            'images.*.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
