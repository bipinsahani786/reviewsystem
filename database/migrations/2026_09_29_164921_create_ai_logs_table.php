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
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
            $table->string('business_name')->nullable();
            $table->string('provider')->default('gemini');
            $table->string('model')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->json('tags')->nullable();
            $table->string('language')->default('hinglish');
            $table->string('status')->default('success')->index(); // success, rate_limit, unavailable, timeout, error, fallback
            $table->integer('http_status')->nullable();
            $table->integer('latency_ms')->default(0);
            $table->text('generated_text')->nullable();
            $table->text('error_message')->nullable();
            $table->boolean('is_fallback')->default(false)->index();
            $table->string('customer_ip', 45)->nullable();
            $table->timestamps();

            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
