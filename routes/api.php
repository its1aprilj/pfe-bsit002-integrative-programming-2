<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EmployeeController;


// =========================
// STUDENT API
// =========================

// GET ALL STUDENTS
Route::get('/students', function () {
    $students = DB::table('student')->get();

    return response()->json($students);
});


// ADD STUDENT
Route::post('/students', function (Request $request) {

    $validated = $request->validate([
        'student_id' => 'required',
        'student_name' => 'required',
    ]);

    DB::table('student')->insert([
        'student_id' => $validated['student_id'],
        'student_name' => $validated['student_name'],
    ]);

    return response()->json([
        'message' => 'Student saved successfully!',
        'data' => $validated
    ], 201);
});


// UPDATE STUDENT
Route::put('/students/{student_id}', function (Request $request, $student_id) {

    $validated = $request->validate([
        'student_name' => 'required',
    ]);

    $updated = DB::table('student')
        ->where('student_id', $student_id)
        ->update([
            'student_name' => $validated['student_name'],
        ]);

    if (!$updated) {
        return response()->json([
            'message' => 'Student not found or no changes made.'
        ], 404);
    }

    return response()->json([
        'message' => 'Student updated successfully!',
        'data' => [
            'student_id' => $student_id,
            'student_name' => $validated['student_name']
        ]
    ], 200);
});


// DELETE STUDENT
Route::delete('/students/{student_id}', function ($student_id) {

    $deleted = DB::table('student')
        ->where('student_id', $student_id)
        ->delete();

    if (!$deleted) {
        return response()->json([
            'message' => 'Student not found.'
        ], 404);
    }

    return response()->json([
        'message' => 'Student deleted successfully!'
    ], 200);
});


// =========================
// EMPLOYEE API
// =========================

Route::get('/employees', [EmployeeController::class, 'index']);

Route::post('/employees', [EmployeeController::class, 'store']);

Route::get('/employees/{id}', [EmployeeController::class, 'show']);

Route::put('/employees/{id}', [EmployeeController::class, 'update']);

Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
