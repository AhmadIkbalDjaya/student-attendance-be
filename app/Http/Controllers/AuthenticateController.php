<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email,
                ];
                $data['data']['role'] = "admin";
            } else {
                $teacher = $user->teacher;
                $data['data']['role'] = "teacher";
                $data['data']['user'] = [
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email,
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

    public function changePass(Request $request)
    {
        $validated = $request->validate([
            "old_password" => "required|string",
            "new_password" => "required|min:8|confirmed",
        ]);
        if (!Hash::check($validated["old_password"], auth()->user()->password)) {
            throw ValidationException::withMessages([
                "old_password" => "Password Lama Salah",
            ]);
        }
        try {
            User::find(auth()->user()->id)->update(["password" => Hash::make($validated["new_password"])]);
            return response()->json(["message" => "Password Berhasil diubah"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Password Gagal Diubah", "error" => $th->getMessage()], 500);
        }
    }
}
