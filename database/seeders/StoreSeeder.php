<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Surgical Products
        $surgical = Category::create([
            'name' => 'Surgical Products',
            'slug' => 'surgical-products',
            'description' => 'Medical and surgical equipments',
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Surgical',
        ]);

        SubCategory::create([
            'category_id' => $surgical->id,
            'name' => 'First Aid',
            'slug' => 'first-aid',
        ]);

        // 2. Gadgets
        $gadgets = Category::create([
            'name' => 'Gadgets',
            'slug' => 'gadgets',
            'description' => 'Modern electronic gadgets',
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Gadgets',
        ]);

        $smartDevices = SubCategory::create([
            'category_id' => $gadgets->id,
            'name' => 'Smart Devices',
            'slug' => 'smart-devices',
        ]);

        // 3. Regular Accessories
        $accessories = Category::create([
            'name' => 'Regular Accessories',
            'slug' => 'regular-accessories',
            'description' => 'Home and lifestyle accessories',
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Accessories',
        ]);

        $homeTools = SubCategory::create([
            'category_id' => $accessories->id,
            'name' => 'Home Tools',
            'slug' => 'home-tools',
        ]);

        // Add Products
        Product::create([
            'category_id' => $accessories->id,
            'sub_category_id' => $homeTools->id,
            'name' => 'Digital Weight Machine',
            'slug' => Str::slug('Digital Weight Machine'),
            'sku' => 'ACC-WM-01',
            'description' => 'High precision digital weight machine for home use.',
            'price' => 1200,
            'stock' => 50,
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Weight+Machine',
        ]);

        Product::create([
            'category_id' => $accessories->id,
            'sub_category_id' => $homeTools->id,
            'name' => 'Stainless Steel Hanger (Set of 10)',
            'slug' => Str::slug('Stainless Steel Hanger Set of 10'),
            'sku' => 'ACC-HGR-01',
            'description' => 'Durable stainless steel hangers for clothes.',
            'price' => 450,
            'stock' => 100,
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Hanger',
        ]);

        Product::create([
            'category_id' => $gadgets->id,
            'sub_category_id' => $smartDevices->id,
            'name' => 'Bluetooth Smart Watch',
            'slug' => Str::slug('Bluetooth Smart Watch'),
            'sku' => 'GDT-SW-01',
            'description' => 'Waterproof smart watch with heart rate monitor.',
            'price' => 2500,
            'stock' => 25,
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Smart+Watch',
        ]);

        Product::create([
            'category_id' => $surgical->id,
            'name' => 'Digital Thermometer',
            'slug' => Str::slug('Digital Thermometer'),
            'sku' => 'SUR-TM-01',
            'description' => 'Accurate digital thermometer for medical use.',
            'price' => 350,
            'stock' => 200,
            'image' => 'https://placehold.co/600x600/003366/ffffff?text=Thermometer',
        ]);
    }
}
