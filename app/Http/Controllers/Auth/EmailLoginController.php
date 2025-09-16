<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailLoginController
{
    public function __invoke(Request $request, int $user): RedirectResponse
    {
        $userModel = User::findOrFail($user);

        Auth::login($userModel, true);

        return redirect()->route('dashboard')->with('status', __('You have been logged in.'));
    }
}
