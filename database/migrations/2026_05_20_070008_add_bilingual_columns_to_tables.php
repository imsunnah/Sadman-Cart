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
        // 1. Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('description', 'description_en');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name_en');
            $table->text('description_bn')->nullable()->after('description_en');
        });

        // 2. SubCategories
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('description', 'description_en');
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name_en');
            $table->text('description_bn')->nullable()->after('description_en');
        });

        // 3. Brands
        Schema::table('brands', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name_en');
        });

        // 4. Products
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('description', 'description_en');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name_en');
            $table->text('description_bn')->nullable()->after('description_en');
        });

        // 5. Combos
        Schema::table('combos', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('description', 'description_en');
        });
        Schema::table('combos', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name_en');
            $table->text('description_bn')->nullable()->after('description_en');
        });

        // 6. Pages
        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('title', 'title_en');
            $table->renameColumn('description', 'description_en');
            $table->renameColumn('content', 'content_en');
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_bn')->nullable()->after('title_en');
            $table->text('description_bn')->nullable()->after('description_en');
            $table->longText('content_bn')->nullable()->after('content_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 6. Pages
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['title_bn', 'description_bn', 'content_bn']);
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('title_en', 'title');
            $table->renameColumn('description_en', 'description');
            $table->renameColumn('content_en', 'content');
        });

        // 5. Combos
        Schema::table('combos', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'description_bn']);
        });
        Schema::table('combos', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->renameColumn('description_en', 'description');
        });

        // 4. Products
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'description_bn']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->renameColumn('description_en', 'description');
        });

        // 3. Brands
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['name_bn']);
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
        });

        // 2. SubCategories
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'description_bn']);
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->renameColumn('description_en', 'description');
        });

        // 1. Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'description_bn']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->renameColumn('description_en', 'description');
        });
    }
};
