<?php

namespace App\Repositories;

use App\Contracts\ClubRepositoryInterface;
use App\Models\Club;
use Illuminate\Database\Eloquent\Collection;

class ClubRepository implements ClubRepositoryInterface
{
    private array $relations = [];

    public function all(): Collection
    {
        return Club::query()
            ->with($this->relations)
            ->get();
    }

    public function find(int $id): ?Club
    {
        return Club::query()
            ->with($this->relations)
            ->find($id);
    }

    public function create(array $data): Club
    {
        return Club::query()->create($data);
    }

    public function update(Club $club, array $data): bool
    {
        return $club->update($data);
    }

    public function delete(Club $club): bool
    {
        return (bool) $club->delete();
    }

    public function withRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }
}
