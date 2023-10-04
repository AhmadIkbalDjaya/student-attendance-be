<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendances>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = Course::all()->pluck("id")->toArray();
        return [
            "title" => fake()->words(3, true),
            "datetime" => fake()->dateTime(),
            "course_id" => fake()->randomElement($courses),
            // "is_filled" => fake()->randomElement([0, 1]),
        ];
    }
}
