<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mesin', 'icon' => 'fa-cog', 'children' => ['Piston & Ring', 'Klep & Noken As', 'Karburator', 'Filter Oli', 'Gasket & Seal']],
            ['name' => 'Kelistrikan', 'icon' => 'fa-bolt', 'children' => ['Aki / Baterai', 'CDI & Koil', 'Busi', 'Kabel & Lampu']],
            ['name' => 'Body & Rangka', 'icon' => 'fa-motorcycle', 'children' => ['Spion', 'Fairing & Cover', 'Setang & Grip', 'Footstep']],
            ['name' => 'Rem & Suspensi', 'icon' => 'fa-circle', 'children' => ['Kampas Rem', 'Cakram', 'Shock Absorber', 'Per Suspensi']],
            ['name' => 'Transmisi & Rantai', 'icon' => 'fa-link', 'children' => ['Rantai', 'Sproket', 'Gigi Transmisi', 'Kopling']],
            ['name' => 'Bahan Bakar', 'icon' => 'fa-gas-pump', 'children' => ['Karburator', 'Injektor', 'Tangki & Selang', 'Filter BBM']],
            ['name' => 'Oli & Cairan', 'icon' => 'fa-tint', 'children' => ['Oli Mesin', 'Oli Gardan', 'Minyak Rem', 'Coolant']],
        ];

        foreach ($categories as $cat) {
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                ['name' => $cat['name'], 'slug' => Str::slug($cat['name']), 'icon' => $cat['icon']]
            );
            foreach ($cat['children'] as $child) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($child.'-'.$cat['name'])],
                    ['name' => $child, 'slug' => Str::slug($child.'-'.$cat['name']), 'parent_id' => $parent->id]
                );
            }
        }
    }
}
