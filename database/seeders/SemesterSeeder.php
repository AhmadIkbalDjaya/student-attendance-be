<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = ["2020", "2021", "2022", "2023"];
        foreach ($years as $year) {
            $is_active = 0;
            Semester::create([
                "start_year" => $year,
                "end_year" => $year + 1,
                "odd_even" => 1,
                "is_active" => $is_active,
            ]);
            if ($year == "2023") {
                $is_active = 1;
            }
            Semester::create([
                "start_year" => $year,
                "end_year" => $year + 1,
                "odd_even" => 0,
                "is_active" => $is_active,
            ]);
        }
    }
}
