<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmailRegistrationController
{
    public function __invoke(Request $request): RedirectResponse
    {
        if (! (bool) Setting::get('registration_enabled', true)) {
            abort(404);
        }

        $data = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in([Role::TEACHER->value, Role::STUDENT->value])],
        ]);

        if (User::where('email', $data['email'])->exists()) {
            return redirect()->route('login')->with('status', __('An account with this email already exists. Please log in.'));
        }

        $role = Role::from($data['role']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(32)),
            'role' => $role,
            'role_confirmed_at' => now(),
            'email_verified_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
