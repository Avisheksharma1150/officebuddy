@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-file-invoice-dollar"></i> My Payslips</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payslips</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf"></i> PDF Report</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel"></i> Excel Report</a></li>
                </ul>
            </div>
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
            <form method="GET" action="{{ route('employee.payslips.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="year" class="form-label">Select Year</label>
                        <select class="form-select" id="year" name="year">
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <select class="form-select" id="month" name="month">
                            <option value="">All Months</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('employee.payslips.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payslips Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> My Payslip History</h5>
        </div>
        <div class="card-body">
            @if($payslips->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Pay Period</th>
                                <th>Basic Salary</th>
                                <th>Total Earnings</th>
                                <th>Total Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payslips as $payslip)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $payslip->month_year->format('F Y') }}</strong>
                                    </td>
                                    <td>{{ number_format($payslip->basic_salary, 2) }} Taka</td>
                                    <td>{{ number_format($payslip->total_earnings, 2) }} Taka</td>
                                    <td>{{ number_format($payslip->total_deductions, 2) }} Taka</td>
                                    <td>
                                        <strong>{{ number_format($payslip->net_salary, 2) }} Taka</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payslip->status == 'disbursed' ? 'success' : ($payslip->status == 'processed' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($payslip->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $payslip->disbursement_date ? $payslip->disbursement_date->format('M d, Y') : 'Pending' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('employee.payslip.download', $payslip->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Download Payslip"
                                           target="_blank">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $payslips->links() }}
                </div>

                <!-- Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h6 class="card-title">Total Payslips</h6>
                                <h4 class="mb-0">{{ $payslips->total() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h6 class="card-title">Total Received</h6>
                                <h4 class="mb-0">{{ number_format($payslips->where('status', 'disbursed')->sum('net_salary'), 2) }} Taka</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h6 class="card-title">Average Salary</h6>
                                <h4 class="mb-0">{{ number_format($payslips->avg('net_salary') ?? 0, 2) }} Taka</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h6 class="card-title">Pending</h6>
                                <h4 class="mb-0">{{ $payslips->where('status', '!=', 'disbursed')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                    <h4>No Payslips Found</h4>
                    <p class="text-muted">You don't have any payslips for the selected criteria.</p>
                    <a href="{{ route('employee.payslips.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh"></i> View All Payslips
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.5em 0.75em;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when year or month changes
        const yearSelect = document.getElementById('year');
        const monthSelect = document.getElementById('month');
        
        if (yearSelect) {
            yearSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        if (monthSelect) {
            monthSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
@endsection