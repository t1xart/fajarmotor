<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return $this->user() && $this->user()->can($isUpdate ? 'wo.update' : 'wo.create');
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => ['nullable', 'string', 'max:20', 'regex:/^(?:0|\+62)?[0-9]{9,12}$/'],
            'vehicle_plate' => ['nullable', 'string', 'max:20', 'regex:/^[A-Z]{1,2}\s?[0-9]{1,4}\s?[A-Z]{1,3}$/'],
            'vehicle_type_id' => 'nullable|exists:vehicle_types,id',
            'vehicle_year' => 'nullable|integer|min:1990|max:'.date('Y'),
            'complaint' => 'nullable|string|max:1000',
            'service_fee' => 'nullable|numeric|min:0|max:999999999',
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required_with:items.*.product_id|string|max:1000',
            'items.*.quantity' => 'required_with:items.*.product_id|integer|min:1|max:99999',
            'items.*.price' => 'required_with:items.*.product_id|numeric|min:0|max:999999999',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama pelanggan harus diisi.',
            'customer_phone.regex' => 'Format nomor telepon tidak valid.',
            'vehicle_plate.regex' => 'Format plat kendaraan tidak valid (contoh: BL 1234 ABC).',
            'vehicle_type_id.exists' => 'Tipe kendaraan yang dipilih tidak valid.',
            'vehicle_year.integer' => 'Tahun kendaraan harus berupa angka.',
            'vehicle_year.min' => 'Tahun kendaraan tidak boleh kurang dari 1990.',
            'items.*.description.required_with' => 'Deskripsi item harus diisi.',
            'items.*.quantity.required_with' => 'Jumlah item harus diisi.',
            'items.*.quantity.min' => 'Jumlah item minimal 1.',
            'items.*.price.required_with' => 'Harga item harus diisi.',
        ];
    }
}
