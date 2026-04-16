<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::withCount('users')->get();
        $totalEmployees = User::where('role', 'employee')->count();
        $averageSalary = SalaryStructure::avg('basic_salary') ?? 0;
        $unassignedStructures = SalaryStructure::whereDoesntHave('users')->count();
        
        $gradeLevels = SalaryStructure::GRADE_LEVELS;
        $employeeTypes = SalaryStructure::EMPLOYEE_TYPES;
        
        return view('admin.salary-structures.index', compact(
            'structures', 
            'totalEmployees', 
            'averageSalary', 
            'unassignedStructures',
            'gradeLevels',
            'employeeTypes'
        ));
    }

    /**
     * Display the specified salary structure.
     */
    public function show(SalaryStructure $salaryStructure)
    {
        $salaryStructure->loadCount('users');
        
        return view('admin.salary-structures.show', compact('salaryStructure'));
    }

    public function create()
    {
        $gradeLevels = SalaryStructure::GRADE_LEVELS;
        $employeeTypes = SalaryStructure::EMPLOYEE_TYPES;
        $baseSalaries = SalaryStructure::BASE_SALARIES;
        $overtimeRates = SalaryStructure::OVERTIME_RATES;
        
        return view('admin.salary-structures.create', compact(
            'gradeLevels', 
            'employeeTypes',
            'baseSalaries',
            'overtimeRates'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'grade_level' => 'required',
            'employee_type' => 'required|in:permanent,temporary,contract,probation',
            'base_salary' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:0',
            'custom_house_rent' => 'nullable|numeric|min:0|max:100',
            'custom_medical' => 'nullable|numeric|min:0|max:100',
            'custom_transport' => 'nullable|numeric|min:0|max:100',
            'custom_other_allowance' => 'nullable|numeric|min:0|max:100',
        ]);

        // Check if structure already exists (for predefined grades only)
        if (!str_starts_with($validated['grade_level'], 'custom_')) {
            $existingStructure = SalaryStructure::where('grade_level', $validated['grade_level'])
                ->where('employee_type', $validated['employee_type'])
                ->first();

            if ($existingStructure) {
                return redirect()->back()
                    ->with('error', 'Salary structure for this grade and employee type already exists.')
                    ->withInput();
            }
        }

        // Calculate structure with custom parameters
        $structureData = $this->calculateCustomStructure($validated);

        SalaryStructure::create($structureData);

        return redirect()->route('admin.salary-structures.index')
            ->with('success', 'Salary structure created successfully.');
    }

    private function calculateCustomStructure($data)
    {
        $baseSalary = $data['base_salary'];
        $employeeType = $data['employee_type'];
        $gradeLevel = $data['grade_level'];
        
        // Get default rules
        $allowanceRules = SalaryStructure::ALLOWANCE_RULES[$employeeType];
        $deductionRules = SalaryStructure::DEDUCTION_RULES[$employeeType];
        
        // Override with custom allowances if provided
        if (!empty($data['custom_house_rent'])) {
            $allowanceRules['house_allowance'] = $data['custom_house_rent'];
        }
        if (!empty($data['custom_medical'])) {
            $allowanceRules['medical_allowance'] = $data['custom_medical'];
        }
        if (!empty($data['custom_transport'])) {
            $allowanceRules['transport_allowance'] = $data['custom_transport'];
        }
        if (!empty($data['custom_other_allowance'])) {
            $allowanceRules['other_allowance'] = $data['custom_other_allowance'];
        }

        // Calculate allowances
        $houseRent = ($baseSalary * $allowanceRules['house_allowance']) / 100;
        $medicalAllowance = ($baseSalary * $allowanceRules['medical_allowance']) / 100;
        $transportAllowance = ($baseSalary * $allowanceRules['transport_allowance']) / 100;
        $otherAllowance = ($baseSalary * $allowanceRules['other_allowance']) / 100;

        // Get festival bonus (use default for predefined grades, 0 for custom)
        $festivalBonus = 0;
        if (!str_starts_with($gradeLevel, 'custom_') && isset(SalaryStructure::BONUS_RULES[$employeeType][$gradeLevel])) {
            $festivalBonus = SalaryStructure::BONUS_RULES[$employeeType][$gradeLevel];
        }

        // Generate name
        $name = $data['name'] ?: (
            str_starts_with($gradeLevel, 'custom_') 
                ? 'Custom Grade - ' . SalaryStructure::EMPLOYEE_TYPES[$employeeType]
                : SalaryStructure::GRADE_LEVELS[$gradeLevel] . ' - ' . SalaryStructure::EMPLOYEE_TYPES[$employeeType]
        );

        return [
            'name' => $name,
            'grade_level' => $gradeLevel,
            'employee_type' => $employeeType,
            'basic_salary' => $baseSalary,
            'house_rent' => $houseRent,
            'medical_allowance' => $medicalAllowance,
            'transport_allowance' => $transportAllowance,
            'other_allowance' => $otherAllowance,
            'provident_fund' => $deductionRules['provident_fund'],
            'tax_deduction' => $deductionRules['tax_deduction'],
            'other_deduction' => $deductionRules['other_deduction'],
            'overtime_rate' => $data['overtime_rate'],
            'festival_bonus' => $festivalBonus,
        ];
    }

    public function edit(SalaryStructure $salaryStructure)
    {
        $gradeLevels = SalaryStructure::GRADE_LEVELS;
        $employeeTypes = SalaryStructure::EMPLOYEE_TYPES;
        $salaryStructure->loadCount('users');
        
        return view('admin.salary-structures.edit', compact('salaryStructure', 'gradeLevels', 'employeeTypes'));
    }

    public function update(Request $request, SalaryStructure $salaryStructure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|in:1,2,3,4,5,6,7',
            'employee_type' => 'required|in:permanent,temporary,contract,probation',
            'basic_salary' => 'required|numeric|min:0',
            'house_rent' => 'required|numeric|min:0',
            'medical_allowance' => 'required|numeric|min:0',
            'transport_allowance' => 'required|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'provident_fund' => 'required|numeric|min:0|max:100',
            'tax_deduction' => 'required|numeric|min:0|max:100',
            'other_deduction' => 'nullable|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:0',
            'festival_bonus' => 'required|numeric|min:0',
        ]);
    
        // Ensure festival_bonus has a default value if empty
        if (empty($validated['festival_bonus'])) {
            $validated['festival_bonus'] = 0.00;
        }
    
        $salaryStructure->update($validated);
    
        return redirect()->route('admin.salary-structures.index')
            ->with('success', 'Salary structure updated successfully.');
    }

    public function destroy(SalaryStructure $salaryStructure)
    {
        if ($salaryStructure->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete salary structure assigned to employees.');
        }

        $salaryStructure->delete();

        return redirect()->route('admin.salary-structures.index')
            ->with('success', 'Salary structure deleted successfully.');
    }

    /**
     * Generate all salary structures automatically
     */
    public function generateAllStructures()
    {
        $created = 0;
        $gradeLevels = array_keys(SalaryStructure::GRADE_LEVELS);
        $employeeTypes = array_keys(SalaryStructure::EMPLOYEE_TYPES);

        foreach ($gradeLevels as $grade) {
            foreach ($employeeTypes as $type) {
                $existing = SalaryStructure::where('grade_level', $grade)
                    ->where('employee_type', $type)
                    ->first();

                if (!$existing) {
                    $structureData = SalaryStructure::calculateStructure($grade, $type);
                    SalaryStructure::create($structureData);
                    $created++;
                }
            }
        }

        $message = $created > 0 
            ? "Successfully generated {$created} new salary structures."
            : "All salary structures already exist.";

        return redirect()->route('admin.salary-structures.index')
            ->with('success', $message);
    }

    /**
     * Calculate salary preview
     */
    public function calculatePreview(Request $request)
    {
        try {
            $request->validate([
                'grade_level' => 'required',
                'employee_type' => 'required|in:permanent,temporary,contract,probation',
                'base_salary' => 'required|numeric|min:0',
                'overtime_rate' => 'required|numeric|min:0',
            ]);

            // Simulate the structure calculation for preview
            $tempData = [
                'grade_level' => $request->grade_level,
                'employee_type' => $request->employee_type,
                'base_salary' => $request->base_salary,
                'overtime_rate' => $request->overtime_rate,
                'custom_house_rent' => $request->custom_allowances['house_rent'] ?? null,
                'custom_medical' => $request->custom_allowances['medical'] ?? null,
                'custom_transport' => $request->custom_allowances['transport'] ?? null,
                'custom_other_allowance' => $request->custom_allowances['other_allowance'] ?? null,
            ];

            $structureData = $this->calculateCustomStructure($tempData);

            // Create temporary structure for calculations
            $tempStructure = new SalaryStructure($structureData);

            return response()->json([
                'success' => true,
                'data' => $structureData,
                'calculations' => [
                    'gross_salary' => $tempStructure->gross_salary ?? 0,
                    'total_deductions' => $tempStructure->total_deductions ?? 0,
                    'net_salary' => $tempStructure->net_salary ?? 0,
                    'monthly_bonus' => $tempStructure->monthly_bonus ?? 0,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Calculate preview error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}