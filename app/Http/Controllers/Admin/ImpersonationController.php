<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a merchant user.
     */
    public function impersonate(Request $request, User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        if (! $currentUser || ! $currentUser->isSuperAdmin()) {
            abort(403, 'Only Super Administrators can impersonate other users.');
        }

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot impersonate your own account.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot impersonate another Super Administrator.');
        }

        // Store original superadmin ID in session
        session(['impersonated_by' => $currentUser->id]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', "Impersonation active: You are now logged in as {$user->name} ({$user->email}).");
    }

    /**
     * Leave impersonation and restore superadmin session.
     */
    public function leave(Request $request): RedirectResponse
    {
        if (! session()->has('impersonated_by')) {
            return redirect()->route('admin.dashboard');
        }

        $adminId = session('impersonated_by');
        $admin = User::find($adminId);

        if (! $admin || ! $admin->isSuperAdmin()) {
            session()->forget('impersonated_by');
            Auth::logout();

            return redirect()->route('login')->with('error', 'Session restored. Please log in again.');
        }

        session()->forget('impersonated_by');
        Auth::login($admin);

        return redirect()->route('admin.users.index')
            ->with('success', 'Left impersonation mode. You are back in your Super Admin account.');
    }
}
