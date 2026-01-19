<?php

namespace App\Repositories;

use App\Contracts\RaidRepositoryInterface;
use App\Models\Raid;
use Illuminate\Database\Eloquent\Collection;

class RaidRepository implements RaidRepositoryInterface
{
    private array $relations = [];

    public function all(): Collection
    {
        return Raid::query()
            ->with($this->relations)
            ->get();
    }

    public function find(string $id): ?Raid
    {
        return Raid::query()
            ->with($this->relations)
            ->find($id);
    }

    public function create(array $data): Raid
    {
        return Raid::query()->create($data);
    }

    public function update(Raid $raid, array $data): bool
    {
        return $raid->update($data);
    }

    public function delete(Raid $raid): bool
    {
        return (bool) $raid->delete();
    }

    public function upcoming(): Collection
    {
        return Raid::query()
            ->with($this->relations)
            ->where('raid_end_date', '>=', now())
            ->orderBy('raid_start_date')
            ->get();
    }

    public function withRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }
}
