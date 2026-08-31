<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;

// PUBLIC AUTHENTICATION ROUTES
// REGISTER
Route::post('/register', [AuthController::class, 'register']);
// LOGIN
Route::post('/login', [AuthController::class, 'login']);

// STUDENT API GET ALL STUDENTS
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



// PROTECTED ROUTES Requires Sanctum Token

Route::middleware('auth:sanctum')->group(function () {

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    // EMPLOYEE CRUD
    Route::get('/employees', [EmployeeController::class, 'index']);

    Route::post('/employees', [EmployeeController::class, 'store']);

    Route::get('/employees/{id}', [EmployeeController::class, 'show']);

    Route::put('/employees/{id}', [EmployeeController::class, 'update']);

    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

});