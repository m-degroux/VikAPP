<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        return [
            'club_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'user_id' => Member::factory(),
            'club_name' => $this->faker->company(),
            'club_address' => $this->faker->city(),  // City only, not full address
            'club_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'club_active' => false,
        ]);
    }
}
