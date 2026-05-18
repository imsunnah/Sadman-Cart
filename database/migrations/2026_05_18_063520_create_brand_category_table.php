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
        Schema::create('brand_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();
        });

        // Seed initial brand-category relationships based on product associations
        $products = \DB::table('products')
            ->whereNotNull('brand_id')
            ->whereNotNull('category_id')
            ->select('brand_id', 'category_id')
            ->distinct()
            ->get();

        foreach ($products as $p) {
            \DB::table('brand_category')->insert([
                'brand_id' => $p->brand_id,
                'category_id' => $p->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_category');
    }
};
