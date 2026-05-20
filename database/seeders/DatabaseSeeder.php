<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@sadmancart.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Create 9 more Users (making 10 total)
        $usersData = [
            ['name' => 'Test User', 'email' => 'test@example.com', 'role' => 'user'],
            ['name' => 'Saad Al Ghamdi', 'email' => 'saad@example.com', 'role' => 'user'],
            ['name' => 'Abdur Rahman', 'email' => 'abdur@example.com', 'role' => 'user'],
            ['name' => 'Mishary Rashid', 'email' => 'mishary@example.com', 'role' => 'user'],
            ['name' => 'Omar Al-Mukhtar', 'email' => 'omar@example.com', 'role' => 'user'],
            ['name' => 'Sufian Ahmed', 'email' => 'sufian@example.com', 'role' => 'user'],
            ['name' => 'Farhan Akhtar', 'email' => 'farhan@example.com', 'role' => 'user'],
            ['name' => 'Sadman Sakib', 'email' => 'sadman@example.com', 'role' => 'user'],
            ['name' => 'Jamil Hasan', 'email' => 'jamil@example.com', 'role' => 'user'],
        ];

        foreach ($usersData as $ud) {
            User::updateOrCreate(
                ['email' => $ud['email']],
                [
                    'name' => $ud['name'],
                    'password' => Hash::make('password'),
                    'role' => $ud['role'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Setup Default Settings (10 total keys)
        $settings = [
            'site_name' => ['en' => 'Sadman Cart', 'bn' => 'সাদমান কার্ট'],
            'contact_email' => ['en' => 'contact@sadmancart.com', 'bn' => 'contact@sadmancart.com'],
            'contact_phone' => ['en' => '+880123456789', 'bn' => '+880123456789'],
            'currency' => ['en' => '৳', 'bn' => '৳'],
            'address' => ['en' => 'Dhaka, Bangladesh', 'bn' => 'ঢাকা, বাংলাদেশ'],
            'site_facebook_url' => ['en' => 'https://facebook.com/sadmancart', 'bn' => 'https://facebook.com/sadmancart'],
            'site_instagram_url' => ['en' => 'https://instagram.com/sadmancart', 'bn' => 'https://instagram.com/sadmancart'],
            'shipping_policy_summary' => ['en' => 'Express delivery within 2-3 business days inside Dhaka, 3-5 days outside Dhaka.', 'bn' => 'ঢাকায় ২-৩ দিন এবং ঢাকার বাইরে ৩-৫ দিনে এক্সপ্রেস ডেলিভারি।'],
            'return_policy_summary' => ['en' => 'Easy return protocol within 7 days of package confirmation.', 'bn' => '৭ দিনের মধ্যে সহজ রিটার্ন সুবিধা।'],
            'slider_images' => ['en' => json_encode([
                'https://placehold.co/1920x600/003366/ffffff?text=Premium+Surgical+Equipments',
                'https://placehold.co/1920x600/ff6600/ffffff?text=Latest+Tech+Gadgets',
                'https://placehold.co/1920x600/003366/ffffff?text=Daily+Home+Accessories'
            ]), 'bn' => json_encode([
                'https://placehold.co/1920x600/003366/ffffff?text=Premium+Surgical+Equipments',
                'https://placehold.co/1920x600/ff6600/ffffff?text=Latest+Tech+Gadgets',
                'https://placehold.co/1920x600/003366/ffffff?text=Daily+Home+Accessories'
            ])]
        ];

        foreach ($settings as $key => $values) {
            Setting::updateOrCreate(
                ['key' => $key], 
                [
                    'value_en' => $values['en'],
                    'value_bn' => $values['bn']
                ]
            );
        }

        // 4. Run Store Seeder and Page Seeder
        $this->call([
            StoreSeeder::class,
            PageSeeder::class,
        ]);
    }
}
