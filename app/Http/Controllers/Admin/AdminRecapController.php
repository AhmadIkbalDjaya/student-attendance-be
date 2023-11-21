<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminRecapResource;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminRecapController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(["claass_id" => "nullable|exists:claasses,id"]);
        if ($request->claass_id) {
            $courses = Course::where("claass_id", $request->claass_id)->get();
        } else {
            $courses = Course::all();
        }
        return response()->json([
            "data" => AdminRecapResource::collection($courses)
        ], 200);
    }
}
