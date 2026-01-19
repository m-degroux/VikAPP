<?php

namespace App\Http\Controllers\Web;

use App\Actions\Race\ImportRaceResults;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRaidRequest;
use App\Services\RaidManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller responsible for managing raids assigned to the authenticated user.
 */
class RaidManagmentController extends Controller
{
    protected $raidManagerService;

    public function __construct(RaidManagerService $raidManagerService)
    {
        $this->raidManagerService = $raidManagerService;
    }

    /**
     * Display a listing of raids that the current user is authorized to manage.
     */
    public function index()
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        $raids = $this->raidManagerService->getManagedRaids($user->user_id);

        return view('admin.raids.manage', compact('raids'));
    }

    /**
     * Show the form for editing the specified raid.
     */
    public function edit(string $id)
    {
        $data = $this->raidManagerService->getRaidForEdit($id);

        return view('dashboard.raids.edit', $data);
    }

    /**
     * Update the specified raid.
     */
    public function update(UpdateRaidRequest $request, string $id)
    {
        $validated = $request->validated();
        $this->raidManagerService->updateRaid($id, $validated);

        return redirect()->route('manage.raids.index')->with('success', 'Raid mis à jour avec succès.');
    }

    /**
     * Import race results from a CSV file for a specific race.
     * Dynamically handles column ordering and data cleaning.
     */
    public function importCsv(Request $request, string $id, ImportRaceResults $importer)
    {
        try {
            $message = $importer->execute($request, $id);

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }
}
