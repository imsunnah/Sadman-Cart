<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('district')->nullable()->after('shipping_address');
            $table->string('upazila')->nullable()->after('district');
            $table->string('village')->nullable()->after('upazila');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['district', 'upazila', 'village']);
        });
    }
};
