<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherDetailResource;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::latest()->get();
        return response()->json([
            "data" => TeacherResource::collection($teachers),
        ], 200);
    }

    public function show(Teacher $teacher)
    {
        return response()->json([
            "data" => new TeacherDetailResource($teacher),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "username" => "required|string|unique:users,username",
            "password" => "required|min:8|string",
            "email" => "required|email|unique:users,email",
            "name" => "required|string",
            "phone" => "nullable",
            "gender" => "required|in:male,female",
        ]);
        try {
            $teacher = null;
            DB::transaction(function () use ($validated, &$teacher) {
                $user = User::create([
                    "username" => $validated["username"],
                    "password" => Hash::make($validated["password"]),
                    "email" => $validated["email"],
                ]);
                $teacher = Teacher::create([
                    "user_id" => $user->id,
                    "name" => $validated["name"],
                    "phone" => $validated["phone"],
                    "gender" => $validated["gender"],
                ]);
            });
            return response()->json([
                "data" => new TeacherDetailResource($teacher),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Teacher $teacher)
    {
        $user = $teacher->user;
        $validated = $request->validate([
            "username" => "required|string|unique:users,username,$user->id",
            "password" => "nullable|min:8|string",
            "email" => "required|email|unique:users,email,$user->id",
            "name" => "required|string",
            "phone" => "nullable",
            "gender" => "required|in:male,female",
        ]);
        if ($request->password) {
            $validated["password"] = Hash::make($validated["password"]);
        } else {
            unset($validated["password"]);
        }
        try {
            DB::transaction(function () use (&$teacher, $validated) {
                $teacher->user->update($validated);
                $teacher->update($validated);
            });
            return response()->json([
                "data" => new TeacherDetailResource($teacher),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            DB::transaction(function () use (&$teacher) {
                $teacher->delete();
                $teacher->user->delete();
            });
            return response()->json([
                "success" => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage(),
            ], 500);
        }
    }
}
