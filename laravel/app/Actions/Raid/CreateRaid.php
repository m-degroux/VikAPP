<?php

namespace App\Actions\Raid;

use App\Models\Club;
use App\Models\ManageRaid;
use App\Models\Raid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateRaid
{
    public function execute(array $data, int $responsibleId): Raid
    {
        if ($data['raid_start_date'] > $data['raid_end_date']) {
            throw new \Exception('La date de début ne peut pas être après la date de fin.');
        }
        if ($data['raid_reg_start_date'] > $data['raid_reg_end_date']) {
            throw new \Exception("La date d'ouverture des inscriptions est incohérente.");
        }

        // Automatically assign club_id if not provided
        if (! isset($data['club_id']) || empty($data['club_id'])) {
            $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();
            if ($user) {
                $club = Club::select('club_id')->where('user_id', $user->user_id)->first();
                if ($club) {
                    $data['club_id'] = $club->club_id;
                } else {
                    throw new \Exception('Aucun club associé à votre compte. Veuillez d\'abord créer un club.');
                }
            } else {
                throw new \Exception('Utilisateur non authentifié.');
            }
        }

        try {
            return DB::transaction(function () use ($data, $responsibleId) {
                $raid = Raid::create($data);

                if ($responsibleId) {
                    ManageRaid::create([
                        'raid_id' => $raid->raid_id,
                        'user_id' => $responsibleId,
                    ]);
                }

                return $raid;
            });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du Raid : '.$e->getMessage());
            throw $e;
        }
    }
}
