<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level', 
        'employee_type',
        'basic_salary',
        'house_rent', // This matches database
        'medical_allowance',
        'transport_allowance', 
        'other_allowance',
        'provident_fund',
        'tax_deduction',
        'other_deduction',
        'overtime_rate',
        'festival_bonus', // This matches database
    ];

    // Grade levels
    const GRADE_LEVELS = [
        '1' => 'Grade 1 (Executive)',
        '2' => 'Grade 2 (Senior Manager)',
        '3' => 'Grade 3 (Manager)',
        '4' => 'Grade 4 (Senior Officer)',
        '5' => 'Grade 5 (Officer)',
        '6' => 'Grade 6 (Junior Officer)',
        '7' => 'Grade 7 (Staff)',
    ];

    const EMPLOYEE_TYPES = [
        'permanent' => 'Permanent',
        'temporary' => 'Temporary',
        'contract' => 'Contract',
        'probation' => 'Probation',
    ];

    // Base salary in Bangladeshi Taka for each grade level
    const BASE_SALARIES = [
        '1' => 80000,
        '2' => 60000,
        '3' => 45000,
        '4' => 35000,
        '5' => 25000,
        '6' => 18000,
        '7' => 12000,
    ];

    // Bangladeshi allowance rules (using house_allowance as house_rent)
    const ALLOWANCE_RULES = [
        'permanent' => [
            'house_allowance' => 50,  // 50% of basic as house rent
            'medical_allowance' => 10,
            'transport_allowance' => 10,
            'other_allowance' => 5,
        ],
        'temporary' => [
            'house_allowance' => 40,
            'medical_allowance' => 8,
            'transport_allowance' => 8,
            'other_allowance' => 3,
        ],
        'contract' => [
            'house_allowance' => 45,
            'medical_allowance' => 9,
            'transport_allowance' => 9,
            'other_allowance' => 4,
        ],
        'probation' => [
            'house_allowance' => 30,
            'medical_allowance' => 5,
            'transport_allowance' => 5,
            'other_allowance' => 2,
        ],
    ];

    // Bangladeshi deduction rules
    const DEDUCTION_RULES = [
        'permanent' => [
            'provident_fund' => 10,
            'tax_deduction' => 5,
            'other_deduction' => 0,
        ],
        'temporary' => [
            'provident_fund' => 0,
            'tax_deduction' => 0,
            'other_deduction' => 2,
        ],
        'contract' => [
            'provident_fund' => 5,
            'tax_deduction' => 5,
            'other_deduction' => 1,
        ],
        'probation' => [
            'provident_fund' => 0,
            'tax_deduction' => 0,
            'other_deduction' => 0,
        ],
    ];

    // Overtime rates in Taka per hour
    const OVERTIME_RATES = [
        '1' => 500,
        '2' => 400,
        '3' => 300,
        '4' => 250,
        '5' => 200,
        '6' => 150,
        '7' => 100,
    ];

    // Festival bonus rules (using bonus column as festival_bonus)
    const BONUS_RULES = [
        'permanent' => [
            '1' => 30000,
            '2' => 25000,
            '3' => 20000,
            '4' => 15000,
            '5' => 10000,
            '6' => 8000,
            '7' => 6000,
        ],
        'temporary' => [
            '1' => 15000,
            '2' => 12000,
            '3' => 10000,
            '4' => 8000,
            '5' => 6000,
            '6' => 4000,
            '7' => 3000,
        ],
        'contract' => [
            '1' => 20000,
            '2' => 15000,
            '3' => 12000,
            '4' => 9000,
            '5' => 7000,
            '6' => 5000,
            '7' => 4000,
        ],
        'probation' => [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
        ],
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Calculate salary structure automatically
     */
    public static function calculateStructure($gradeLevel, $employeeType, $customName = null)
    {
        $baseSalary = self::BASE_SALARIES[$gradeLevel];
        $allowanceRules = self::ALLOWANCE_RULES[$employeeType];
        $deductionRules = self::DEDUCTION_RULES[$employeeType];
        $overtimeRate = self::OVERTIME_RATES[$gradeLevel];
        $festivalBonus = self::BONUS_RULES[$employeeType][$gradeLevel];
    
        // Calculate allowances
        $houseAllowance = ($baseSalary * $allowanceRules['house_allowance']) / 100;
        $medicalAllowance = ($baseSalary * $allowanceRules['medical_allowance']) / 100;
        $transportAllowance = ($baseSalary * $allowanceRules['transport_allowance']) / 100;
        $otherAllowance = ($baseSalary * $allowanceRules['other_allowance']) / 100;
    
        // Generate name if not provided
        $name = $customName ?: self::GRADE_LEVELS[$gradeLevel] . ' - ' . self::EMPLOYEE_TYPES[$employeeType];
    
        return [
            'name' => $name,
            'grade_level' => $gradeLevel,
            'employee_type' => $employeeType,
            'basic_salary' => $baseSalary,
            'house_allowance' => $houseAllowance, // Database field
            'house_rent' => $houseAllowance, // Alias for frontend
            'medical_allowance' => $medicalAllowance,
            'transport_allowance' => $transportAllowance,
            'other_allowance' => $otherAllowance,
            'provident_fund' => $deductionRules['provident_fund'],
            'tax_deduction' => $deductionRules['tax_deduction'],
            'other_deduction' => $deductionRules['other_deduction'],
            'overtime_rate' => $overtimeRate,
            'bonus' => $festivalBonus, // Database field
            'festival_bonus' => $festivalBonus, // Alias for frontend
        ];
    }

    /**
     * Get display name for grade level
     */
    public function getGradeLevelNameAttribute()
    {
        return self::GRADE_LEVELS[$this->grade_level] ?? 'Unknown Grade';
    }

    /**
     * Get display name for employee type
     */
    public function getEmployeeTypeNameAttribute()
    {
        return self::EMPLOYEE_TYPES[$this->employee_type] ?? 'Unknown Type';
    }

    /**
     * Calculate total earnings (Gross Salary)
     */
    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->house_allowance + $this->medical_allowance + 
               $this->transport_allowance + $this->other_allowance;
    }

    /**
     * Calculate total deductions amount
     */
    public function getTotalDeductionsAttribute()
    {
        $basicForPf = $this->basic_salary;
        $pfDeduction = ($basicForPf * $this->provident_fund) / 100;
        $taxDeduction = ($this->gross_salary * $this->tax_deduction) / 100;
        
        return $pfDeduction + $taxDeduction + $this->other_deduction;
    }

    /**
     * Calculate net salary
     */
    public function getNetSalaryAttribute()
    {
        return $this->gross_salary - $this->total_deductions;
    }

    /**
     * Calculate monthly festival bonus (divided by 12 months)
     */
    public function getMonthlyBonusAttribute()
    {
        return $this->bonus > 0 ? ($this->bonus * 2) / 12 : 0;
    }

    /**
     * Alias for house_allowance as house_rent (for display purposes)
     */
    public function getHouseRentAttribute()
    {
        return $this->house_allowance;
    }

    /**
     * Alias for bonus as festival_bonus (for display purposes)
     */
    public function getFestivalBonusAttribute()
    {
        return $this->bonus;
    }
}