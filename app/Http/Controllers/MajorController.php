<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdNameResource;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::all();
        return response()->json([
            "data" => IdNameResource::collection($majors),
        ], 200);
    }

}
