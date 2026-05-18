<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->after('sub_category_id')->constrained('brands')->nullOnDelete();
        });

        // Let's dynamically map existing products to realistic seeded brand IDs!
        $deshiBrand = \DB::table('brands')->where('slug', 'deshi-organic')->first();
        $alNoorBrand = \DB::table('brands')->where('slug', 'al-noor')->first();
        $purePressBrand = \DB::table('brands')->where('slug', 'pure-press')->first();
        $sadmanBrand = \DB::table('brands')->where('slug', 'sadman-premium')->first();

        if ($deshiBrand) {
            \DB::table('products')->where('name', 'like', '%ghee%')->update(['brand_id' => $deshiBrand->id]);
        }
        if ($alNoorBrand) {
            \DB::table('products')->where('name', 'like', '%honey%')->orWhere('name', 'like', '%modhu%')->update(['brand_id' => $alNoorBrand->id]);
        }
        if ($purePressBrand) {
            \DB::table('products')->where('name', 'like', '%oil%')->orWhere('name', 'like', '%tel%')->update(['brand_id' => $purePressBrand->id]);
        }
        if ($sadmanBrand) {
            \DB::table('products')->whereNull('brand_id')->update(['brand_id' => $sadmanBrand->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
    }
};
