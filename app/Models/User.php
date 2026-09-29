<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'is_super_admin', 'is_agent', 'agent_id', 'commission_rate', 'agent_code', 'agent_notes'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'is_agent' => 'boolean',
            'commission_rate' => 'decimal:2',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    public function isAgent(): bool
    {
        return (bool) $this->is_agent;
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class, 'owner_user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Agent: sales records where this user is the agent.
     */
    public function agentSales(): HasMany
    {
        return $this->hasMany(AgentSale::class, 'agent_id');
    }

    /**
     * Agent: merchants this agent has onboarded.
     */
    public function referredMerchants(): HasMany
    {
        return $this->hasMany(User::class, 'agent_id');
    }

    /**
     * Merchant: which agent referred/onboarded this user.
     */
    public function referringAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the current active or trial subscription plan for this user.
     */
    public function currentPlan(): ?Plan
    {
        $business = $this->businesses()
            ->with('plan')
            ->whereNotNull('plan_id')
            ->orderByRaw("CASE WHEN subscription_status = 'active' THEN 1 WHEN subscription_status = 'trial' THEN 2 ELSE 3 END")
            ->first();

        return $business?->plan ?? Plan::getDefaultPlan();
    }

    /**
     * Maximum business locations this user is permitted to create.
     */
    public function maxBusinessesAllowed(): int
    {
        if ($this->isSuperAdmin()) {
            return 999999;
        }

        $plan = $this->currentPlan();

        return $plan ? $plan->maxBusinesses() : 1;
    }

    /**
     * Check if user can add more businesses under their current plan.
     */
    public function canAddMoreBusinesses(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->businesses()->count() < $this->maxBusinessesAllowed();
    }

    /**
     * Generate a unique agent code like AGT-ABCD.
     */
    public static function generateAgentCode(): string
    {
        do {
            $code = 'AGT-'.strtoupper(Str::random(4));
        } while (self::where('agent_code', $code)->exists());

        return $code;
    }

    /**
     * Total commission earned by this agent (pending + approved + paid).
     */
    public function totalCommissionEarned(): float
    {
        return (float) $this->agentSales()->sum('commission_amount');
    }

    /**
     * Total commission paid out to this agent.
     */
    public function totalCommissionPaid(): float
    {
        return (float) $this->agentSales()->where('commission_status', 'paid')->sum('commission_amount');
    }
}
