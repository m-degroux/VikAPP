<?php

namespace App\Services;

use App\Models\Club;

class ClubService
{
    public function getAllClubs()
    {
        return Club::all();
    }

    public function createClub(array $data)
    {
        return Club::create($data);
    }

    public function getClubById($id)
    {
        return Club::find($id);
    }

    public function updateClub($id, array $data)
    {
        $club = Club::find($id);
        if ($club) {
            $club->update($data);

            return $club;
        }

        return null;
    }

    public function deleteClub($id): bool
    {
        $club = Club::find($id);
        if ($club) {
            return (bool) $club->delete();
        }

        return false;
    }
}
