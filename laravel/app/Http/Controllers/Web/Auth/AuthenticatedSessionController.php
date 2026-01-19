<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Controller responsible for handling user authentication sessions.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate the user using the logic defined in LoginRequest
        $request->authenticate();

        // Regenerate the session ID to prevent session fixation attacks
        $request->session()->regenerate();

        // Redirect based on user type
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        // For regular members, redirect to home
        return redirect()->route('welcome');
    }

    /**
     * Destroy an authenticated session.
     */
    // Standard Logout (Member)
    public function destroy(Request $request): RedirectResponse
    {
        // Logout using the default 'web' guard
        Auth::guard('web')->logout();

        // Invalidate the current session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Destroy an authenticated admin session.
     */
    // Admin Logout
    public function destroyAdmin(Request $request): RedirectResponse
    {
        // Force logout specifically on the 'admin' guard
        Auth::guard('admin')->logout();

        // Clean up the session data
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect back to the landing page/admin login
        return redirect('/');
    }
}
