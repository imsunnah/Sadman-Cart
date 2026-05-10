<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'delivery_charge_inside_dhaka',
                'value' => '60',
                'group' => 'delivery',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'delivery_charge_outside_dhaka',
                'value' => '120',
                'group' => 'delivery',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['delivery_charge_inside_dhaka', 'delivery_charge_outside_dhaka'])
            ->delete();
    }
};
