<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mem_name' => fake()->lastName(),
            'mem_firstname' => fake()->firstName(),
            'mem_birthdate' => fake()->date(),
            'mem_adress' => fake()->address(),
            'mem_phone' => fake()->phoneNumber(),
            'mem_email' => fake()->unique()->safeEmail(),
            'mem_default_licence' => Str::random(10), // Example, adjust as needed
            'user_username' => fake()->unique()->userName(),
            'user_password' => static::$password ??= Hash::make('password'),
            // 'remember_token' is not in Member model
        ];
    }
}
