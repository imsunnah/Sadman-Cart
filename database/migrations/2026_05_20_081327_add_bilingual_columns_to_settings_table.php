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
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('value', 'value_en');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->text('value_bn')->nullable()->after('value_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('value_bn');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('value_en', 'value');
        });
    }
};
