<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Team $team)
    {
        //
    }

    public function update(Request $request, Team $team)
    {
        //
    }

    public function destroy(Team $team)
    {
        //
    }

    public function join(Request $request, Team $team)
    {
        $this->teamService->joinTeam(Auth::id(), $team->team_id);

        return back()->with('success', 'Vous avez rejoint l\'équipe !');
    }

    public function removeMember(Team $team, Member $user)
    {
        $this->teamService->removeMemberFromTeam($user->user_id, $team->team_id);

        return response()->json(['message' => 'Membre supprimé de l\'équipe.']);
    }
}
