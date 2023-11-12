<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherCourse\TeacherCourseResource;
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
                "success" => true,
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
                $data['data']['role'] = "admin";
            } else {
                $teacher = $user->teacher;
                $data['data']['role'] = "teacher";
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
        return response()->json([
            "data" => TeacherCourseResource::collection(Major::all()),
        ], 200);
    }
}
