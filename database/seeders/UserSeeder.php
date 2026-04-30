<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        $users = [
            ['name' => 'Admin', 'email' => 'admin@spareparts.id', 'role' => 'admin'],
            ['name' => 'Kasir 1', 'email' => 'kasir@spareparts.id', 'role' => 'cashier'],
            ['name' => 'Gudang 1', 'email' => 'gudang@spareparts.id', 'role' => 'warehouse'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'branch_id' => $branch?->id,
                    'is_active' => true,
                ]
            );
            $user->assignRole($data['role']);
        }
    }
}
