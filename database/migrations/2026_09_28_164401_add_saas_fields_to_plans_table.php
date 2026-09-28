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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('billing_period')->default('monthly')->after('billing_cycle');
            $table->integer('trial_days')->default(14)->after('billing_period');
            $table->decimal('yearly_price', 10, 2)->nullable()->after('price');
            $table->boolean('is_default')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['billing_period', 'trial_days', 'yearly_price', 'is_default']);
        });
    }
};
