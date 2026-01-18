<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Club;

/**
 * Controller responsible for handling new user registration.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Fetch all clubs ordered by name to populate the registration dropdown
        $clubs = Club::orderBy('club_name')->get();

        return view('auth.register', compact('clubs'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate incoming member and user data
        $request->validate([
            'mem_name' => ['required', 'string', 'max:50'],
            'mem_firstname' => ['required', 'string', 'max:50'],
            'mem_default_licence' => ['nullable', 'string', 'unique:'.Member::class],
            'mem_birthdate' => ['required', 'date'],
            'mem_adress' => ['required', 'string', 'max:128'],
            'mem_phone' => ['required', 'string', 'max:10'],
            'mem_email' => ['required', 'string', 'email', 'max:128', 'unique:'.Member::class],
            'user_username' => ['required', 'string', 'max:50', 'unique:'.Member::class],
            'user_password' => ['required', 'confirmed', Rules\Password::defaults()],
            'club_id' => ['nullable', 'integer', 'exists:vik_club,club_id']
        ]);

        // Create a new Member record in the database
        $user = Member::create([
            'mem_name' => $request->mem_name,
            'mem_firstname' => $request->mem_firstname,
            'mem_birthdate' => $request->mem_birthdate,
            'mem_adress' => $request->mem_adress,
            'mem_phone' => $request->mem_phone,
            'mem_email' => $request->mem_email,
            'mem_default_licence' => $request->mem_default_licence,
            'user_username' => $request->user_username,
            'user_password'  => Hash::make($request->user_password), // Securely hash the password
            'club_id' => $request->club_id,
        ]);

        // Fire the Registered event to trigger listeners (e.g., email verification)
        event(new Registered($user));

        // Automatically log in the newly registered user
        Auth::login($user);

        // Redirect to the home page
        return Redirect::to("/");
    }
}