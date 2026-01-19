<?php

namespace App\Services;

use App\Models\Race;

class RaceService
{
    public function getAll()
    {
        return Race::with('raid')->get();
    }

    public function create(array $data)
    {
        return Race::create($data);
    }

    public function getById($id)
    {
        return Race::find($id);
    }

    public function update($id, array $data)
    {
        $race = Race::find($id);
        if ($race) {
            $race->update($data);

            return $race;
        }

        return null;
    }

    public function delete($id): bool
    {
        $race = Race::find($id);
        if ($race) {
            return (bool) $race->delete();
        }

        return false;
    }
}
