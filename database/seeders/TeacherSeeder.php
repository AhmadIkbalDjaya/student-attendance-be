<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where("id", "!=", "0")->get();
        foreach ($users as $user) {
            Teacher::create([
                "user_id" => $user->id,
                "name" => fake()->name(),
                "gender" => fake()->randomElement(["male", "female"]),
            ]);
        }
    }
}
