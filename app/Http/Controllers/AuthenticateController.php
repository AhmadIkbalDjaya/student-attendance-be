<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            "username" => "required|string",
            "password" => "required|min:8",
        ]);

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            $data = [
                "succes" => true,
                "data" => [
                    "token" => $user->createToken('token')->plainTextToken,
                    "user" => [],
                ],
                "message" => "Login Berhasil",
            ];
            if ($user->level == 0) {
                $data['data']['user'] = [
                    "username" => $user->username,
                ];
            } else {
                $teacher = $user->teacher;
                $data['data']['user'] = [
                    "username" => $user->username,
                    "name" => $teacher->name,
                    "phone" => $teacher->phone ? $teacher->phone : "",
                    "gender" => $teacher->gender ? $teacher->gender : "",
                ];
            }
            return response()->json($data, 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Username / Password Salah",
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                "message" => "Logout Berhasil",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Logout gagal",
                "errorMessage" => $th->getMessage(),
            ], 500);
        }
    }

    public function teacherCourses()
    {
        $majors = Major::with('claasses.courses')->get();

        $formattedData = [];

        foreach ($majors as $major) {
            $majorData = [];

            foreach ($major->claasses->sortBy('level') as $class) {
                $classData = [];

                foreach ($class->courses as $course) {
                    if ($course->teacher_id == Auth::user()->teacher->id) {
                        $classData[$class->name][] = [
                            'id' => $course->id,
                            'course' => $course->name,
                        ];
                    }
                }
                if ($classData != []) {
                    $majorData[$class->level][] = $classData;
                }
            }

            $formattedData[$major->name] = $majorData;
        }

        return response()->json($formattedData, 200);
    }
}
