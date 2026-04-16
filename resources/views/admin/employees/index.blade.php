@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-users"></i> Employee Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Employee
            </a>
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

    <!-- Employees Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Employees List</h5>
        </div>
        <div class="card-body">
            @if($employees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Employee ID</th>
                                <th>Email</th>
                                <th>Grade Level</th>
                                <th>Employee Type</th>
                                <th>Joining Date</th>
                                <th>Salary Structure</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $employee->name }}</strong>
                                    </td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>
                                        @if($employee->salaryStructure)
                                            <span class="badge bg-info">{{ $employee->salaryStructure->grade_level_name }}</span>
                                        @else
                                            <span class="badge bg-warning">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->salaryStructure)
                                            <span class="badge bg-{{ $employee->salaryStructure->employee_type == 'permanent' ? 'success' : 'warning' }}">
                                                {{ $employee->salaryStructure->employee_type_name }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $employee->joining_date ? $employee->joining_date->format('M d, Y') : 'Not set' }}</td>
                                    <td>
                                        @if($employee->salaryStructure)
                                            <span class="badge bg-primary">{{ $employee->salaryStructure->name }}</span>
                                        @else
                                            <span class="text-danger">No Structure</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.employees.edit', $employee->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Employee">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h6 class="card-title">Total Employees</h6>
                                <h4 class="mb-0">{{ $employees->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h6 class="card-title">Permanent</h6>
                                <h4 class="mb-0">{{ $employees->where('salaryStructure.employee_type', 'permanent')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h6 class="card-title">Temporary/Contract</h6>
                                <h4 class="mb-0">{{ $employees->where('salaryStructure.employee_type', '!=', 'permanent')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h6 class="card-title">Avg. Experience</h6>
                                <h4 class="mb-0">{{ number_format($averageExperience, 1) }} yrs</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4>No Employees Found</h4>
                    <p class="text-muted">Get started by adding your first employee.</p>
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Employee
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection