<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of registered users / merchants.
     */
    public function index(Request $request): View
    {
        $query = User::with(['businesses.plan'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            if ($request->input('role') === 'admin') {
                $query->where('is_super_admin', true);
            } elseif ($request->input('role') === 'merchant') {
                $query->where('is_super_admin', false);
            }
        }

        $users = $query->paginate(15)->withQueryString();

        // High level user metrics for the top cards
        $totalUsers = User::count();
        $totalSuperAdmins = User::where('is_super_admin', true)->count();
        $totalMerchants = User::where('is_super_admin', false)->count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalSuperAdmins',
            'totalMerchants',
            'newUsersThisMonth'
        ));
    }

    /**
     * Toggle superadmin role for a user.
     */
    public function toggleAdmin(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own administrative privileges.');
        }

        $user->update(['is_super_admin' => ! $user->is_super_admin]);

        $status = $user->is_super_admin ? 'promoted to Super Admin' : 'demoted to regular Merchant';

        return back()->with('success', "User {$user->name} has been {$status}.");
    }

    /**
     * Delete user and cascading data.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "User {$name} and associated businesses were deleted successfully.");
    }
}
