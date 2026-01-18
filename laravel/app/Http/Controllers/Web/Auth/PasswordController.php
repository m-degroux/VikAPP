<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controller handled to manage user password updates.
 */
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validate the request data and store errors in a specific "updatePassword" error bag
        $validated = $request->validateWithBag('updatePassword', [
            // Ensure the current password is provided and matches the authenticated user's password
            'current_password' => ['required', 'current_password'],
            // Validate the new password against default security rules and ensure it is confirmed
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Update the user's password in the database after hashing it
        $request->user()->update([
            'user_password' => Hash::make($validated['password']),
        ]);

        // Redirect back to the previous page with a success status message
        return back()->with('status', 'password-updated');
    }
}