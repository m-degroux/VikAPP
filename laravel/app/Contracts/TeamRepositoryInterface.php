<?php

namespace App\Contracts;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Team;

    public function create(array $data): Team;

    public function update(Team $team, array $data): bool;

    public function delete(Team $team): bool;

    public function findByRaceId(string $raceId): Collection;

    public function withRelations(array $relations): self;
}
