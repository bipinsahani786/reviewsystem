<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_sales', function (Blueprint $table) {
            $table->id();

            // The agent who made this sale
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();

            // The merchant account that was onboarded
            $table->foreignId('merchant_id')->constrained('users')->cascadeOnDelete();

            // The business that was created for this merchant
            $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();

            // The plan the merchant subscribed to
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();

            // Sale status
            $table->enum('status', ['pending', 'active', 'cancelled', 'expired'])->default('pending');

            // Commission info
            $table->decimal('plan_price', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->enum('commission_status', ['pending', 'approved', 'paid'])->default('pending');
            $table->date('commission_paid_at')->nullable();

            // Billing cycle of this sale
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');

            // Notes for this specific sale
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_sales');
    }
};
