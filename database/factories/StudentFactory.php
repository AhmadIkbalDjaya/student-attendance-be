<?php

namespace Database\Factories;

use App\Models\Claass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $claasses = Claass::all()->pluck("id")->toArray();
        return [
            "nis" => fake()->numerify('#####'),
            "name" => fake()->name(),
            "gender" => fake()->randomElement(['male', 'female']),
            "claass_id" => fake()->randomElement($claasses),
        ];
    }
}
