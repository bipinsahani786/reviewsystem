<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Is this user an agent / sales rep?
            $table->boolean('is_agent')->default(false)->after('is_super_admin');

            // Which agent referred/onboarded this merchant? (nullable)
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete()->after('is_agent');

            // Agent commission rate in percent (e.g. 10 = 10%)
            $table->decimal('commission_rate', 5, 2)->default(10.00)->after('agent_id');

            // Unique shareable agent code for tracking (e.g. AGT-BIPN)
            $table->string('agent_code', 20)->nullable()->unique()->after('commission_rate');

            // Notes about the agent (territory, contact info, etc.)
            $table->text('agent_notes')->nullable()->after('agent_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn(['is_agent', 'agent_id', 'commission_rate', 'agent_code', 'agent_notes']);
        });
    }
};
