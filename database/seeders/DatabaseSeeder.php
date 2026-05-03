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

        // 2. Create Test User
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        // 3. Setup Default Settings
        $settings = [
            'site_name' => 'Sadman Cart',
            'contact_email' => 'contact@sadmancart.com',
            'contact_phone' => '+880123456789',
            'currency' => '৳',
            'address' => 'Dhaka, Bangladesh',
            'slider_images' => json_encode([
                'https://placehold.co/1920x600/003366/ffffff?text=Premium+Surgical+Equipments',
                'https://placehold.co/1920x600/ff6600/ffffff?text=Latest+Tech+Gadgets',
                'https://placehold.co/1920x600/003366/ffffff?text=Daily+Home+Accessories'
            ])
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 4. Run Store Seeder for Categories/Products
        $this->call(StoreSeeder::class);
    }
}
