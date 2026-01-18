<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Raid;

/**
 * Controller handled to manage the landing page of the application.
 */
class WelcomeController extends Controller
{
    /**
     * Display the welcome page with a selection of the next upcoming raids.
     */
    public function index()
    {
        // Retrieve the top 3 upcoming raids with specific criteria
        $nextRaids = Raid::with('races.ageCategories') // Eager load races and their price categories
            // Filter raids that start from today onwards
            ->where('raid_start_date', '>=', now())
            // Only include raids that actually have associated races
            ->whereHas('races')
            // Order by date to show the most imminent raids first
            ->orderBy('raid_start_date', 'asc')
            // Limit the result set for the home page display
            ->limit(3)
            ->get();

        // Return the welcome view with the filtered raids data
        return view('welcome', compact('nextRaids'));
    }
}