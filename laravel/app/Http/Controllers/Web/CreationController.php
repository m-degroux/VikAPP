<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Club;
use App\Models\Difficulty;
use App\Models\Member;
use App\Models\Race;
use App\Models\Raid;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;


// Correction des imports : utilisez le namespace de vos modèles

// Import correct pour les logs

class CreationController extends Controller
{
    /**
     * Affiche le formulaire de création de course avec les données nécessaires.
     */
    public function indexRace(Request $request)
    {
        $selectedRaidId = $request->query('raid_id');

        // Récupération des données pour les menus déroulants
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

        // --- AJOUTER CETTE LIGNE ---
        $ageCategories = AgeCategory::all();

        // Membres éligibles pour être responsables
        $licencies = Member::whereNotNull('mem_default_licence')->get();

        // --- AJOUTER 'ageCategories' DANS LE COMPACT ---
        return view('pages.addrace', compact('types', 'difficulties', 'raid', 'licencies', 'ageCategories', 'selectedRaidId'));
    }

    public function indexRaid()
    {
        $members = Member::orderBy('mem_name', 'asc')->get();

        return view('raid.create', compact('members'));
    }

    /**
     * Enregistre une nouvelle course dans la table vik_race.
     */
    public function createRace(Request $request)
    {
        // 1. Validation incluant selected_ages pour filtrer les catégories
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
            $race = new Race();
            $new_race_id = (Race::max('race_id') ?? 0) + 1;
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

            // 6. Enregistrement des prix uniquement pour les catégories sélectionnées
            foreach ($validated['selected_ages'] as $age_id) {
                $price = $validated['prices'][$age_id] ?? 0;

                DB::table('vik_race_age_cat')->insert([
                    'age_id' => $age_id,
                    'race_id' => $new_race_id,
                    'bel_price' => $price,
                ]);
            }

            DB::table('vik_race_manager')->insert([
                'user_id' => $validated['user_id'],
                'race_id' => $new_race_id
            ]);

            return redirect()->intended(route('manage.raid.index'));

        } catch (Exception $e) {
            Log::error("Erreur insertion course : " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erreur SQL : ' . $e->getMessage());
        }
    }

    public function indexClub()
    {
        $clubs = Club::with('manager')->get();

        $licencies = Member::whereNotNull('mem_default_licence')->get();

        return view('pages.club.create', compact('clubs', 'licencies'));
    }

    public function createClub(Request $request)
    {
        // 1. Validation : les clés ici correspondent aux "name" de vos inputs HTML
        $validated = $request->validate([
            'club_name' => 'required|string|max:50',
            'club_adress' => 'required|string|max:50',
            'user_id' => 'required|integer', // L'ID du responsable sélectionné
        ]);

        try {
            // 2. Création manuelle de l'objet Club
            $club = new Club();

            // Calcul de l'ID car $incrementing = false dans votre modèle
            $club->club_id = (Club::max('club_id') ?? 0) + 1;

            // Affectation des valeurs (doivent correspondre au $fillable du modèle)
            $club->club_name = $validated['club_name'];
            $club->club_address = $validated['club_adress'];
            $club->user_id = $validated['user_id'];

            // 3. Sauvegarde effective en base de données
            $club->save();

            // 4. Redirection vers la page précédente avec un message de succès
            return redirect()->route('manage.club.index')->with('success', 'Le club a bien été enregistré !');

        } catch (Exception $e) {
            // En cas d'erreur, on revient en arrière avec l'erreur pour débugger
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
