<?php
/*
f:\Practise\Personal\E-commerce\E-commerce\database\migrations\2026_04_30_083855_add_parent_id_to_categories_table.php
*/
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $header) {
            $header->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $header) {
            $header->dropForeign(['parent_id']);
            $header->dropColumn('parent_id');
        });
    }
};
