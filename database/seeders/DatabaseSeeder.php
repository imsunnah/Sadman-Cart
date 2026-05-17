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
            'site_name' => 'Sadman Cart',
            'contact_email' => 'contact@sadmancart.com',
            'contact_phone' => '+880123456789',
            'currency' => '৳',
            'address' => 'Dhaka, Bangladesh',
            'site_facebook_url' => 'https://facebook.com/sadmancart',
            'site_instagram_url' => 'https://instagram.com/sadmancart',
            'shipping_policy_summary' => 'Express delivery within 2-3 business days inside Dhaka, 3-5 days outside Dhaka.',
            'return_policy_summary' => 'Easy return protocol within 7 days of package confirmation.',
            'slider_images' => json_encode([
                'https://placehold.co/1920x600/003366/ffffff?text=Premium+Surgical+Equipments',
                'https://placehold.co/1920x600/ff6600/ffffff?text=Latest+Tech+Gadgets',
                'https://placehold.co/1920x600/003366/ffffff?text=Daily+Home+Accessories'
            ])
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 4. Run Store Seeder and Page Seeder
        $this->call([
            StoreSeeder::class,
            PageSeeder::class,
        ]);
    }
}
