@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-money-bill-wave"></i> Salary Structure Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Salary Structures</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.salary-structures.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Structure
            </a>
<!--             <a href="{{ route('admin.salary-structures.generate-all') }}" class="btn btn-info" 
               onclick="return confirm('Do you want to generate salary structures for all grades and types?')">
                <i class="fas fa-bolt"></i> Generate All
            </a> -->
        </div>
    </div>

    <!-- Success Message -->
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

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Structures</h6>
                    <h4 class="mb-0">{{ $structures->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Active Employees</h6>
                    <h4 class="mb-0">{{ $totalEmployees }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Average Basic Salary</h6>
                    <h4 class="mb-0">৳ {{ number_format($averageSalary, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Unassigned Structures</h6>
                    <h4 class="mb-0">{{ $unassignedStructures }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Structures Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Salary Structure List
            </h5>
        </div>
        <div class="card-body">
            @if($structures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Structure Name</th>
                                <th>Grade</th>
                                <th>Type</th>
                                <th>Basic Salary</th>
                                <th>House Rent</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Employees</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($structures as $structure)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $structure->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $structure->grade_level_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $structure->employee_type == 'permanent' ? 'success' : 'warning' }}">
                                            {{ $structure->employee_type_name }}
                                        </span>
                                    </td>
                                    <td>৳ {{ number_format($structure->basic_salary, 0) }}</td>
                                    <td>৳ {{ number_format($structure->house_rent, 0) }}</td>
                                    <td>৳ {{ number_format($structure->gross_salary, 0) }}</td>
                                    <td>৳ {{ number_format($structure->net_salary, 0) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $structure->users_count ?? 0 }} Employees</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.salary-structures.edit', $structure->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.salary-structures.destroy', $structure->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this salary structure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Delete"
                                                        {{ $structure->users_count > 0 ? 'disabled' : '' }}>
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
            @else
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                    <h4>No Salary Structures Found</h4>
                    <p class="text-muted">Start by creating your first salary structure.</p>
                    <a href="{{ route('admin.salary-structures.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Structure
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection