<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_businesses')->default(1)->after('trial_days');
        });

        // Set realistic defaults for starter, pro, agency
        DB::table('plans')->where('slug', 'starter')->update(['max_businesses' => 1]);
        DB::table('plans')->where('slug', 'pro-growth')->update(['max_businesses' => 3]);
        DB::table('plans')->where('slug', 'agency')->update(['max_businesses' => 10]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('max_businesses');
        });
    }
};
