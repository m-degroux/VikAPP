<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function join(Request $request)
    {
        $request->validate([
            'team_id' => 'required|integer'
        ]);

        $this->teamService->joinTeam(Auth::id(), $request->team_id);

        return back()->with('success', 'Vous avez rejoint l\'équipe !');
    }
}
