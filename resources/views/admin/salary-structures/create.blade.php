@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-money-bill-wave"></i> Create Salary Structure</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.salary-structures.index') }}">Salary Structures</a></li>
                    <li class="breadcrumb-item active">Create New</li>
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

    <div class="row">
        <!-- Left Column - Grade Management -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-layer-group"></i> Manage Grade Levels</h5>
                </div>
                <div class="card-body">
                    <!-- Existing Grades -->
                    <div class="mb-4">
                        <h6>Existing Grade Levels</h6>
                        <div id="existingGrades" class="list-group">
                            @foreach($gradeLevels as $key => $value)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $value }}</strong>
                                        <br>
                                        <small class="text-muted">Base: ৳ {{ number_format($baseSalaries[$key] ?? 0) }}</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary select-grade" data-grade="{{ $key }}">
                                        Select
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Create New Grade -->
                    <div class="border-top pt-3">
                        <h6>Create Custom Grade</h6>
                        <form id="customGradeForm">
                            @csrf
                            <div class="mb-3">
                                <label for="custom_grade_name" class="form-label">Grade Name</label>
                                <input type="text" class="form-control" id="custom_grade_name" placeholder="e.g., Senior Executive, Junior Staff">
                            </div>
                            <div class="mb-3">
                                <label for="custom_base_salary" class="form-label">Base Salary</label>
                                <input type="number" class="form-control" id="custom_base_salary" placeholder="Enter base salary in Taka">
                            </div>
                            <div class="mb-3">
                                <label for="custom_overtime_rate" class="form-label">Overtime Rate (per hour)</label>
                                <input type="number" class="form-control" id="custom_overtime_rate" placeholder="Overtime rate in Taka">
                            </div>
                            <button type="button" class="btn btn-success w-100" id="createCustomGrade">
                                <i class="fas fa-plus"></i> Create Custom Grade
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Salary Structure Creation -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> Salary Structure Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.salary-structures.store') }}" method="POST" id="salaryStructureForm">
                        @csrf
                        
                        <!-- Selected Grade Info -->
                        <div id="selectedGradeInfo" class="alert alert-info" style="display: none;">
                            <h6><i class="fas fa-check-circle"></i> Selected Grade: <span id="currentGradeName">None</span></h6>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <strong>Base Salary:</strong> <span id="currentBaseSalary">৳ 0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Overtime Rate:</strong> <span id="currentOvertimeRate">৳ 0/hour</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Grade Level:</strong> <span id="currentGradeLevel">-</span>
                                </div>
                            </div>
                            <input type="hidden" name="grade_level" id="selected_grade_level">
                            <input type="hidden" name="base_salary" id="selected_base_salary">
                            <input type="hidden" name="overtime_rate" id="selected_overtime_rate">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_type" class="form-label"><strong>Employee Type</strong></label>
                                    <select class="form-control @error('employee_type') is-invalid @enderror" id="employee_type" name="employee_type" required>
                                        <option value="">Select Employee Type</option>
                                        @foreach($employeeTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label"><strong>Structure Name</strong></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Will be auto-generated if empty">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Custom Allowance Overrides -->
                        <div class="card mt-4">
                            <div class="card-header bg-warning">
                                <h6 class="mb-0"><i class="fas fa-sliders-h"></i> Custom Allowance Overrides (Optional)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="custom_house_rent" class="form-label">House Rent Allowance (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="custom_house_rent" name="custom_house_rent" placeholder="Leave empty for default">
                                        </div>
                                        <div class="mb-3">
                                            <label for="custom_medical" class="form-label">Medical Allowance (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="custom_medical" name="custom_medical" placeholder="Leave empty for default">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="custom_transport" class="form-label">Transport Allowance (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="custom_transport" name="custom_transport" placeholder="Leave empty for default">
                                        </div>
                                        <div class="mb-3">
                                            <label for="custom_other_allowance" class="form-label">Other Allowance (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="custom_other_allowance" name="custom_other_allowance" placeholder="Leave empty for default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Preview -->
                        <div id="previewSection" class="mt-4" style="display: none;">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-eye"></i> Salary Structure Preview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Earnings:</h6>
                                            <table class="table table-sm table-bordered">
                                                <tr><th>Basic Salary:</th><td id="preview_basic">৳ 0.00</td></tr>
                                                <tr><th>House Rent:</th><td id="preview_house_rent">৳ 0.00</td></tr>
                                                <tr><th>Medical Allowance:</th><td id="preview_medical">৳ 0.00</td></tr>
                                                <tr><th>Transport Allowance:</th><td id="preview_transport">৳ 0.00</td></tr>
                                                <tr><th>Other Allowance:</th><td id="preview_other">৳ 0.00</td></tr>
                                                <tr class="table-primary"><th><strong>Gross Salary:</strong></th><td id="preview_gross"><strong>৳ 0.00</strong></td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Deductions:</h6>
                                            <table class="table table-sm table-bordered">
                                                <tr><th>Provident Fund:</th><td id="preview_pf">0%</td></tr>
                                                <tr><th>Tax Deduction:</th><td id="preview_tax">0%</td></tr>
                                                <tr><th>Other Deduction:</th><td id="preview_other_deduction">৳ 0.00</td></tr>
                                                <tr class="table-danger"><th><strong>Total Deductions:</strong></th><td id="preview_total_deductions"><strong>৳ 0.00</strong></td></tr>
                                            </table>
                                            
                                            <h6 class="mt-3">Other Information:</h6>
                                            <table class="table table-sm table-bordered">
                                                <tr><th>Overtime Rate:</th><td id="preview_overtime">৳ 0.00/hour</td></tr>
                                                <tr><th>Festival Bonus (Annual):</th><td id="preview_festival_bonus">৳ 0.00</td></tr>
                                                <tr class="table-success"><th><strong>Net Salary:</strong></th><td id="preview_net"><strong>৳ 0.00</strong></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="fas fa-save"></i> Create Salary Structure
                                </button>
                                <a href="{{ route('admin.salary-structures.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeSelectors = document.querySelectorAll('.select-grade');
    const customGradeForm = document.getElementById('customGradeForm');
    const createCustomGradeBtn = document.getElementById('createCustomGrade');
    const selectedGradeInfo = document.getElementById('selectedGradeInfo');
    const employeeTypeSelect = document.getElementById('employee_type');
    const submitBtn = document.getElementById('submitBtn');
    
    // Grade data from backend
    const gradeData = @json($gradeLevels);
    const baseSalaries = @json($baseSalaries);
    const overtimeRates = @json($overtimeRates);

    // Select existing grade
    gradeSelectors.forEach(button => {
        button.addEventListener('click', function() {
            const gradeLevel = this.dataset.grade;
            selectGrade(gradeLevel, gradeData[gradeLevel], baseSalaries[gradeLevel], overtimeRates[gradeLevel]);
        });
    });

    // Create custom grade
    createCustomGradeBtn.addEventListener('click', function() {
        const gradeName = document.getElementById('custom_grade_name').value;
        const baseSalary = document.getElementById('custom_base_salary').value;
        const overtimeRate = document.getElementById('custom_overtime_rate').value;
        
        if (!gradeName || !baseSalary) {
            alert('Please enter grade name and base salary');
            return;
        }

        // Generate a unique key for custom grade
        const customGradeKey = 'custom_' + Date.now();
        
        selectGrade(customGradeKey, gradeName, baseSalary, overtimeRate || 0);
        
        // Clear custom form
        document.getElementById('customGradeForm').reset();
    });

    function selectGrade(gradeLevel, gradeName, baseSalary, overtimeRate) {
        // Update selected grade info
        document.getElementById('currentGradeName').textContent = gradeName;
        document.getElementById('currentBaseSalary').textContent = '৳ ' + Number(baseSalary).toLocaleString();
        document.getElementById('currentOvertimeRate').textContent = '৳ ' + Number(overtimeRate).toLocaleString() + '/hour';
        document.getElementById('currentGradeLevel').textContent = gradeLevel;
        
        // Update hidden fields
        document.getElementById('selected_grade_level').value = gradeLevel;
        document.getElementById('selected_base_salary').value = baseSalary;
        document.getElementById('selected_overtime_rate').value = overtimeRate;
        
        // Show selected grade info
        selectedGradeInfo.style.display = 'block';
        
        // Enable submit button if employee type is selected
        updateSubmitButton();
        
        // Calculate preview if employee type is already selected
        if (employeeTypeSelect.value) {
            calculatePreview();
        }
    }

    function updateSubmitButton() {
        const gradeSelected = document.getElementById('selected_grade_level').value;
        const employeeTypeSelected = employeeTypeSelect.value;
        
        submitBtn.disabled = !(gradeSelected && employeeTypeSelected);
    }

    // Employee type change handler
    employeeTypeSelect.addEventListener('change', function() {
        updateSubmitButton();
        if (document.getElementById('selected_grade_level').value) {
            calculatePreview();
        }
    });

    // Custom allowance inputs change handler
    document.querySelectorAll('#custom_house_rent, #custom_medical, #custom_transport, #custom_other_allowance').forEach(input => {
        input.addEventListener('input', function() {
            if (document.getElementById('selected_grade_level').value && employeeTypeSelect.value) {
                calculatePreview();
            }
        });
    });

    function calculatePreview() {
        const gradeLevel = document.getElementById('selected_grade_level').value;
        const baseSalary = document.getElementById('selected_base_salary').value;
        const overtimeRate = document.getElementById('selected_overtime_rate').value;
        const employeeType = employeeTypeSelect.value;
        
        const customAllowances = {
            house_rent: document.getElementById('custom_house_rent').value,
            medical: document.getElementById('custom_medical').value,
            transport: document.getElementById('custom_transport').value,
            other_allowance: document.getElementById('custom_other_allowance').value
        };

        if (gradeLevel && employeeType && baseSalary) {
            fetch('{{ route("admin.salary-structures.calculate-preview") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    grade_level: gradeLevel,
                    employee_type: employeeType,
                    base_salary: baseSalary,
                    overtime_rate: overtimeRate,
                    custom_allowances: customAllowances
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updatePreview(data);
                    document.getElementById('previewSection').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error calculating preview:', error);
            });
        }
    }

    function updatePreview(data) {
        const formatCurrency = (amount) => {
            if (!amount || isNaN(amount)) return '৳ 0.00';
            return '৳ ' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2
            });
        };

        // Update preview sections
        document.getElementById('preview_basic').textContent = formatCurrency(data.data.basic_salary);
        document.getElementById('preview_house_rent').textContent = formatCurrency(data.data.house_rent);
        document.getElementById('preview_medical').textContent = formatCurrency(data.data.medical_allowance);
        document.getElementById('preview_transport').textContent = formatCurrency(data.data.transport_allowance);
        document.getElementById('preview_other').textContent = formatCurrency(data.data.other_allowance);
        document.getElementById('preview_gross').textContent = formatCurrency(data.calculations.gross_salary);
        
        document.getElementById('preview_pf').textContent = (data.data.provident_fund || 0) + '%';
        document.getElementById('preview_tax').textContent = (data.data.tax_deduction || 0) + '%';
        document.getElementById('preview_other_deduction').textContent = formatCurrency(data.data.other_deduction);
        document.getElementById('preview_total_deductions').textContent = formatCurrency(data.calculations.total_deductions);
        
        document.getElementById('preview_overtime').textContent = formatCurrency(data.data.overtime_rate) + '/hour';
        document.getElementById('preview_festival_bonus').textContent = formatCurrency(data.data.festival_bonus);
        document.getElementById('preview_net').textContent = formatCurrency(data.calculations.net_salary);
    }
});
</script>
@endsection