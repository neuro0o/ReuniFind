<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['categoryName' => 'Accessories', 'description' => 'Items such as keychains, watches, glasses, and jewelry.'],
            ['categoryName' => 'Bags', 'description' => 'Items such as backpacks, tote bags, handbags, or luggage.'],
            ['categoryName' => 'Books & Stationery', 'description' => 'Items such as books, notebooks, pens, or files.'],
            ['categoryName' => 'Clothing', 'description' => 'Apparel items such as jackets, hoodies, hats, shoes, or uniforms.'],
            ['categoryName' => 'Documents', 'description' => 'IDs, passports, certificates, exam slips, etc.'],
            ['categoryName' => 'Electronics', 'description' => 'Devices such as phones, laptops, earbuds, USB drives, or earphones.'],
            ['categoryName' => 'Keys & Cards', 'description' => 'House keys, car keys, or access cards'],
            ['categoryName' => 'Personal Item', 'description' => 'Wallets, purses, tumblers, or cosmetic items.'],
            ['categoryName' => 'Other', 'description' => 'Items that do not fit any other category.'],
        ];

        DB::table('item_categories')->insert($categories);
    }
}
