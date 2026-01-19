<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Member;
use App\Models\Race;
use App\Models\Raid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller handled to manage Club-related operations and statistics.
 */
class ClubManagementController extends Controller
{
    /**
     * Display a listing of active clubs with their managers.
     */
    public function index()
    {
        // Fetch only active clubs and eager load the manager relationship
        $clubs = Club::with('manager')->where('club_active', '=', true)->get();

        return view('dashboard.clubs.index', compact('clubs'));
    }

    /**
     * Show the form for editing a specific club and display its statistics.
     */
    public function edit(string $id)
    {
        // Find the active club by ID with its manager relation
        $club = Club::with('manager')
            ->where('club_id', '=', $id)
            ->where('club_active', '=', true)
            ->first();

        // Get manager details from the Member table
        $manager = Member::where('user_id', '=', $club->user_id)
            ->first();

        // Fetch all members of the club
        $members = Member::where('club_id', '=', $id)->get();

        // Count total raids organized by this club
        $raids = Raid::where('club_id', $club->club_id)->count();

        // Retrieve IDs of all raids belonging to this club
        $raidIds = Raid::where('club_id', $club->club_id)->pluck('raid_id');

        // Count total races associated with those raids
        $races = Race::whereIn('raid_id', $raidIds)->count();

        return view('dashboard.clubs.edit', [
            'club' => $club,
            'manager' => $manager,
            'members' => $members,
            'nbRaids' => $raids,
            'nbRaces' => $races,
        ]);
    }

    /**
     * Update the specified club's information in the database.
     */
    public function update(Request $request, string $id)
    {
        // Validate basic club information
        $validated = $request->validate([
            'club_name' => ['required', 'string', 'max:255'],
            'club_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            // Update the club record using the query builder
            DB::table('vik_club')
                ->where('club_id', $id)
                ->update($validated);

            return redirect()
                ->back()
                ->withSuccess('Club updated.');
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Club update error', [
                'club_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', "Une erreur s'est produite lors de la mise à jour du club.");
        }
    }

    /**
     * Soft delete a club by setting its active status to false.
     */
    public function destroy(string $id)
    {
        // We perform a logical deletion rather than a physical one
        DB::table('vik_club')
            ->where('club_id', $id)
            ->update([
                'club_active' => false,
            ]);

        return redirect()->route('manage.clubs.index');
    }
}
