<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attendance;
use App\Models\Claass;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            "username" => "admin",
            "password" => Hash::make("password"),
            "email" => "admin@gmail.com",
            "level" => 0,
        ]);
        User::factory()->count(5)->create();
        $this->call(MajorSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(SemesterSeeder::class);
        Claass::factory()->count(10)->create();
        Student::factory()->count(50)->create();
        $this->call(AttendanceStatusSeeder::class);
        Course::factory()->count(50)->create();
        Attendance::factory()->count(8)->create();
        CourseStudent::factory()->count(20)->create();
        StudentAttendance::factory()->count(30)->create();
    }
}
