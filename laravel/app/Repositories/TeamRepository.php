<?php

namespace App\Repositories;

use App\Contracts\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    private array $relations = [];

    public function all(): Collection
    {
        return Team::query()
            ->with($this->relations)
            ->get();
    }

    public function find(string $id): ?Team
    {
        return Team::query()
            ->with($this->relations)
            ->find($id);
    }

    public function create(array $data): Team
    {
        return Team::query()->create($data);
    }

    public function update(Team $team, array $data): bool
    {
        return $team->update($data);
    }

    public function delete(Team $team): bool
    {
        return (bool) $team->delete();
    }

    public function findByRaceId(string $raceId): Collection
    {
        return Team::query()
            ->with($this->relations)
            ->where('race_id', $raceId)
            ->get();
    }

    public function withRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }
}
