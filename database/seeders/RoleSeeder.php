<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'stock.view', 'stock.in', 'stock.out', 'stock.adjust', 'stock.transfer',
            'sales.view', 'sales.create', 'sales.return',
            'po.view', 'po.create', 'po.receive',
            'wo.view', 'wo.create', 'wo.update',
            'suppliers.view', 'suppliers.create', 'suppliers.edit',
            'reports.view', 'reports.export',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'branches.view', 'branches.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin' => $permissions,
            'cashier' => ['products.view', 'stock.view', 'sales.view', 'sales.create', 'wo.view'],
            'warehouse' => ['products.view', 'stock.view', 'stock.in', 'stock.out', 'stock.adjust', 'stock.transfer', 'po.view', 'po.receive'],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePerms);
        }
    }
}
