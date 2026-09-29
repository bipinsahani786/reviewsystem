<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $agent = null;
        if ($request->filled('ref')) {
            $agent = User::where('agent_code', trim($request->ref))->where('is_agent', true)->first();
            if ($agent) {
                session(['agent_ref' => $agent->agent_code]);
            }
        } elseif (session()->has('agent_ref')) {
            $agent = User::where('agent_code', session('agent_ref'))->where('is_agent', true)->first();
        }

        return view('auth.register', compact('agent'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $refCode = $request->input('ref', session('agent_ref'));
        $agentId = null;
        if ($refCode) {
            $agent = User::where('agent_code', trim($refCode))->where('is_agent', true)->first();
            $agentId = $agent?->id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'agent_id' => $agentId,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
