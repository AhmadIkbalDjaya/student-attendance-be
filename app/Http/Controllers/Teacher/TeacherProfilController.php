<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherCourse\TeacherCourseResource;
use App\Http\Resources\TeacherDetailResource;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherProfilController extends Controller
{
    public function teacherCourses()
    {
        return response()->json([
            "data" => TeacherCourseResource::collection(Major::all()),
        ], 200);
    }

    public function updateProfil(Request $request)
    {
        $authUser = Auth::user();
        $validated = $request->validate([
            "name" => "required|string",
            "username" => "required|string|unique:users,username,$authUser->id",
            "email" => "required|email|unique:users,email,$authUser->id",
            "phone" => "nullable",
            "gender" => "required|in:male,female",
        ]);
        try {
            $user = User::where('id', auth()->user()->id)->first();
            DB::transaction(function () use ($user, $validated) {
                $user->update([
                    "username" => $validated["username"],
                    "email" => $validated["email"],
                ]);
                $user->teacher->update([
                    "name" => $validated["name"],
                    "phone" => $validated["phone"],
                    "gender" => $validated["gender"],
                ]);
            });
            return response()->json([
                "data" => new TeacherDetailResource($user->teacher),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Profil Gagal Diupdate",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

}
