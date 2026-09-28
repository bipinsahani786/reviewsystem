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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('subscription_status')->default('trial')->after('plan_id'); // trial, active, expired, cancelled
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_status');
            $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');
            $table->string('billing_cycle')->default('monthly')->after('subscription_ends_at');
            $table->string('razorpay_customer_id')->nullable()->after('billing_cycle');
            $table->string('razorpay_subscription_id')->nullable()->after('razorpay_customer_id');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'trial_ends_at',
                'subscription_ends_at',
                'billing_cycle',
                'razorpay_customer_id',
                'razorpay_subscription_id',
                'razorpay_payment_id',
            ]);
        });
    }
};
