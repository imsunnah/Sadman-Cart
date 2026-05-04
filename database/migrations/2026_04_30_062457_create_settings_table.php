<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $row) {
            $row->id();
            $row->string('key')->unique();
            $row->text('value')->nullable();
            $row->string('group')->default('general');
            $row->timestamps();
        });

        // Seed some default settings
        \DB::table('settings')->insert([
            ['key' => 'site_logo', 'value' => '/images/logo.png', 'group' => 'general'],
            ['key' => 'site_name', 'value' => 'Sadman Cart', 'group' => 'general'],
            ['key' => 'footer_about', 'value' => 'Bangladesh\'s leading destination for pure, natural, and premium organic products.', 'group' => 'footer'],
            ['key' => 'footer_address', 'value' => 'Rampura, Dhaka, Bangladesh', 'group' => 'footer'],
            ['key' => 'footer_phone', 'value' => '0123456789', 'group' => 'footer'],
            ['key' => 'footer_email', 'value' => 'admin@sadmancart.com', 'group' => 'footer'],
            ['key' => 'slider_images', 'value' => json_encode(['/images/slider1.jpg', '/images/slider2.jpg']), 'group' => 'slider'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
