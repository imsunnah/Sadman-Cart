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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert some base dynamic brands
        $baseBrands = [
            ['name' => 'Deshi Organic', 'slug' => 'deshi-organic'],
            ['name' => 'Al-Noor', 'slug' => 'al-noor'],
            ['name' => 'Pure Press', 'slug' => 'pure-press'],
            ['name' => 'Sadman Premium', 'slug' => 'sadman-premium'],
            ['name' => 'Applied Nutrition', 'slug' => 'applied-nutrition'],
            ['name' => 'Ceylon Naturals', 'slug' => 'ceylon-naturals'],
        ];

        foreach ($baseBrands as $brand) {
            \DB::table('brands')->insert(array_merge($brand, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
