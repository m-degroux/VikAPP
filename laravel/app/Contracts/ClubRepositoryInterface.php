<?php

namespace App\Contracts;

use App\Models\Club;
use Illuminate\Database\Eloquent\Collection;

interface ClubRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Club;

    public function create(array $data): Club;

    public function update(Club $club, array $data): bool;

    public function delete(Club $club): bool;

    public function withRelations(array $relations): self;
}
