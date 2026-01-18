<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Raid;
use App\Models\ManageRaid;
use App\Models\Club;
use App\Models\Member;

/**
 * Controller responsible for managing raids assigned to the authenticated user.
 */
class RaidManagmentController extends Controller
{
    /**
     * Display a listing of raids that the current user is authorized to manage.
     */
    public function index()
    {
        // Retrieve raids by joining the main raid table with the management pivot table
        $raids = DB::table('vik_raid')
            ->join('vik_manage_raid', 'vik_raid.raid_id', '=', 'vik_manage_raid.raid_id')
            // Filter results to only include raids managed by the currently logged-in user
            ->where('vik_manage_raid.user_id', '=', Auth::user()->user_id)
            // Select all columns from the vik_raid table
            ->select('vik_raid.*') 
            // Order the list by start date in ascending order for better readability
            ->orderBy('raid_start_date', 'asc') 
            ->get();

        // Return the management view populated with the retrieved raids
        return view('pages.raidManagement', compact('raids'));
    }

    /**
     * Show the form for editing the specified raid.
     */
    public function edit(string $id)
    {
        $raid = Raid::findOrFail($id);
        $user = Auth::user();

        // Authorization: admin, explicit manager (vik_manage_raid) or club manager of the raid's club
        $isAdmin = Gate::allows('admin', $user);
        $isAssignedManager = ManageRaid::where('raid_id', $id)->where('user_id', $user->user_id)->exists();
        $isClubManagerOfRaid = $user->isClubManager() && optional($user->managedClub)->club_id == $raid->club_id;

        if (!($isAdmin || $isAssignedManager || $isClubManagerOfRaid)) {
            abort(403, "Accès refusé");
        }

        // Members of the club owning the raid (to pick a new responsible)
        $clubMembers = collect();
        if ($raid->club_id) {
            $club = Club::with('members')->where('club_id', $raid->club_id)->first();
            $clubMembers = $club ? $club->members : collect();
        }

        return view('raid.edit', compact('raid', 'clubMembers'));
    }

    /**
     * Update the specified raid.
     */
    public function update(Request $request, string $id)
    {
        $raid = Raid::findOrFail($id);
        $user = Auth::user();

        // Same authorization as edit
        $isAdmin = Gate::allows('admin', $user);
        $isAssignedManager = ManageRaid::where('raid_id', $id)->where('user_id', $user->user_id)->exists();
        $isClubManagerOfRaid = $user->isClubManager() && optional($user->managedClub)->club_id == $raid->club_id;

        if (!($isAdmin || $isAssignedManager || $isClubManagerOfRaid)) {
            abort(403, "Accès refusé");
        }

        // Basic validation (adapter si besoin)
        $validated = $request->validate([
            'raid_name' => 'required|string|max:50',
            'raid_place' => 'nullable|string|max:50',
            'raid_contact' => 'nullable|string|max:50',
            'raid_website' => 'nullable|url|max:100',
            'raid_reg_start_date' => 'nullable|date',
            'raid_reg_end_date' => 'nullable|date|after_or_equal:raid_reg_start_date',
            'raid_start_date' => 'nullable|date|after_or_equal:raid_reg_end_date',
            'raid_end_date' => 'nullable|date|after_or_equal:raid_start_date',
            'responsible_id' => 'nullable|integer|exists:vik_member,user_id',
        ]);

        // Update raid fields (whitelist)
        $raid->fill(array_filter([
            'raid_name' => $validated['raid_name'] ?? null,
            'raid_place' => $validated['raid_place'] ?? $raid->raid_place,
            'raid_contact' => $validated['raid_contact'] ?? $raid->raid_contact,
            'raid_website' => $validated['raid_website'] ?? $raid->raid_website,
            'raid_reg_start_date' => $validated['raid_reg_start_date'] ?? $raid->raid_reg_start_date,
            'raid_reg_end_date' => $validated['raid_reg_end_date'] ?? $raid->raid_reg_end_date,
            'raid_start_date' => $validated['raid_start_date'] ?? $raid->raid_start_date,
            'raid_end_date' => $validated['raid_end_date'] ?? $raid->raid_end_date,
        ]));

        $raid->save();

        // If responsible_id provided, replace pivot entry (simple implementation: remove existing managers and add the new one)
        if (!empty($validated['responsible_id'])) {
            DB::transaction(function() use ($raid, $validated) {
                ManageRaid::where('raid_id', $raid->raid_id)->delete();
                ManageRaid::create([
                    'raid_id' => $raid->raid_id,
                    'user_id' => $validated['responsible_id'],
                ]);
            });
        }

        return redirect()->route('manage.raid.index')->with('success', 'Raid mis à jour avec succès.');
    }
}