<?php

namespace App\Http\Controllers\Admin;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

trait AuthorizesBusinessAccess
{
    /**
     * Resolve a business that the logged-in user is authorized to manage.
     */
    protected function getAuthorizedBusiness(int|string|Business $business): Business
    {
        if ($business instanceof Business) {
            $user = Auth::user();
            if ($user->isSuperAdmin() || $business->owner_user_id === $user->id) {
                return $business;
            }
            abort(403, 'Unauthorized access to this business.');
        }

        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return Business::findOrFail($business);
        }

        return $user->businesses()->findOrFail($business);
    }

    /**
     * Get base query for businesses accessible by the user.
     */
    protected function getAuthorizedBusinessesQuery(): Builder|HasMany
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return Business::query();
        }

        return $user->businesses();
    }
}
