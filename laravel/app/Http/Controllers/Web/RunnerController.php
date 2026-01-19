<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\RunnerService;
use Illuminate\Support\Facades\Auth;

/**
 * Controller responsible for managing runner statistics, race history,
 * and upcoming registrations.
 */
class RunnerController extends Controller
{
    protected $runnerService;

    public function __construct(RunnerService $runnerService)
    {
        $this->runnerService = $runnerService;
    }

    /**
     * Display a listing of the runner's dashboard including stats,
     * upcoming races, and historical performance.
     */
    public function index()
    {
        $currentRunnerId = Auth::user()->user_id;

        $globalStats = $this->runnerService->getGlobalStatistics($currentRunnerId);
        $upcomingRaces = $this->runnerService->getUpcomingRaces($currentRunnerId);
        $history = $this->runnerService->getRaceHistory($currentRunnerId);
        $processedHistory = $this->runnerService->processRaceHistory($history);

        return view('dashboard.runner.stats', [
            'nbCourses' => $globalStats['nbCourses'],
            'totalPoints' => $globalStats['totalPoints'],
            'nbPodiums' => $processedHistory['nbPodiums'],
            'history' => $processedHistory['history'],
            'upcomingRaces' => $upcomingRaces,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
