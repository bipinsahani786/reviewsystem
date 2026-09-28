<?php

use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaultPlan = Plan::where('is_default', true)->first()
            ?? Plan::orderBy('id')->first();

        DB::table('businesses')
            ->whereNull('trial_ends_at')
            ->update([
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'billing_cycle' => 'monthly',
                'plan_id' => $defaultPlan?->id ?? 1,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
