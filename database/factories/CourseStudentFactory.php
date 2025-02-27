<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseStudent>
 */
class CourseStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = Course::all()->pluck("id")->toArray();
        $students = Student::all()->pluck("id")->toArray();
        return [
            "course_id" => fake()->randomElement($courses),
            "student_id" => fake()->randomElement($students),
        ];
    }
}
