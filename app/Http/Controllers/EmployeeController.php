<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // GET /api/employees
    // Supports pagination and search
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search by first name, last name, or email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Pagination: 10 employees per page
        return response()->json(
            $query->paginate(10)
        );
    }

    // POST /api/employees
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'department' => 'nullable|string|max:100',
            'position' => 'required|string|max:100',
        ]);

        $employee = Employee::create($validated);

        return response()->json([
            'message' => 'Employee created successfully!',
            'data' => $employee
        ], 201);
    }

    // GET /api/employees/{id}
    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json($employee, 200);
    }

    // PUT /api/employees/{id}
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:employees,email,' . $id,
            'department' => 'sometimes|nullable|string|max:100',
            'position' => 'sometimes|string|max:100',
        ]);

        $employee->update($validated);

        return response()->json([
            'message' => 'Employee updated successfully!',
            'data' => $employee
        ], 200);
    }

    // DELETE /api/employees/{id}
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully!'
        ], 200);
    }
}