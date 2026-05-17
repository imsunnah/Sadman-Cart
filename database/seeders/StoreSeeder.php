<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Combo;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Categories (at least 10)
        $categoriesData = [
            ['name' => 'Surgical Products', 'description' => 'Medical and surgical equipments', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Surgical'],
            ['name' => 'Electronic Gadgets', 'description' => 'Modern electronic gadgets', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Gadgets'],
            ['name' => 'Regular Accessories', 'description' => 'Home and lifestyle accessories', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Accessories'],
            ['name' => 'Fitness & Health', 'description' => 'Smart health monitoring and gym gear', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Fitness'],
            ['name' => 'Home Appliances', 'description' => 'Useful kitchen and smart home utilities', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Home+Appliances'],
            ['name' => 'Personal Care', 'description' => 'Self care and grooming tools', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Personal+Care'],
            ['name' => 'Office Supplies', 'description' => 'Organizing and high quality stationery', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Office'],
            ['name' => 'Outdoor & Travel', 'description' => 'Smart carry luggage and camping gear', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Travel'],
            ['name' => 'Automotive Accessories', 'description' => 'Car detailing and dashboard equipment', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Auto'],
            ['name' => 'Smart Kitchen Tools', 'description' => 'Intelligent culinary and baking helper equipment', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Kitchen'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                [
                    'name' => $c['name'],
                    'description' => $c['description'],
                    'image' => $c['image'],
                ]
            );
        }

        // 2. Seed SubCategories (at least 10)
        $subCategoriesData = [
            ['category_idx' => 0, 'name' => 'First Aid Kits'],
            ['category_idx' => 0, 'name' => 'Diagnostic Tools'],
            ['category_idx' => 1, 'name' => 'Smart Wearables'],
            ['category_idx' => 1, 'name' => 'Wireless Audio'],
            ['category_idx' => 2, 'name' => 'Storage Solutions'],
            ['category_idx' => 2, 'name' => 'Hangers & Hooks'],
            ['category_idx' => 3, 'name' => 'Yoga & Cardio Tools'],
            ['category_idx' => 4, 'name' => 'Kitchen Utilities'],
            ['category_idx' => 5, 'name' => 'Hair Grooming Tools'],
            ['category_idx' => 6, 'name' => 'Desk Organizers'],
        ];

        $subCategories = [];
        foreach ($subCategoriesData as $sub) {
            $catId = $categories[$sub['category_idx']]->id;
            $subCategories[] = SubCategory::updateOrCreate(
                ['slug' => Str::slug($sub['name']), 'category_id' => $catId],
                [
                    'name' => $sub['name'],
                ]
            );
        }

        // 3. Seed Products (at least 10)
        $productsData = [
            [
                'category_idx' => 2, 'sub_category_idx' => 5,
                'name' => 'Stainless Steel Hanger (Set of 10)', 'sku' => 'ACC-HGR-01',
                'description' => 'Durable stainless steel hangers for clothes.',
                'price' => 450, 'buying_price' => 200, 'package_cost' => 20, 'stock' => 100,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Hangers',
                'discount_type' => 'percentage', 'discount_value' => 10.00
            ],
            [
                'category_idx' => 1, 'sub_category_idx' => 2,
                'name' => 'Bluetooth Smart Watch', 'sku' => 'GDT-SW-01',
                'description' => 'Waterproof smart watch with heart rate monitor.',
                'price' => 2500, 'buying_price' => 1200, 'package_cost' => 50, 'stock' => 25,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Smart+Watch',
                'discount_type' => 'fixed', 'discount_value' => 300.00
            ],
            [
                'category_idx' => 0, 'sub_category_idx' => 1,
                'name' => 'Digital Thermometer', 'sku' => 'SUR-TM-01',
                'description' => 'Accurate digital thermometer for medical use.',
                'price' => 350, 'buying_price' => 150, 'package_cost' => 10, 'stock' => 200,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Thermometer',
                'discount_type' => null, 'discount_value' => null
            ],
            [
                'category_idx' => 3, 'sub_category_idx' => 6,
                'name' => 'Premium Yoga Mat', 'sku' => 'FIT-YM-01',
                'description' => 'Extra thick non-slip yoga mat with carrying strap.',
                'price' => 1500, 'buying_price' => 700, 'package_cost' => 30, 'stock' => 80,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Yoga+Mat',
                'discount_type' => 'percentage', 'discount_value' => 15.00
            ],
            [
                'category_idx' => 4, 'sub_category_idx' => 7,
                'name' => 'Electric Mini Garlic Chopper', 'sku' => 'KIT-GC-01',
                'description' => 'Wireless smart mini food processor & garlic mincer.',
                'price' => 850, 'buying_price' => 350, 'package_cost' => 15, 'stock' => 120,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Mini+Chopper',
                'discount_type' => 'percentage', 'discount_value' => 5.00
            ],
            [
                'category_idx' => 5, 'sub_category_idx' => 8,
                'name' => 'Professional Hair Clipper', 'sku' => 'PCM-HC-01',
                'description' => 'Rechargeable cordless beard and hair trimmer kit.',
                'price' => 1800, 'buying_price' => 800, 'package_cost' => 40, 'stock' => 40,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Hair+Clipper',
                'discount_type' => 'fixed', 'discount_value' => 200.00
            ],
            [
                'category_idx' => 6, 'sub_category_idx' => 9,
                'name' => 'Multi-functional Desk Organizer', 'sku' => 'OFC-DO-01',
                'description' => 'Mesh metal desk organizer with 6 compartments and drawer.',
                'price' => 950, 'buying_price' => 400, 'package_cost' => 25, 'stock' => 65,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Desk+Organizer',
                'discount_type' => null, 'discount_value' => null
            ],
            [
                'category_idx' => 2, 'sub_category_idx' => 4,
                'name' => 'Digital Weight Machine', 'sku' => 'ACC-WM-01',
                'description' => 'High precision digital weight machine for home use.',
                'price' => 1200, 'buying_price' => 500, 'package_cost' => 30, 'stock' => 50,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Weight+Machine',
                'discount_type' => 'percentage', 'discount_value' => 10.00
            ],
            [
                'category_idx' => 1, 'sub_category_idx' => 3,
                'name' => 'Wireless TWS Earbuds', 'sku' => 'GDT-EB-01',
                'description' => 'Noise-cancelling wireless TWS earbuds with deep bass.',
                'price' => 1950, 'buying_price' => 900, 'package_cost' => 20, 'stock' => 110,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Earbuds',
                'discount_type' => 'percentage', 'discount_value' => 20.00
            ],
            [
                'category_idx' => 0, 'sub_category_idx' => 0,
                'name' => 'Emergency Survival Kit', 'sku' => 'SUR-SK-01',
                'description' => 'All-in-one professional emergency survival kit box.',
                'price' => 3200, 'buying_price' => 1500, 'package_cost' => 60, 'stock' => 35,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Survival+Kit',
                'discount_type' => 'fixed', 'discount_value' => 500.00
            ],
        ];

        $products = [];
        foreach ($productsData as $prod) {
            $products[] = Product::updateOrCreate(
                ['sku' => $prod['sku']],
                [
                    'category_id' => $categories[$prod['category_idx']]->id,
                    'sub_category_id' => $subCategories[$prod['sub_category_idx']]->id,
                    'name' => $prod['name'],
                    'slug' => Str::slug($prod['name']),
                    'description' => $prod['description'],
                    'price' => $prod['price'],
                    'buying_price' => $prod['buying_price'],
                    'package_cost' => $prod['package_cost'],
                    'stock' => $prod['stock'],
                    'image' => $prod['image'],
                    'discount_type' => $prod['discount_type'],
                    'discount_value' => $prod['discount_value'],
                    'is_active' => true,
                ]
            );
        }

        // 4. Seed Combos (at least 10)
        $combosData = [
            ['name' => 'Complete Medical Protocol Box', 'price' => 3400.00, 'original_price' => 3550.00, 'desc' => 'Combination of survival kit and thermometer', 'prod_indices' => [2, 9]],
            ['name' => 'Smart Wearable & Audio Suite', 'price' => 3900.00, 'original_price' => 4450.00, 'desc' => 'TWS Earbuds and Smartwatch bundle', 'prod_indices' => [1, 8]],
            ['name' => 'Self Grooming & Groomer Bundle', 'price' => 1900.00, 'original_price' => 2250.00, 'desc' => 'Stainless hangers and Professional hair trimmer', 'prod_indices' => [0, 5]],
            ['name' => 'Home Wellness Fitness Kit', 'price' => 2400.00, 'original_price' => 2700.00, 'desc' => 'Yoga mat and Smart digital weighing machine', 'prod_indices' => [3, 7]],
            ['name' => 'Culinary Preparation Bundle', 'price' => 1600.00, 'original_price' => 1800.00, 'desc' => 'Mini Garlic chopper and Smart weight machine', 'prod_indices' => [4, 7]],
            ['name' => 'Office Space Productivity Kit', 'price' => 2200.00, 'original_price' => 2450.00, 'desc' => 'Mesh organizer and weighing scales', 'prod_indices' => [6, 7]],
            ['name' => 'Emergency Health & Survival Kit', 'price' => 4800.00, 'original_price' => 5050.00, 'desc' => 'Emergency kit, thermometer, and Smartwatch package', 'prod_indices' => [2, 1, 9]],
            ['name' => 'Smart Lifestyle Gear Suite', 'price' => 5400.00, 'original_price' => 5950.00, 'desc' => 'Earbuds, Smartwatch, and Yoga Mat', 'prod_indices' => [1, 3, 8]],
            ['name' => 'Smart Home Organizer & Closet Suite', 'price' => 1500.00, 'original_price' => 1650.00, 'desc' => 'Weight scale and Hangers combo pack', 'prod_indices' => [0, 7]],
            ['name' => 'General Store Best Seller Kit', 'price' => 2900.00, 'original_price' => 3150.00, 'desc' => 'Chopper, earbuds, and premium hangers', 'prod_indices' => [0, 4, 8]],
        ];

        $combos = [];
        foreach ($combosData as $comb) {
            $combo = Combo::updateOrCreate(
                ['slug' => Str::slug($comb['name'])],
                [
                    'name' => $comb['name'],
                    'description' => $comb['desc'],
                    'price' => $comb['price'],
                    'original_price' => $comb['original_price'],
                    'image' => 'https://placehold.co/600x600/ff6600/ffffff?text=' . urlencode($comb['name']),
                    'is_active' => true,
                ]
            );
            
            // Sync products to combo (detaches existing and links new ones)
            $syncIds = [];
            foreach ($comb['prod_indices'] as $idx) {
                $syncIds[] = $products[$idx]->id;
            }
            $combo->products()->sync($syncIds);
            $combos[] = $combo;
        }

        // 5. Seed Reviews (at least 10)
        $reviewsData = [
            ['rating' => 5, 'comment' => 'The digital scale is highly accurate and very premium!', 'user' => 'Admin User'],
            ['rating' => 4, 'comment' => 'Very solid smart watch, has a great battery backup.', 'user' => 'Test User'],
            ['rating' => 5, 'comment' => 'Must have emergency survival kit, absolute life saver!', 'user' => 'Saad Al Ghamdi'],
            ['rating' => 5, 'comment' => 'Extremely lightweight and noise cancelling is great.', 'user' => 'Abdur Rahman'],
            ['rating' => 4, 'comment' => 'The desk organizer was super clean and helps keep everything tidy.', 'user' => 'Mishary Rashid'],
            ['rating' => 5, 'comment' => 'Highly precise digital thermometer. Highly recommend!', 'user' => 'Omar Al-Mukhtar'],
            ['rating' => 4, 'comment' => 'Beautiful hangers, very strong stainless build quality.', 'user' => 'Sufian Ahmed'],
            ['rating' => 5, 'comment' => 'Excellent chopper, wireless and works fast!', 'user' => 'Farhan Akhtar'],
            ['rating' => 5, 'comment' => 'Very thick yoga mat, perfect for regular stretching.', 'user' => 'Sadman Sakib'],
            ['rating' => 4, 'comment' => 'Excellent trimmer kit, sharp blades and great battery.', 'user' => 'Jamil Hasan'],
        ];

        // Clear existing reviews to avoid duplicated review items in the seed
        Review::truncate();
        foreach ($reviewsData as $rev) {
            Review::create([
                'customer_name' => $rev['user'],
                'rating' => $rev['rating'],
                'comment' => $rev['comment'],
                'is_active' => true,
            ]);
        }

        // 6. Seed Orders and OrderItems (at least 10)
        // Clear existing orders to avoid duplicate order sets in the seed
        Order::query()->delete();
        OrderItem::query()->delete();

        for ($i = 1; $i <= 10; $i++) {
            $totalAmount = 0;
            $deliveryCharge = $i % 2 === 0 ? 60 : 120;
            $location = $i % 2 === 0 ? 'Inside Dhaka' : 'Outside Dhaka';
            
            $order = Order::create([
                'user_id' => null,
                'customer_name' => 'Seeded Customer ' . $i,
                'customer_email' => 'customer' . $i . '@example.com',
                'customer_phone' => '0171234567' . $i,
                'shipping_address' => 'Customer Address Protocol ' . $i,
                'district' => $i % 2 === 0 ? 'Dhaka' : 'Sylhet',
                'upazila' => $i % 2 === 0 ? 'Mirpur' : 'Beanibazar',
                'village' => 'Road ' . $i . ', House ' . ($i * 3),
                'total_amount' => 0,
                'delivery_charge' => $deliveryCharge,
                'delivery_location' => $location,
                'payment_method' => 'cod',
                'status' => ['pending', 'processing', 'completed', 'cancelled'][$i % 4],
                'is_active' => true,
            ]);

            // Add one product item
            $prod = $products[$i - 1];
            $price = $prod->discounted_price;
            $qty = 2;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $prod->id,
                'product_name' => $prod->name,
                'price' => $price,
                'buying_price' => $prod->buying_price,
                'package_cost' => $prod->package_cost,
                'quantity' => $qty,
            ]);
            $totalAmount += $price * $qty;

            // Add one combo item (for odd orders)
            if ($i % 2 === 1) {
                $comb = $combos[$i % 10];
                OrderItem::create([
                    'order_id' => $order->id,
                    'combo_id' => $comb->id,
                    'product_name' => $comb->name . ' (Bundle)',
                    'price' => $comb->price,
                    'buying_price' => $comb->products->sum('buying_price'),
                    'package_cost' => $comb->products->sum('package_cost'),
                    'quantity' => 1,
                ]);
                $totalAmount += $comb->price;
            }

            $order->update(['total_amount' => $totalAmount + $deliveryCharge]);
        }
    }
}
