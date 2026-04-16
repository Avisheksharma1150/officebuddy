<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Attendance;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $payrolls = Payroll::with('user')
            ->whereYear('month_year', substr($month, 0, 4))
            ->whereMonth('month_year', substr($month, 5, 2))
            ->get();
            
        return view('admin.payrolls.index', compact('payrolls', 'month'));
    }

    public function create()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.payrolls.create', compact('employees'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::with('salaryStructure')->find($validated['user_id']);
        
        if (!$user->salaryStructure) {
            return redirect()->back()
                ->with('error', 'Employee does not have a salary structure assigned.');
        }

        // Check if payroll already exists for this user and month
        $existingPayroll = Payroll::where('user_id', $validated['user_id'])
            ->whereYear('month_year', substr($validated['month_year'], 0, 4))
            ->whereMonth('month_year', substr($validated['month_year'], 5, 2))
            ->first();

        if ($existingPayroll) {
            return redirect()->back()
                ->with('error', 'Payroll already generated for this employee for the selected month.');
        }

        // Get attendance data for the month
        $attendances = Attendance::where('user_id', $validated['user_id'])
            ->whereYear('date', substr($validated['month_year'], 0, 4))
            ->whereMonth('date', substr($validated['month_year'], 5, 2))
            ->get();

        $workingDays = $attendances->count();
        $lateMinutes = $attendances->sum('late_minutes');
        $earlyLeaveMinutes = $attendances->sum('early_leave_minutes');
        $overtimeMinutes = $attendances->sum('overtime_minutes');

        $salaryStructure = $user->salaryStructure;
        
        // Calculate basic salary based on working days (assuming 22 working days in a month)
        $basicSalary = ($salaryStructure->basic_salary / 22) * $workingDays;
        
        // Calculate allowances - ensure house_rent is not null
        $houseRent = $salaryStructure->house_rent ?? 0; // Add null coalescing
        $transportAllowance = $salaryStructure->transport_allowance;
        $medicalAllowance = $salaryStructure->medical_allowance;
        $otherAllowance = $salaryStructure->other_allowance;

        // Calculate overtime earnings
        $overtimeEarnings = ($salaryStructure->overtime_rate / 60) * $overtimeMinutes;
        

        // Calculate bonus - ensure festival_bonus is not null
        $festivalBonus = $salaryStructure->festival_bonus ?? 0; // Add null coalescing
        
        // Calculate total earnings
        $totalEarnings = $basicSalary + $houseRent + $transportAllowance + 
                         $medicalAllowance + $otherAllowance + $overtimeEarnings + $festivalBonus;
        
        // Calculate deductions
        $taxDeduction = ($totalEarnings * $salaryStructure->tax_deduction) / 100;
        $providentFund = ($basicSalary * $salaryStructure->provident_fund) / 100;
        $otherDeduction = $salaryStructure->other_deduction;
        
        // Calculate late and early leave deductions
        $lateDeduction = ($basicSalary / (22 * 8 * 60)) * $lateMinutes; // Assuming 8 working hours per day
        $earlyLeaveDeduction = ($basicSalary / (22 * 8 * 60)) * $earlyLeaveMinutes;
        
        $totalDeductions = $taxDeduction + $providentFund + $otherDeduction + $lateDeduction + $earlyLeaveDeduction;
        
        // Calculate net salary
        $netSalary = $totalEarnings - $totalDeductions;

        // Create payroll record with correct column names
        $payroll = new Payroll();
        $payroll->user_id = $user->id;
        $payroll->salary_structure_id = $salaryStructure->id;
        $payroll->month_year = $validated['month_year'] . '-01';
        $payroll->basic_salary = $basicSalary;
        $payroll->house_rent = $houseRent; // Changed from house_allowance
        $payroll->transport_allowance = $transportAllowance;
        $payroll->medical_allowance = $medicalAllowance;
        $payroll->other_allowance = $otherAllowance;
        $payroll->tax_deduction = $taxDeduction;
        $payroll->provident_fund = $providentFund;
        $payroll->other_deduction = $otherDeduction;
        $payroll->overtime_earnings = $overtimeEarnings;
        $payroll->festival_bonus = $festivalBonus; // Changed from bonus
        $payroll->late_deduction = $lateDeduction;
        $payroll->early_leave_deduction = $earlyLeaveDeduction;
        $payroll->total_earnings = $totalEarnings;
        $payroll->total_deductions = $totalDeductions;
        $payroll->net_salary = $netSalary;
        $payroll->status = 'processed';
        $payroll->save();

        return redirect()->route('admin.payrolls.index')
            ->with('success', 'Payroll generated successfully.');
    }

    public function disburse(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'disbursed',
            'disbursement_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Salary disbursed successfully.');
    }

    public function downloadPayslip(Payroll $payroll)
    {
        $pdf = PDF::loadView('admin.payrolls.payslip', compact('payroll'));
        return $pdf->download('payslip-' . $payroll->user->employee_id . '-' . $payroll->month_year->format('F-Y') . '.pdf');
    }
}