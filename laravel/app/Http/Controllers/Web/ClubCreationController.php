<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;

class ClubCreationController extends Controller
{
    public function create()
    {
        $clubs = Club::with('manager')->get();

        $licencies = Member::whereNotNull('mem_default_licence')->get();

        return view('dashboard.clubs.create', compact('clubs', 'licencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_name' => 'required|string|max:50',
            'club_adress' => 'required|string|max:50',
            'user_id' => 'required|integer',
        ]);

        try {
            $club = new Club;
            $club->club_id = (Club::max('club_id') ?? 0) + 1;
            $club->club_name = $validated['club_name'];
            $club->club_address = $validated['club_adress'];
            $club->user_id = $validated['user_id'];
            $club->save();

            return redirect()->route('manage.clubs.index')->with('success', 'Club successfully registered!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }
}
