<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Difficulty;
use App\Models\Member;
use App\Models\Race;
use App\Models\Raid;
use App\Models\Type;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RaceCreationController extends Controller
{
    /**
     * Affiche le formulaire de création de course avec les données nécessaires.
     */
    public function create(Request $request)
    {
        $selectedRaidId = $request->query('raid_id');

        
        $types = Type::all();
        $difficulties = Difficulty::all();
        $raid = Raid::select([
            'raid_reg_start_date as start_register',
            'raid_reg_end_date as end_register',
            'raid_start_date as start',
            'raid_end_date as end',
        ])
            ->where('raid_id', $selectedRaidId)
            ->first();

        
        $ageCategories = AgeCategory::all();

        
        $licencies = Member::whereNotNull('mem_default_licence')->get();

        
        return view('dashboard.races.create', compact('types', 'difficulties', 'raid', 'licencies', 'ageCategories', 'selectedRaidId'));
    }

    /**
     * Enregistre une nouvelle course dans la table vik_race.
     */
    public function store(Request $request)
    {
        Log::info('RaceCreationController@store - Début');
        Log::info('Auth check web: ' . (auth()->guard('web')->check() ? 'YES' : 'NO'));
        Log::info('Auth check admin: ' . (auth()->guard('admin')->check() ? 'YES' : 'NO'));
        Log::info('User: ' . (auth()->user() ? auth()->user()->user_id : 'NULL'));
        Log::info('Request data: ', $request->all());
        
        $validated = $request->validate([
            'raid_id' => 'required|exists:vik_raid,raid_id',
            'race_name' => 'required|string|max:50',
            'type_id' => 'required|exists:vik_type,type_id',
            'race_length' => 'required|numeric',
            'user_id' => 'required|exists:vik_member,user_id',
            'race_start_date' => 'required|date',
            'race_end_date' => 'required|date|after:race_start_date',
            'race_min_part' => 'required|integer',
            'race_max_part' => 'required|integer',
            'race_min_team' => 'required|integer',
            'race_max_team' => 'required|integer',
            'race_max_part_per_team' => 'required|integer',
            'race_meal_price' => 'nullable|numeric',
            'race_reduction' => 'nullable|numeric|min:0|max:100',
            'selected_ages' => 'required|array|min:1', // Au moins une catégorie cochée
            'prices' => 'required|array',
            'prices.*' => 'nullable|numeric|min:0',
        ]);

        try {
            $race = new Race;
            // Generate UUID for race_id (race_id is string UUID, not integer)
            $new_race_id = \Illuminate\Support\Str::uuid()->toString();
            $race->race_id = $new_race_id;

            // 2. Calcul automatique de la durée à partir des dates
            $start = new \DateTime($validated['race_start_date']);
            $end = new \DateTime($validated['race_end_date']);
            $diff = $start->diff($end);
            $hours = ($diff->days * 24) + $diff->h;
            $duration = sprintf('%02d:%02d:%02d', $hours, $diff->i, $diff->s);

            // 3. Calcul de la difficulté via la base
            $length = $validated['race_length'];
            $difficulty = Difficulty::where('dif_dist_min', '<=', $length)
                ->where('dif_dist_max', '>=', $length)
                ->first();
            $race->dif_id = $difficulty ? $difficulty->dif_id : Difficulty::first()->dif_id;

            // 4. Transformation réduction (0.XX)
            $reductionFloat = ($validated['race_reduction'] ?? 0) / 100;

            // 5. Assignation des champs
            $race->race_name = $validated['race_name'];
            $race->raid_id = $validated['raid_id'];
            $race->type_id = $validated['type_id'];
            $race->race_duration = $duration;
            $race->race_length = $length;
            $race->race_reduction = $reductionFloat;
            $race->race_start_date = $validated['race_start_date'];
            $race->race_end_date = $validated['race_end_date'];
            $race->race_min_part = $validated['race_min_part'];
            $race->race_max_part = $validated['race_max_part'];
            $race->race_min_team = $validated['race_min_team'];
            $race->race_max_team = $validated['race_max_team'];
            $race->race_max_part_per_team = $validated['race_max_part_per_team'];
            $race->race_meal_price = $validated['race_meal_price'];

            $race->save();

            // 6. Enregistrement des prix uniquement pour les catégories sélectionnées (batch insert)
            $racePrices = [];
            foreach ($validated['selected_ages'] as $age_id) {
                $price = $validated['prices'][$age_id] ?? 0;
                $racePrices[] = [
                    'age_id' => $age_id,
                    'race_id' => $new_race_id,
                    'bel_price' => $price,
                ];
            }

            if (! empty($racePrices)) {
                DB::table('vik_race_age_cat')->insert($racePrices);
            }

            DB::table('vik_race_manager')->insert([
                'user_id' => $validated['user_id'],
                'race_id' => $new_race_id,
            ]);

            Log::info('Race created successfully: ' . $new_race_id);
            return redirect()->route('manage.races.index')->with('success', 'Course créée avec succès !');

        } catch (Exception $e) {
            Log::error('Erreur insertion course : '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Erreur SQL : '.$e->getMessage());
        }
    }
}
