<?php

namespace Database\Seeders;

use App\Models\AttendanceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ["Hadir", "Izin", "Sakit", "Alpa"];
        foreach ($statuses as $status) {
            AttendanceStatus::create([
                "name" => $status,
            ]);
        }
    }
}
