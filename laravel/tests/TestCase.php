<?php

namespace Tests;

use Database\Seeders\AgeCategorySeeder;
use Database\Seeders\DifficultySeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seed reference data for all tests
        $this->seed(TypeSeeder::class);
        $this->seed(DifficultySeeder::class);
        $this->seed(AgeCategorySeeder::class);
    }
}
