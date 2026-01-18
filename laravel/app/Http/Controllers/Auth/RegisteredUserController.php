<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mem_name' => ['required', 'string', 'lowercase', 'max:50'],
            'mem_firstname' => ['required', 'string', 'lowercase', 'max:50'],
            'mem_licence' => ['nullable', 'string', 'unique:'.Member::class],
            'mem_birthdate' => ['required', 'date'],
            'mem_adress' => ['required', 'string', 'lowercase', 'max:128'],
            'mem_phone' => ['required', 'string', 'max:10'],
            'mem_email' => ['required', 'string', 'lowercase', 'email', 'max:128', 'unique:'.Member::class],
            'user_username' => ['required', 'string', 'max:50', 'unique:'.Member::class],
            'user_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Member::create([
            'mem_name' => $request->mem_name,
            'mem_firstname' => $request->mem_firstname,
            'mem_birthdate' => $request->mem_birthdate,
            'mem_adress' => $request->mem_adress,
            'mem_phone' => $request->mem_phone,
            'mem_email' => $request->mem_email,
            'mem_default_licence' => $request->mem_licence,
            'user_username' => $request->user_username,
            'user_password'  => Hash::make($request->user_password)
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
