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
            ['name_en' => 'Surgical Products', 'name_bn' => 'সার্জিক্যাল পণ্য', 'description_en' => 'Medical and surgical equipments', 'description_bn' => 'চিকিৎসা ও সার্জিক্যাল সরঞ্জাম', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Surgical'],
            ['name_en' => 'Electronic Gadgets', 'name_bn' => 'ইলেকট্রনিক গ্যাজেট', 'description_en' => 'Modern electronic gadgets', 'description_bn' => 'আধুনিক ইলেকট্রনিক গ্যাজেট', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Gadgets'],
            ['name_en' => 'Regular Accessories', 'name_bn' => 'রেগুলার এক্সেসরিজ', 'description_en' => 'Home and lifestyle accessories', 'description_bn' => 'বাড়ি এবং লাইফস্টাইল এক্সেসরিজ', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Accessories'],
            ['name_en' => 'Fitness & Health', 'name_bn' => 'ফিটনেস ও স্বাস্থ্য', 'description_en' => 'Smart health monitoring and gym gear', 'description_bn' => 'স্মার্ট হেলথ মনিটরিং এবং জিম গিয়ার', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Fitness'],
            ['name_en' => 'Home Appliances', 'name_bn' => 'হোম অ্যাপ্লায়েন্স', 'description_en' => 'Useful kitchen and smart home utilities', 'description_bn' => 'উপযোগী রান্নাঘর এবং স্মার্ট বাড়ির সরঞ্জাম', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Home+Appliances'],
            ['name_en' => 'Personal Care', 'name_bn' => 'ব্যক্তিগত যত্ন', 'description_en' => 'Self care and grooming tools', 'description_bn' => 'সেলফ কেয়ার এবং গ্রুমিং টুলস', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Personal+Care'],
            ['name_en' => 'Office Supplies', 'name_bn' => 'অফিস সাপ্লাইস', 'description_en' => 'Organizing and high quality stationery', 'description_bn' => 'অর্গানাইজিং এবং উচ্চ মানের স্টেশনারি', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Office'],
            ['name_en' => 'Outdoor & Travel', 'name_bn' => 'আউটডোর এবং ট্রাভেল', 'description_en' => 'Smart carry luggage and camping gear', 'description_bn' => 'স্মার্ট ক্যারি লাগেজ এবং ক্যাম্পিং গিয়ার', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Travel'],
            ['name_en' => 'Automotive Accessories', 'name_bn' => 'অটোমোটিভ এক্সেসরিজ', 'description_en' => 'Car detailing and dashboard equipment', 'description_bn' => 'গাড়ির ড্যাশবোর্ড সরঞ্জাম', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Auto'],
            ['name_en' => 'Smart Kitchen Tools', 'name_bn' => 'স্মার্ট কিচেন টুলস', 'description_en' => 'Intelligent culinary and baking helper equipment', 'description_bn' => 'স্মার্ট রান্নার সরঞ্জাম', 'image' => 'https://placehold.co/600x600/003366/ffffff?text=Kitchen'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = Category::updateOrCreate(
                ['slug' => Str::slug($c['name_en'])],
                [
                    'name_en' => $c['name_en'],
                    'name_bn' => $c['name_bn'],
                    'description_en' => $c['description_en'],
                    'description_bn' => $c['description_bn'],
                    'image' => $c['image'],
                ]
            );
        }

        // 2. Seed SubCategories (at least 10)
        $subCategoriesData = [
            ['category_idx' => 0, 'name_en' => 'First Aid Kits', 'name_bn' => 'ফার্স্ট এইড কিট'],
            ['category_idx' => 0, 'name_en' => 'Diagnostic Tools', 'name_bn' => 'ডায়াগনস্টিক টুলস'],
            ['category_idx' => 1, 'name_en' => 'Smart Wearables', 'name_bn' => 'স্মার্ট পরিধানযোগ্য ডিভাইস'],
            ['category_idx' => 1, 'name_en' => 'Wireless Audio', 'name_bn' => 'ওয়্যারলেস অডিও'],
            ['category_idx' => 2, 'name_en' => 'Storage Solutions', 'name_bn' => 'স্টোরেজ সলিউশন'],
            ['category_idx' => 2, 'name_en' => 'Hangers & Hooks', 'name_bn' => 'হ্যাঙ্গার এবং হুকস'],
            ['category_idx' => 3, 'name_en' => 'Yoga & Cardio Tools', 'name_bn' => 'যোগব্যায়াম এবং কার্ডিও টুলস'],
            ['category_idx' => 4, 'name_en' => 'Kitchen Utilities', 'name_bn' => 'কিচেন সামগ্রী'],
            ['category_idx' => 5, 'name_en' => 'Hair Grooming Tools', 'name_bn' => 'হেয়ার গ্রুমিং টুলস'],
            ['category_idx' => 6, 'name_en' => 'Desk Organizers', 'name_bn' => 'ডেস্ক অর্গানাইজারস'],
        ];

        $subCategories = [];
        foreach ($subCategoriesData as $sub) {
            $catId = $categories[$sub['category_idx']]->id;
            $subCategories[] = SubCategory::updateOrCreate(
                ['slug' => Str::slug($sub['name_en']), 'category_id' => $catId],
                [
                    'name_en' => $sub['name_en'],
                    'name_bn' => $sub['name_bn'],
                ]
            );
        }

        // 3. Seed Products (at least 10)
        $productsData = [
            [
                'category_idx' => 2, 'sub_category_idx' => 5,
                'name_en' => 'Stainless Steel Hanger (Set of 10)', 'name_bn' => 'স্টেইনলেস স্টিল হ্যাঙ্গার (১০ পিস)', 'sku' => 'ACC-HGR-01',
                'description_en' => 'Durable stainless steel hangers for clothes.', 'description_bn' => 'কাপড়ের জন্য টেকসই স্টেইনলেস স্টিলের হ্যাঙ্গার।',
                'price' => 450, 'buying_price' => 200, 'package_cost' => 20, 'stock' => 100,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Hangers',
                'discount_type' => 'percentage', 'discount_value' => 10.00
            ],
            [
                'category_idx' => 1, 'sub_category_idx' => 2,
                'name_en' => 'Bluetooth Smart Watch', 'name_bn' => 'ব্লুটুথ স্মার্ট ওয়াচ', 'sku' => 'GDT-SW-01',
                'description_en' => 'Waterproof smart watch with heart rate monitor.', 'description_bn' => 'হার্ট রেট মনিটর সহ ওয়াটারপ্রুফ স্মার্ট ওয়াচ।',
                'price' => 2500, 'buying_price' => 1200, 'package_cost' => 50, 'stock' => 25,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Smart+Watch',
                'discount_type' => 'fixed', 'discount_value' => 300.00
            ],
            [
                'category_idx' => 0, 'sub_category_idx' => 1,
                'name_en' => 'Digital Thermometer', 'name_bn' => 'ডিজিটাল থার্মোমিটার', 'sku' => 'SUR-TM-01',
                'description_en' => 'Accurate digital thermometer for medical use.', 'description_bn' => 'চিকিৎসা ব্যবহারের জন্য সঠিক ডিজিটাল থার্মোমিটার।',
                'price' => 350, 'buying_price' => 150, 'package_cost' => 10, 'stock' => 200,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Thermometer',
                'discount_type' => null, 'discount_value' => null
            ],
            [
                'category_idx' => 3, 'sub_category_idx' => 6,
                'name_en' => 'Premium Yoga Mat', 'name_bn' => 'প্রিমিয়াম ইয়োগা ম্যাট', 'sku' => 'FIT-YM-01',
                'description_en' => 'Extra thick non-slip yoga mat with carrying strap.', 'description_bn' => 'ক্যারিয়িং স্ট্র্যাপ সহ অতিরিক্ত পুরু নন-স্লিপ যোগ ম্যাট।',
                'price' => 1500, 'buying_price' => 700, 'package_cost' => 30, 'stock' => 80,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Yoga+Mat',
                'discount_type' => 'percentage', 'discount_value' => 15.00
            ],
            [
                'category_idx' => 4, 'sub_category_idx' => 7,
                'name_en' => 'Electric Mini Garlic Chopper', 'name_bn' => 'ইলেকট্রিক মিনি গার্লিক চপার', 'sku' => 'KIT-GC-01',
                'description_en' => 'Wireless smart mini food processor & garlic mincer.', 'description_bn' => 'ওয়্যারলেস স্মার্ট মিনি ফুড প্রসেসর।',
                'price' => 850, 'buying_price' => 350, 'package_cost' => 15, 'stock' => 120,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Mini+Chopper',
                'discount_type' => 'percentage', 'discount_value' => 5.00
            ],
            [
                'category_idx' => 5, 'sub_category_idx' => 8,
                'name_en' => 'Professional Hair Clipper', 'name_bn' => 'প্রফেশনাল হেয়ার ক্লিপার', 'sku' => 'PCM-HC-01',
                'description_en' => 'Rechargeable cordless beard and hair trimmer kit.', 'description_bn' => 'রিচার্জেবল কর্ডলেস দাড়ি এবং চুল ট্রিমার কিট।',
                'price' => 1800, 'buying_price' => 800, 'package_cost' => 40, 'stock' => 40,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Hair+Clipper',
                'discount_type' => 'fixed', 'discount_value' => 200.00
            ],
            [
                'category_idx' => 6, 'sub_category_idx' => 9,
                'name_en' => 'Multi-functional Desk Organizer', 'name_bn' => 'মাল্টি-ফাংশনাল ডেস্ক অর্গানাইজার', 'sku' => 'OFC-DO-01',
                'description_en' => 'Mesh metal desk organizer with 6 compartments and drawer.', 'description_bn' => '৬ টি কম্পার্টমেন্ট এবং ড্রয়ার সহ মেশ মেটাল ডেস্ক অর্গানাইজার।',
                'price' => 950, 'buying_price' => 400, 'package_cost' => 25, 'stock' => 65,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Desk+Organizer',
                'discount_type' => null, 'discount_value' => null
            ],
            [
                'category_idx' => 2, 'sub_category_idx' => 4,
                'name_en' => 'Digital Weight Machine', 'name_bn' => 'ডিজিটাল ওয়েট মেশিন', 'sku' => 'ACC-WM-01',
                'description_en' => 'High precision digital weight machine for home use.', 'description_bn' => 'বাড়িতে ব্যবহারের জন্য উচ্চ নির্ভুলতা ডিজিটাল ওজন মেশিন।',
                'price' => 1200, 'buying_price' => 500, 'package_cost' => 30, 'stock' => 50,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Weight+Machine',
                'discount_type' => 'percentage', 'discount_value' => 10.00
            ],
            [
                'category_idx' => 1, 'sub_category_idx' => 3,
                'name_en' => 'Wireless TWS Earbuds', 'name_bn' => 'ওয়্যারলেস TWS ইয়ারবাড', 'sku' => 'GDT-EB-01',
                'description_en' => 'Noise-cancelling wireless TWS earbuds with deep bass.', 'description_bn' => 'গভীর বাস সহ নয়েজ-ক্যানসেলিং ওয়্যারলেস TWS ইয়ারবাড।',
                'price' => 1950, 'buying_price' => 900, 'package_cost' => 20, 'stock' => 110,
                'image' => 'https://placehold.co/600x600/003366/ffffff?text=Earbuds',
                'discount_type' => 'percentage', 'discount_value' => 20.00
            ],
            [
                'category_idx' => 0, 'sub_category_idx' => 0,
                'name_en' => 'Emergency Survival Kit', 'name_bn' => 'ইমার্জেন্সি সারভাইভাল কিট', 'sku' => 'SUR-SK-01',
                'description_en' => 'All-in-one professional emergency survival kit box.', 'description_bn' => 'অল-ইন-ওয়ান প্রফেশনাল ইমার্জেন্সি সারভাইভাল কিট বক্স।',
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
                    'name_en' => $prod['name_en'],
                    'name_bn' => $prod['name_bn'],
                    'slug' => Str::slug($prod['name_en']),
                    'description_en' => $prod['description_en'],
                    'description_bn' => $prod['description_bn'],
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
            ['name_en' => 'Complete Medical Protocol Box', 'name_bn' => 'সম্পূর্ণ মেডিকেল প্রোটোকল বক্স', 'price' => 3400.00, 'original_price' => 3550.00, 'desc_en' => 'Combination of survival kit and thermometer', 'desc_bn' => 'সারভাইভাল কিট এবং থার্মোমিটারের কম্বো', 'prod_indices' => [2, 9]],
            ['name_en' => 'Smart Wearable & Audio Suite', 'name_bn' => 'স্মার্ট ওয়্যারেবল এবং অডিও সুইট', 'price' => 3900.00, 'original_price' => 4450.00, 'desc_en' => 'TWS Earbuds and Smartwatch bundle', 'desc_bn' => 'TWS ইয়ারবাড এবং স্মার্টওয়াচ বান্ডেল', 'prod_indices' => [1, 8]],
            ['name_en' => 'Self Grooming & Groomer Bundle', 'name_bn' => 'সেলফ গ্রুমিং বান্ডেল', 'price' => 1900.00, 'original_price' => 2250.00, 'desc_en' => 'Stainless hangers and Professional hair trimmer', 'desc_bn' => 'স্টেইনলেস হ্যাঙ্গার এবং পেশাদার চুল ট্রিমার', 'prod_indices' => [0, 5]],
            ['name_en' => 'Home Wellness Fitness Kit', 'name_bn' => 'হোম ওয়েলনেস ফিটনেস কিট', 'price' => 2400.00, 'original_price' => 2700.00, 'desc_en' => 'Yoga mat and Smart digital weighing machine', 'desc_bn' => 'যোগ ম্যাট এবং স্মার্ট ডিজিটাল ওয়েট মেশিন', 'prod_indices' => [3, 7]],
            ['name_en' => 'Culinary Preparation Bundle', 'name_bn' => 'রান্না প্রস্তুতি বান্ডেল', 'price' => 1600.00, 'original_price' => 1800.00, 'desc_en' => 'Mini Garlic chopper and Smart weight machine', 'desc_bn' => 'মিনি গার্লিক চপার এবং স্মার্ট ওয়েট মেশিন', 'prod_indices' => [4, 7]],
            ['name_en' => 'Office Space Productivity Kit', 'name_bn' => 'অফিস স্পেস প্রোডাক্টিভিটি কিট', 'price' => 2200.00, 'original_price' => 2450.00, 'desc_en' => 'Mesh organizer and weighing scales', 'desc_bn' => 'মেশ অর্গানাইজার এবং ওয়েট স্কেল', 'prod_indices' => [6, 7]],
            ['name_en' => 'Emergency Health & Survival Kit', 'name_bn' => 'ইমার্জেন্সি হেলথ অ্যান্ড সারভাইভাল কিট', 'price' => 4800.00, 'original_price' => 5050.00, 'desc_en' => 'Emergency kit, thermometer, and Smartwatch package', 'desc_bn' => 'ইমার্জেন্সি কিট, থার্মোমিটার এবং স্মার্টওয়াচ প্যাকেজ', 'prod_indices' => [2, 1, 9]],
            ['name_en' => 'Smart Lifestyle Gear Suite', 'name_bn' => 'স্মার্ট লাইফস্টাইল গিয়ার সুইট', 'price' => 5400.00, 'original_price' => 5950.00, 'desc_en' => 'Earbuds, Smartwatch, and Yoga Mat', 'desc_bn' => 'ইয়ারবাড, স্মার্টওয়াচ এবং ইয়োগা ম্যাট', 'prod_indices' => [1, 3, 8]],
            ['name_en' => 'Smart Home Organizer & Closet Suite', 'name_bn' => 'স্মার্ট হোম অর্গানাইজার এবং ক্লোজেট সুইট', 'price' => 1500.00, 'original_price' => 1650.00, 'desc_en' => 'Weight scale and Hangers combo pack', 'desc_bn' => 'ওয়েট স্কেল এবং হ্যাঙ্গারস কম্বো প্যাক', 'prod_indices' => [0, 7]],
            ['name_en' => 'General Store Best Seller Kit', 'name_bn' => 'জেনারেল স্টোর বেস্ট সেলার কিট', 'price' => 2900.00, 'original_price' => 3150.00, 'desc_en' => 'Chopper, earbuds, and premium hangers', 'desc_bn' => 'চপার, ইয়ারবাড এবং প্রিমিয়াম হ্যাঙ্গারস', 'prod_indices' => [0, 4, 8]],
        ];

        $combos = [];
        foreach ($combosData as $comb) {
            $combo = Combo::updateOrCreate(
                ['slug' => Str::slug($comb['name_en'])],
                [
                    'name_en' => $comb['name_en'],
                    'name_bn' => $comb['name_bn'],
                    'description_en' => $comb['desc_en'],
                    'description_bn' => $comb['desc_bn'],
                    'price' => $comb['price'],
                    'original_price' => $comb['original_price'],
                    'image' => 'https://placehold.co/600x600/ff6600/ffffff?text=' . urlencode($comb['name_en']),
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
                'product_name' => $prod->name_en,
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
                    'product_name' => $comb->name_en . ' (Bundle)',
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
