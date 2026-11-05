<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\NativeApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SetupController extends Controller
{
    public function create(): View
    {
        abort_unless(NativeApp::needsSetup(), 404);

        return view('auth.setup');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(NativeApp::needsSetup(), 404);

        $user = User::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]));

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->route('invoices.index');
    }
}
