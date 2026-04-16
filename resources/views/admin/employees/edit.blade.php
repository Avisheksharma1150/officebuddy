@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-edit"></i> Edit Employee</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
                    <li class="breadcrumb-item active">Edit {{ $employee->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-edit"></i> Edit Employee Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name"><strong>Full Name</strong></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email"><strong>Email Address</strong></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee_id"><strong>Employee ID</strong></label>
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                   id="employee_id" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="joining_date"><strong>Joining Date</strong></label>
                            <input type="date" class="form-control @error('joining_date') is-invalid @enderror" 
                                   id="joining_date" name="joining_date" value="{{ old('joining_date', $employee->joining_date->format('Y-m-d')) }}" required>
                            @error('joining_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="salary_structure_id"><strong>Salary Structure</strong></label>
                            <select class="form-control @error('salary_structure_id') is-invalid @enderror" 
                                    id="salary_structure_id" name="salary_structure_id" required>
                                <option value="">Select Salary Structure</option>
                                @foreach($salaryStructures as $structure)
                                    <option value="{{ $structure->id }}" 
                                        {{ old('salary_structure_id', $employee->salary_structure_id) == $structure->id ? 'selected' : '' }}>
                                        {{ $structure->name }} (Grade: {{ $structure->grade_level }}, Type: {{ $structure->employee_type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('salary_structure_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password"><strong>New Password (Optional)</strong></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Leave blank to keep current password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Only enter if you want to change the password</small>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation"><strong>Confirm New Password</strong></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <!-- Current Information Display -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Current Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Current Grade:</strong> 
                                            @if($employee->salaryStructure)
                                                <span class="badge bg-info">{{ $employee->salaryStructure->grade_level_name }}</span>
                                            @else
                                                <span class="text-danger">Not assigned</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Current Type:</strong> 
                                            @if($employee->salaryStructure)
                                                <span class="badge bg-{{ $employee->salaryStructure->employee_type == 'permanent' ? 'success' : 'warning' }}">
                                                    {{ $employee->salaryStructure->employee_type_name }}
                                                </span>
                                            @else
                                                <span class="text-danger">Not assigned</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Current Basic Salary:</strong> 
                                            @if($employee->salaryStructure)
                                                ৳ {{ number_format($employee->salaryStructure->basic_salary, 2) }}
                                            @else
                                                <span class="text-danger">Not assigned</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Update Employee
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#salaryStructureModal">
                            <i class="fas fa-eye"></i> View Salary Structures
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Salary Structure Modal -->
<div class="modal fade" id="salaryStructureModal" tabindex="-1" aria-labelledby="salaryStructureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="salaryStructureModalLabel">Available Salary Structures</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Type</th>
                                <th>Basic Salary</th>
                                <th>Gross Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryStructures as $structure)
                                <tr>
                                    <td>{{ $structure->name }}</td>
                                    <td><span class="badge bg-info">{{ $structure->grade_level }}</span></td>
                                    <td><span class="badge bg-{{ $structure->employee_type == 'permanent' ? 'success' : 'warning' }}">{{ $structure->employee_type }}</span></td>
                                    <td>৳ {{ number_format($structure->basic_salary, 2) }}</td>
                                    <td>৳ {{ number_format($structure->gross_salary, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection