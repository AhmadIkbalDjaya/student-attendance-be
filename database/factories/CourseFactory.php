<?php

namespace Database\Factories;

use App\Models\Claass;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $claass = Claass::all()->pluck("id")->toArray();
        $teacher = Teacher::all()->pluck("id")->toArray();
        $semester = Semester::all()->pluck("id")->toArray();
        return [
            "name" => fake()->words(2, true),
            "claass_id" => fake()->randomElement($claass),
            "teacher_id" => fake()->randomElement($teacher),
            "semester_id" => fake()->randomElement($semester),
        ];
    }
}
