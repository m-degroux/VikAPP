<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRaidRequest;
use App\Services\GeocodingService;
use App\Services\RaidService;
use Illuminate\Http\Request;

/**
 * Controller responsible for managing Raids, including searching, 
 * displaying details, and geocoding-based creation.
 */
class RaidController extends Controller
{
    /** @var RaidService Handling business logic for Raids */
    protected $raidService;

    /** @var GeocodingService Handling address to coordinate transformation */
    protected $geocoder;

    /**
     * Dependency injection via constructor.
     */
    public function __construct(RaidService $raidService, GeocodingService $geocoder)
    {
        $this->raidService = $raidService;
        $this->geocoder = $geocoder;
    }

    /**
     * Display a listing of raids based on provided filters.
     */
    public function index(Request $request)
    {
        $filters = $request->all();

        // If a location name is provided but coordinates are missing, fetch them
        if (!empty($filters['location']) && (empty($filters['lat']) || empty($filters['lon']))) {

            $coords = $this->geocoder->getCoordinates($filters['location']);

            if ($coords) {
                // Map the results from the geocoder to local filter variables
                $filters['lat'] = $coords['lat'];
                $filters['lon'] = $coords['lng'] ?? $coords['lon'];

                // Merge coordinates back into the request for persistent filtering
                $request->merge([
                    'lat' => $filters['lat'],
                    'lon' => $filters['lon']
                ]);
            }
        }

        // Delegate the complex search logic to the RaidService
        $raids = $this->raidService->searchRaids($filters);

        return view('raid.index', compact('raids'));
    }

    /**
     * Display details of a specific raid including its nested relationships.
     */
    public function show(string $id)
    {
        $raid = $this->raidService->getRaidById($id);
        
        // Eager load related races, their teams, and specific age categories
        $raid->load('races.teams', 'races.ageCategories');

        return view('raid.show', compact('raid'));
    }

    /**
     * Store a newly created raid in the database.
     */
    public function store(StoreRaidRequest $request, GeocodingService $geocoder, RaidService $raidService)
    {
        // Get data validated via the dedicated StoreRaidRequest class
        $validated = $request->validated();

        // Attempt to geocode the raid's location to store precise coordinates
        if (!empty($validated['RAID_PLACE'])) {
            $coords = $geocoder->getCoordinates($validated['RAID_PLACE']);
            if ($coords) {
                $validated['RAID_LAT'] = $coords['lat'];
                $validated['RAID_LNG'] = $coords['lng'];
            }
        }

        try {
            // Get the ID of the member responsible for the raid
            $responsibleId = $request->input('responsible_id');
            
            // Persist the raid and its association with the responsible person
            $raid = $this->raidService->createRaid($validated, $responsibleId);

            return redirect()->route('raid.show', $raid->raid_id)
                ->with('success', 'Le raid et son responsable ont été créés avec succès !');

        } catch (\Exception $e) {
            // Rollback and return with input data and error message in case of failure
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}