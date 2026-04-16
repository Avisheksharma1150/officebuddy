@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Edit Salary Structure</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.salary-structures.index') }}">Salary Structures</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Salary Structure Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.salary-structures.update', $salaryStructure->id) }}" method="POST" id="salaryForm">
                @csrf
                @method('PUT')
                
                <!-- Hidden fields for grade_level and employee_type -->
                <input type="hidden" name="grade_level" value="{{ $salaryStructure->grade_level }}">
                <input type="hidden" name="employee_type" value="{{ $salaryStructure->employee_type }}">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Structure Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $salaryStructure->name) }}" required>
                            <small class="form-text text-muted">e.g., Junior Developer, Senior Manager, etc.</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="basic_salary">Basic Salary</label>
                            <input type="number" step="0.01" class="form-control @error('basic_salary') is-invalid @enderror" id="basic_salary" name="basic_salary" value="{{ old('basic_salary', $salaryStructure->basic_salary) }}" required>
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('basic_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="house_rent">House Rent</label>
                            <input type="number" step="0.01" class="form-control @error('house_rent') is-invalid @enderror" id="house_rent" name="house_rent" value="{{ old('house_rent', $salaryStructure->house_rent) }}" required>
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('house_rent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transport_allowance">Transport Allowance</label>
                            <input type="number" step="0.01" class="form-control @error('transport_allowance') is-invalid @enderror" id="transport_allowance" name="transport_allowance" value="{{ old('transport_allowance', $salaryStructure->transport_allowance) }}" required>
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('transport_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medical_allowance">Medical Allowance</label>
                            <input type="number" step="0.01" class="form-control @error('medical_allowance') is-invalid @enderror" id="medical_allowance" name="medical_allowance" value="{{ old('medical_allowance', $salaryStructure->medical_allowance) }}" required>
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('medical_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="other_allowance">Other Allowance</label>
                            <input type="number" step="0.01" class="form-control @error('other_allowance') is-invalid @enderror" id="other_allowance" name="other_allowance" value="{{ old('other_allowance', $salaryStructure->other_allowance) }}">
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('other_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tax_deduction">Tax Deduction (%)</label>
                            <input type="number" step="0.01" class="form-control @error('tax_deduction') is-invalid @enderror" id="tax_deduction" name="tax_deduction" value="{{ old('tax_deduction', $salaryStructure->tax_deduction) }}" required>
                            @error('tax_deduction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="provident_fund">Provident Fund (%)</label>
                            <input type="number" step="0.01" class="form-control @error('provident_fund') is-invalid @enderror" id="provident_fund" name="provident_fund" value="{{ old('provident_fund', $salaryStructure->provident_fund) }}" required>
                            @error('provident_fund')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="other_deduction">Other Deduction</label>
                            <input type="number" step="0.01" class="form-control @error('other_deduction') is-invalid @enderror" id="other_deduction" name="other_deduction" value="{{ old('other_deduction', $salaryStructure->other_deduction) }}">
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('other_deduction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="overtime_rate">Overtime Rate (per hour)</label>
                            <input type="number" step="0.01" class="form-control @error('overtime_rate') is-invalid @enderror" id="overtime_rate" name="overtime_rate" value="{{ old('overtime_rate', $salaryStructure->overtime_rate) }}" required>
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('overtime_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="festival_bonus">Festival Bonus</label>
                            <input type="number" step="0.01" class="form-control @error('festival_bonus') is-invalid @enderror" id="festival_bonus" name="festival_bonus" value="{{ old('festival_bonus', $salaryStructure->festival_bonus) }}" required>
                            <small class="form-text text-muted">Amount in Taka</small>
                            @error('festival_bonus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Read-only fields for information -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Grade Level</label>
                            <input type="text" class="form-control" value="{{ $salaryStructure->grade_level_name }}" readonly>
                            <small class="form-text text-muted">Grade level cannot be changed</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Employee Type</label>
                            <input type="text" class="form-control" value="{{ $salaryStructure->employee_type_name }}" readonly>
                            <small class="form-text text-muted">Employee type cannot be changed</small>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Salary Structure
                        </button>
                        <a href="{{ route('admin.salary-structures.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('salaryForm');
    const festivalBonus = document.getElementById('festival_bonus');
    
    // Ensure festival_bonus has a value
    if (festivalBonus && !festivalBonus.value) {
        festivalBonus.value = 0;
    }
    
    form.addEventListener('submit', function(e) {
        // Double-check all required fields
        if (!festivalBonus.value) {
            festivalBonus.value = 0;
        }
    });
});
</script>
@endsection