<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SemesterResources;
use App\Models\Semester;
use Database\Seeders\SemesterSeeder;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderByRaw('start_year DESC')->orderByRaw('odd_even')->get();
        return response()->json([
            "data" => SemesterResources::collection($semesters),
        ], 200);
    }

    public function show(Semester $semester)
    {
        return response()->json([
            "data" => new SemesterResources($semester),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "start_year" => "required|numeric|max_digits:4|min_digits:4",
            "odd_even" => "required|boolean",
        ]);
        $validated["end_year"] = $validated["start_year"] + 1;
        $check = Semester::where("start_year", $validated["start_year"])
            ->where("end_year", $validated["end_year"])
            ->where("odd_even", $validated['odd_even'])
            ->count();
        if ($check > 0) {
            return response()->json([
                "message" => "Tahun Ajaran yang sama sudah ditambahkan sebelumnya",
            ], 400);
        }
        try {
            $semester = Semester::create($validated);
            return response()->json([
                "data" => new SemesterResources($semester),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function setActive(Semester $semester)
    {
        try {
            Semester::query()->update(["is_active" => 0]);
            $semester->update(["is_active" => 1]);
            return response()->json([
                "success" => true,
                "data" => new SemesterResources($semester),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                "success" => false,
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Semester $semester)
    {
        if ($semester->is_active == 1) {
            return response()->json([
                "success" => false,
                "message" => "Tahun Ajaran Yang Aktif Tidak Dapat Dihapus",
            ], 400);
        }
        try {
            $semester->delete();
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
