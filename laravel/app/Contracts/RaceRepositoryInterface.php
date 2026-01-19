<?php

namespace App\Contracts;

use App\Models\Race;
use Illuminate\Database\Eloquent\Collection;

interface RaceRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Race;

    public function create(array $data): Race;

    public function update(Race $race, array $data): bool;

    public function delete(Race $race): bool;

    public function findByRaidId(string $raidId): Collection;

    public function withRelations(array $relations): self;
}
