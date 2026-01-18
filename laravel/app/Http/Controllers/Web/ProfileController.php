<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Club;

/**
 * Controller handled to manage user profile actions: viewing, updating, and deleting.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Return the profile view with the authenticated user and the list of all clubs
        return view('pages.profile', [
            'user' => $request->user(),
            'clubs' => Club::all()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Fill the user model with validated data from the dedicated Request class
        $request->user()->fill($request->validated());

        // Persist changes to the database
        $request->user()->save();

        // Refresh the page with a success status message
        return Redirect::refresh()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate the password before deletion to ensure the user is the owner
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log the user out before deleting the record
        Auth::logout();

        // Perform the deletion of the member/user record
        $user->delete();

        // Invalidate the session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page after account destruction
        return Redirect::to('/');
    }
}