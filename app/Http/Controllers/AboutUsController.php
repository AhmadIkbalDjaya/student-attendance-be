<?php

namespace App\Http\Controllers;

use App\Http\Resources\AboutUsResource;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        return response()->json([
            "data" => AboutUsResource::collection(AboutUs::latest()->get()),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "position" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
        ]);
        try {
            $aboutUs = AboutUs::create($validated);
            return response()->json([
                "data" => new AboutUsResource($aboutUs),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data Gagal disimpan",
                "errors" => $th->getMessage(),
            ], 500);
        }
    }

    public function show(AboutUs $aboutUs)
    {
        return response()->json([
            "data" => new AboutUsResource($aboutUs),
        ]);
    }

    public function update(Request $request, AboutUs $aboutUs)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "position" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
        ]);
        try {
            $aboutUs->update($validated);
            return response()->json([
                "data" => new AboutUsResource($aboutUs),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data Gagal disimpan",
                "errors" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(AboutUs $aboutUs)
    {
        try {
            $aboutUs->delete();
            return response()->json([
                "message" => "Data Berhasil dihapus"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data Gagal di hapus",
                "errors" => $th->getMessage(),
            ]);
        }
    }
}
