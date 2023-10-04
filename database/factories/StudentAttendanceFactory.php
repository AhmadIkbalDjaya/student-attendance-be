<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentAttendance>
 */
class StudentAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attendances = Attendance::all()->pluck("id")->toArray();
        $students = Student::all()->pluck("id")->toArray();
        $statuses = AttendanceStatus::all()->pluck("id")->toArray();
        return [
            "attendance_id" => fake()->randomElement($attendances),
            "student_id" => fake()->randomElement($students),
            "status_id" => fake()->randomElement($statuses),
        ];
    }
}
