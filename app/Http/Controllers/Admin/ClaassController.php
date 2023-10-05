<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClaassResource;
use App\Models\Claass;
use Illuminate\Http\Request;

class ClaassController extends Controller
{
    public function index()
    {
        $claasses = Claass::latest()->get();
        return response()->json([
            "data" => ClaassResource::collection($claasses),
        ], 200);
    }

    public function show(Claass $claass)
    {
        return response()->json([
            "data" => new ClaassResource($claass),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "major_id" => "required|exists:majors,id",
            "level" => "required|in:10,11,12",
            "name" => "required|string",
        ]);
        try {
            $claass = Claass::create($validated);
            return response()->json([
                "data" => new ClaassResource($claass),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Claass $claass)
    {
        $validated = $request->validate([
            "major_id" => "required|exists:majors,id",
            "level" => "required|in:10,11,12",
            "name" => "required|string",
        ]);
        try {
            $claass->update($validated);
            return response()->json([
                "data" => new ClaassResource($claass),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Claass $claass)
    {
        try {
            $claass->delete();
            return response()->json([
                "success" => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }
}
