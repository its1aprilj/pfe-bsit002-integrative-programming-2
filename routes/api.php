<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/students', function () {
    $students = DB::table('student')->get();

    return response()->json($students);
});

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
