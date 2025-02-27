<?php

namespace App\Http\Controllers\Admin;

use App\Models\Claass;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentDetailResource;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $claass_id = $request->input('claass_id');
        $request->validate([
            "claass_id" => "nullable|exists:claasses,id"
        ]);
        if ($claass_id) {
            $students = Student::where('claass_id', $claass_id)->latest()->get();
        } else {
            $students = Student::latest()->get();
        }
        return response()->json([
            "data" => StudentResource::collection($students),
        ], 200);
    }

    public function show(Student $student)
    {
        return response()->json([
            "data" => new StudentDetailResource($student),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "nis" => "required|string|unique:students,nis",
            "name" => "required|string",
            "gender" => "required|in:male,female",
            "claass_id" => "required|exists:claasses,id",
        ]);
        try {
            $student = Student::create($validated);
            return response()->json([
                "data" => new StudentResource($student),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            "nis" => "required|string|unique:students,nis,$student->id",
            "name" => "required|string",
            "gender" => "required|in:male,female",
            "claass_id" => "required|exists:claasses,id",
        ]);
        try {
            $student->update($validated);
            return response()->json([
                "data" => new StudentResource($student),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();
            return response()->json([
                "success" => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
            ], 500);
        }
    }
}
