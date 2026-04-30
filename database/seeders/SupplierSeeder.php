<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'PT Astra Honda Motor', 'code' => 'SUP-AHM-0001', 'contact_person' => 'Budi Santoso', 'phone' => '021-12345678', 'email' => 'sales@ahm.co.id', 'city' => 'Jakarta', 'province' => 'DKI Jakarta'],
            ['name' => 'PT Yamaha Indonesia', 'code' => 'SUP-YMH-0002', 'contact_person' => 'Siti Rahayu', 'phone' => '021-87654321', 'email' => 'sales@yamaha.co.id', 'city' => 'Jakarta', 'province' => 'DKI Jakarta'],
            ['name' => 'CV Sparepart Nusantara', 'code' => 'SUP-SPN-0003', 'contact_person' => 'Ahmad Fauzi', 'phone' => '0741-556677', 'email' => 'info@sparepartnusantara.com', 'city' => 'Jambi', 'province' => 'Jambi'],
            ['name' => 'UD Maju Jaya Motor', 'code' => 'SUP-MJM-0004', 'contact_person' => 'Dewi Lestari', 'phone' => '0741-998877', 'email' => null, 'city' => 'Jambi', 'province' => 'Jambi'],
        ];

        foreach ($suppliers as $s) {
            Supplier::firstOrCreate(['code' => $s['code']], array_merge($s, ['is_active' => true]));
        }
    }
}
