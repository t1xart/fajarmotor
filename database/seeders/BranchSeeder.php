<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['name' => 'Jambi Handil', 'city' => 'Jambi', 'province' => 'Jambi', 'phone' => '0741-1234567'],
        ];

        foreach ($branches as $data) {
            $branch = Branch::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
            Warehouse::firstOrCreate(
                ['code' => 'WH-'.strtoupper(substr($branch->city, 0, 3)).'-01'],
                [
                    'branch_id' => $branch->id,
                    'name' => 'Gudang Utama '.$branch->name,
                    'code' => 'WH-'.strtoupper(substr($branch->city, 0, 3)).'-01',
                ]
            );
        }
    }
}
