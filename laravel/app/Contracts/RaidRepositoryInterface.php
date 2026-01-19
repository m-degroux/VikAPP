<?php

namespace App\Contracts;

use App\Models\Raid;
use Illuminate\Database\Eloquent\Collection;

interface RaidRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Raid;

    public function create(array $data): Raid;

    public function update(Raid $raid, array $data): bool;

    public function delete(Raid $raid): bool;

    public function upcoming(): Collection;

    public function withRelations(array $relations): self;
}
