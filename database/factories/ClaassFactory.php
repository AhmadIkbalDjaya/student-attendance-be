<?php

namespace Database\Factories;

use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Claass>
 */
class ClaassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $majors = Major::all()->pluck("id")->toArray();
        return [
            "major_id" => fake()->randomElement($majors),
            "level" => fake()->randomElement(["10", "11", "12"]),
            "name" => fake()->name(),
        ];
    }
}
