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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('business_name')->nullable();
            $table->string('category')->nullable();
            $table->string('outlets')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new'); // new, contacted, qualified, converted, closed
            $table->text('notes')->nullable();
            $table->string('source')->default('contact_page');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
