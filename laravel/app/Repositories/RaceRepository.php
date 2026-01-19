<?php

namespace App\Repositories;

use App\Contracts\RaceRepositoryInterface;
use App\Models\Race;
use Illuminate\Database\Eloquent\Collection;

class RaceRepository implements RaceRepositoryInterface
{
    private array $relations = [];

    public function all(): Collection
    {
        return Race::query()
            ->with($this->relations)
            ->get();
    }

    public function find(string $id): ?Race
    {
        return Race::query()
            ->with($this->relations)
            ->find($id);
    }

    public function create(array $data): Race
    {
        return Race::query()->create($data);
    }

    public function update(Race $race, array $data): bool
    {
        return $race->update($data);
    }

    public function delete(Race $race): bool
    {
        return (bool) $race->delete();
    }

    public function findByRaidId(string $raidId): Collection
    {
        return Race::query()
            ->with($this->relations)
            ->where('raid_id', $raidId)
            ->get();
    }

    public function withRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }
}
