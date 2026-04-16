@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-eye"></i> Salary Structure Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.salary-structures.index') }}">Salary Structures</a></li>
                    <li class="breadcrumb-item active">{{ $salaryStructure->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $salaryStructure->name }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Basic Information</h6>
                    <table class="table table-bordered">
                        <tr><th>Grade Level:</th><td>{{ $salaryStructure->grade_level_name }}</td></tr>
                        <tr><th>Employee Type:</th><td>{{ $salaryStructure->employee_type_name }}</td></tr>
                        <tr><th>Assigned Employees:</th><td><span class="badge bg-info">{{ $salaryStructure->users_count }} employees</span></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Salary Breakdown</h6>
                    <table class="table table-bordered">
                        <tr><th>Basic Salary:</th><td>৳ {{ number_format($salaryStructure->basic_salary, 2) }}</td></tr>
                        <tr><th>House Rent:</th><td>৳ {{ number_format($salaryStructure->house_rent, 2) }}</td></tr>
                        <tr><th>Medical Allowance:</th><td>৳ {{ number_format($salaryStructure->medical_allowance, 2) }}</td></tr>
                        <tr><th>Transport Allowance:</th><td>৳ {{ number_format($salaryStructure->transport_allowance, 2) }}</td></tr>
                        <tr><th>Other Allowance:</th><td>৳ {{ number_format($salaryStructure->other_allowance, 2) }}</td></tr>
                        <tr class="table-primary"><th><strong>Gross Salary:</strong></th><td><strong>৳ {{ number_format($salaryStructure->gross_salary, 2) }}</strong></td></tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h6>Deductions</h6>
                    <table class="table table-bordered">
                        <tr><th>Provident Fund:</th><td>{{ $salaryStructure->provident_fund }}%</td></tr>
                        <tr><th>Tax Deduction:</th><td>{{ $salaryStructure->tax_deduction }}%</td></tr>
                        <tr><th>Other Deduction:</th><td>৳ {{ number_format($salaryStructure->other_deduction, 2) }}</td></tr>
                        <tr class="table-danger"><th><strong>Total Deductions:</strong></th><td><strong>৳ {{ number_format($salaryStructure->total_deductions, 2) }}</strong></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Other Information</h6>
                    <table class="table table-bordered">
                        <tr><th>Overtime Rate:</th><td>৳ {{ number_format($salaryStructure->overtime_rate, 2) }}/hour</td></tr>
                        <tr><th>Festival Bonus (Annual):</th><td>৳ {{ number_format($salaryStructure->festival_bonus, 2) }}</td></tr>
                        <tr><th>Monthly Bonus:</th><td>৳ {{ number_format($salaryStructure->monthly_bonus, 2) }}</td></tr>
                        <tr class="table-success"><th><strong>Net Salary:</strong></th><td><strong>৳ {{ number_format($salaryStructure->net_salary, 2) }}</strong></td></tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <a href="{{ route('admin.salary-structures.edit', $salaryStructure->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Structure
                    </a>
                    <a href="{{ route('admin.salary-structures.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection