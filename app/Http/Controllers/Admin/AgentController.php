<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentSale;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AgentController extends Controller
{
    /**
     * List all agents with performance metrics.
     */
    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin();

        $query = User::where('is_agent', true)
            ->withCount(['agentSales', 'referredMerchants'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('agent_code', 'like', "%{$search}%");
            });
        }

        $agents = $query->paginate(15)->withQueryString();

        // Platform-wide agent metrics
        $totalAgents = User::where('is_agent', true)->count();
        $totalSalesMade = AgentSale::count();
        $totalCommissionEarned = (float) AgentSale::sum('commission_amount');
        $totalCommissionPaid = (float) AgentSale::where('commission_status', 'paid')->sum('commission_amount');
        $pendingCommission = $totalCommissionEarned - $totalCommissionPaid;

        return view('admin.agents.index', compact(
            'agents',
            'totalAgents',
            'totalSalesMade',
            'totalCommissionEarned',
            'totalCommissionPaid',
            'pendingCommission'
        ));
    }

    /**
     * Show form to create a new agent account.
     */
    public function create(): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.agents.create');
    }

    /**
     * Store a new agent account.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'agent_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $agent = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_agent' => true,
            'is_super_admin' => false,
            'commission_rate' => $validated['commission_rate'],
            'agent_code' => User::generateAgentCode(),
            'agent_notes' => $validated['agent_notes'] ?? null,
        ]);

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', "Agent account for '{$agent->name}' created! Code: {$agent->agent_code}");
    }

    /**
     * Show detailed performance dashboard for a single agent.
     */
    public function show(User $agent): View
    {
        $this->authorizeSuperAdmin();

        abort_unless($agent->is_agent, 404);

        $sales = AgentSale::where('agent_id', $agent->id)
            ->with(['merchant', 'business', 'plan'])
            ->latest()
            ->paginate(15);

        // Performance metrics
        $totalSales = AgentSale::where('agent_id', $agent->id)->count();
        $activeSales = AgentSale::where('agent_id', $agent->id)->where('status', 'active')->count();
        $totalRevenue = (float) AgentSale::where('agent_id', $agent->id)->sum('plan_price');
        $totalCommission = (float) AgentSale::where('agent_id', $agent->id)->sum('commission_amount');
        $paidCommission = (float) AgentSale::where('agent_id', $agent->id)->where('commission_status', 'paid')->sum('commission_amount');
        $pendingCommission = $totalCommission - $paidCommission;

        // Monthly breakdown (last 6 months)
        $monthExpr = config('database.default') === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : 'DATE_FORMAT(created_at, "%Y-%m")';

        $monthlySales = AgentSale::where('agent_id', $agent->id)
            ->selectRaw("{$monthExpr} as month, COUNT(*) as count, SUM(plan_price) as revenue, SUM(commission_amount) as commission")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.agents.show', compact(
            'agent',
            'sales',
            'totalSales',
            'activeSales',
            'totalRevenue',
            'totalCommission',
            'paidCommission',
            'pendingCommission',
            'monthlySales'
        ));
    }

    /**
     * Show form to edit agent details.
     */
    public function edit(User $agent): View
    {
        $this->authorizeSuperAdmin();
        abort_unless($agent->is_agent, 404);

        return view('admin.agents.edit', compact('agent'));
    }

    /**
     * Update agent account.
     */
    public function update(Request $request, User $agent): RedirectResponse
    {
        $this->authorizeSuperAdmin();
        abort_unless($agent->is_agent, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$agent->id}"],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'agent_notes' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $agent->name = $validated['name'];
        $agent->email = $validated['email'];
        $agent->commission_rate = $validated['commission_rate'];
        $agent->agent_notes = $validated['agent_notes'] ?? null;

        if (! empty($validated['password'])) {
            $agent->password = Hash::make($validated['password']);
        }

        $agent->save();

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', "Agent '{$agent->name}' updated successfully.");
    }

    /**
     * Log a manual sale for an agent (when a merchant is onboarded via agent referral).
     */
    public function recordSale(Request $request, User $agent): RedirectResponse
    {
        $this->authorizeSuperAdmin();
        abort_unless($agent->is_agent, 404);

        $validated = $request->validate([
            'merchant_id' => ['required', 'exists:users,id'],
            'business_id' => ['nullable', 'exists:businesses,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $price = $validated['billing_cycle'] === 'yearly' && $plan->yearly_price
            ? $plan->yearly_price
            : $plan->price;

        $commissionAmount = round($price * ($agent->commission_rate / 100), 2);

        AgentSale::create([
            'agent_id' => $agent->id,
            'merchant_id' => $validated['merchant_id'],
            'business_id' => $validated['business_id'] ?? null,
            'plan_id' => $plan->id,
            'status' => 'active',
            'plan_price' => $price,
            'commission_rate' => $agent->commission_rate,
            'commission_amount' => $commissionAmount,
            'commission_status' => 'pending',
            'billing_cycle' => $validated['billing_cycle'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Link the merchant to this agent
        User::where('id', $validated['merchant_id'])->update(['agent_id' => $agent->id]);

        return back()->with('success', 'Sale recorded! Commission of ₹'.number_format($commissionAmount)." added for {$agent->name}.");
    }

    /**
     * Mark commission as paid for a sale.
     */
    public function markCommissionPaid(Request $request, User $agent, AgentSale $sale): RedirectResponse
    {
        $this->authorizeSuperAdmin();
        abort_unless($sale->agent_id === $agent->id, 403);

        $sale->update([
            'commission_status' => 'paid',
            'commission_paid_at' => now(),
        ]);

        return back()->with('success', "Commission of {$sale->formatted_commission} marked as paid.");
    }

    /**
     * Delete an agent account.
     */
    public function destroy(User $agent): RedirectResponse
    {
        $this->authorizeSuperAdmin();
        abort_unless($agent->is_agent, 404);

        if ($agent->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $agent->name;
        $agent->update(['is_agent' => false, 'agent_code' => null]);

        return redirect()->route('admin.agents.index')
            ->with('success', "Agent account for '{$name}' has been deactivated.");
    }

    protected function authorizeSuperAdmin(): void
    {
        if (! Auth::user()?->isSuperAdmin()) {
            abort(403, 'Super Admin access required.');
        }
    }
}
