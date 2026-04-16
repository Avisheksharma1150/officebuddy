<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_structure_id', 
        'month_year',
        'basic_salary',
        'house_rent', // Make sure this matches your database column
        'transport_allowance',
        'medical_allowance',
        'other_allowance',
        'tax_deduction',
        'provident_fund',
        'other_deduction',
        'overtime_earnings',
        'festival_bonus', // Make sure this matches your database column
        'late_deduction',
        'early_leave_deduction',
        'total_earnings',
        'total_deductions',
        'net_salary',
        'status',
        'disbursement_date',
    ];

    protected $attributes = [
        'house_rent' => 0,
        'festival_bonus' => 0,
    ];

    protected $casts = [
        'month_year' => 'date',
        'disbursement_date' => 'datetime',
    ];

    // Add this to handle the column name difference
    public function getHouseAllowanceAttribute()
    {
        return $this->house_rent;
    }

    public function getBonusAttribute() 
    {
        return $this->festival_bonus;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }
}