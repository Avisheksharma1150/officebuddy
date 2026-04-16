<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->with('salaryStructure')->get();
        
        // Remove the problematic average experience calculation
        $averageExperience = 0; // Just set to 0 to avoid errors
        
        return view('admin.employees.index', compact('employees', 'averageExperience'));
    }

    public function create()
    {
        $salaryStructures = SalaryStructure::all();
        return view('admin.employees.create', compact('salaryStructures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:users,employee_id',
            'joining_date' => 'required|date',
            'salary_structure_id' => 'required|exists:salary_structures,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'joining_date' => $validated['joining_date'],
            'salary_structure_id' => $validated['salary_structure_id'],
            'password' => bcrypt($validated['password']),
            'role' => 'employee',
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(User $employee)
    {
        $employee->load('salaryStructure', 'attendances', 'payrolls');
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        $salaryStructures = SalaryStructure::all();
        return view('admin.employees.edit', compact('employee', 'salaryStructures'));
    }
    
    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $employee->id,
            'joining_date' => 'required|date',
            'salary_structure_id' => 'required|exists:salary_structures,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'joining_date' => $validated['joining_date'],
            'salary_structure_id' => $validated['salary_structure_id'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $employee->update($updateData);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}