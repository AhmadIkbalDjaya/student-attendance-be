<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseDetailResource;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->get();
        return response()->json([
            "data" => CourseResource::collection($courses),
        ], 200);
    }

    public function show(Course $course)
    {
        return response()->json([
            "data" => new CourseDetailResource($course),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "claass_id" => "required|exists:claasses,id",
            "teacher_id" => "required|exists:teachers,id",
            "semester_id" => "required|exists:semesters,id",
        ]);
        try {
            $course = Course::create($validated);
            return response()->json([
                "data" => new CourseDetailResource($course),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "claass_id" => "required|exists:claasses,id",
            "teacher_id" => "required|exists:teachers,id",
            "semester_id" => "required|exists:semesters,id",
        ]);
        try {
            $course->update($validated);
            return response()->json([
                "data" => new CourseDetailResource($course),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Course $course)
    {
        try {
            $course->delete();
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
