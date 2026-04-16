@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-calculator"></i> Payroll Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payrolls</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.payrolls.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Generate Payroll
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

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payrolls.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <input type="month" class="form-control" id="month" name="month" value="{{ $month }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payrolls Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Payroll Records - {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h5>
        </div>
        <div class="card-body">
            @if($payrolls->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Employee ID</th>
                                <th>Month</th>
                                <th>Basic Salary</th>
                                <th>Total Earnings</th>
                                <th>Total Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $payroll->user->name }}</strong>
                                    </td>
                                    <td>{{ $payroll->user->employee_id }}</td>
                                    <td>{{ $payroll->month_year->format('M Y') }}</td>
                                    <td>৳ {{ number_format($payroll->basic_salary, 2) }}</td>
                                    <td>৳ {{ number_format($payroll->total_earnings, 2) }}</td>
                                    <td>৳ {{ number_format($payroll->total_deductions, 2) }}</td>
                                    <td>
                                        <strong>৳ {{ number_format($payroll->net_salary, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payroll->status == 'disbursed' ? 'success' : ($payroll->status == 'processed' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.payrolls.download-payslip', $payroll->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Download Payslip">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if($payroll->status != 'disbursed')
                                                <form action="{{ route('admin.payrolls.disburse', $payroll->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Disburse Salary"
                                                            onclick="return confirm('Mark this salary as disbursed?')">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </button>
                                                </form>
                                            @endif
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
                                <h6 class="card-title">Total Payrolls</h6>
                                <h4 class="mb-0">{{ $payrolls->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h6 class="card-title">Total Disbursed</h6>
                                <h4 class="mb-0">৳ {{ number_format($payrolls->where('status', 'disbursed')->sum('net_salary'), 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h6 class="card-title">Pending</h6>
                                <h4 class="mb-0">{{ $payrolls->where('status', '!=', 'disbursed')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h6 class="card-title">Avg. Salary</h6>
                                <h4 class="mb-0">৳ {{ number_format($payrolls->avg('net_salary') ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calculator fa-4x text-muted mb-3"></i>
                    <h4>No Payroll Records Found</h4>
                    <p class="text-muted">No payroll records found for the selected month.</p>
                    <a href="{{ route('admin.payrolls.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Generate First Payroll
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection