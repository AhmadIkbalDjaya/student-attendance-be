<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = ["IPA", "IPS", "UMUM", "OLAHRAGA & SENI"];
        foreach ($majors as $major) {
            Major::create([
                "name" => $major,
            ]);
        }
    }
}
