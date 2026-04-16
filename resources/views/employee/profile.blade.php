@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 font-weight-bold text-dark mb-1">My Profile</h2>
                    <p class="text-muted mb-0">Manage your personal information and account settings</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-user me-2"></i>{{ $user->isAdmin() ? 'Admin' : 'Employee' }} Profile
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-circle me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Full Name</label>
                            <p class="fw-bold text-dark fs-6">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Email Address</label>
                            <p class="fw-bold text-dark fs-6">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Employee ID</label>
                            <p class="fw-bold text-dark fs-6">{{ $user->employee_id ?? 'Not assigned' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Role</label>
                            <p class="fw-bold text-dark fs-6">
                                <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'success' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Joining Date</label>
                            <p class="fw-bold text-dark fs-6">
                                {{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('M d, Y') : 'Not specified' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Account Created</label>
                            <p class="fw-bold text-dark fs-6">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Information -->
            @if($user->salaryStructure)
            <div class="card shadow border-0 rounded-3 mt-4">
                <div class="card-header bg-gradient-info text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-money-bill-wave me-2"></i>Salary Structure
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Basic Salary</label>
                            <p class="fw-bold text-dark fs-6">৳ {{ number_format($user->salaryStructure->basic_salary, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">House Rent Allowance</label>
                            <p class="fw-bold text-dark fs-6">৳ {{ number_format($user->salaryStructure->house_rent_allowance, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Medical Allowance</label>
                            <p class="fw-bold text-dark fs-6">৳ {{ number_format($user->salaryStructure->medical_allowance, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Transport Allowance</label>
                            <p class="fw-bold text-dark fs-6">৳ {{ number_format($user->salaryStructure->transport_allowance, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Other Allowance</label>
                            <p class="fw-bold text-dark fs-6">৳ {{ number_format($user->salaryStructure->other_allowance, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Total Salary</label>
                            <p class="fw-bold text-success fs-5">
                                ৳ {{ number_format($user->salaryStructure->basic_salary + $user->salaryStructure->house_rent_allowance + $user->salaryStructure->medical_allowance + $user->salaryStructure->transport_allowance + $user->salaryStructure->other_allowance, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Account Summary -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient-success text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Account Summary
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="icon-circle bg-primary mx-auto mb-3">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold text-dark">{{ $user->name }}</h5>
                        <p class="text-muted mb-2">{{ $user->employee_id ?? 'No ID' }}</p>
                        <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'success' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <div class="account-stats">
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                            <span class="text-muted">Member since</span>
                            <span class="fw-bold">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                        @if($user->joining_date)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                            <span class="text-muted">Joining Date</span>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($user->joining_date)->format('M Y') }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                            <span class="text-muted">Account Status</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">Email Verified</span>
                            <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient-warning text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Attendances</span>
                        <span class="fw-bold text-primary">{{ $user->attendances->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Payslips Generated</span>
                        <span class="fw-bold text-success">{{ $user->payrolls->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Salary Structure</span>
                        <span class="badge bg-{{ $user->salaryStructure ? 'success' : 'secondary' }}">
                            {{ $user->salaryStructure ? 'Assigned' : 'Not Set' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient-info text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('employee.dashboard') }}" class="btn btn-outline-primary btn-sm text-start py-2">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('employee.attendances.index') }}" class="btn btn-outline-info btn-sm text-start py-2">
                            <i class="fas fa-history me-2"></i>Attendance History
                        </a>
                        <a href="{{ route('employee.payslips.index') }}" class="btn btn-outline-success btn-sm text-start py-2">
                            <i class="fas fa-file-invoice me-2"></i>My Payslips
                        </a>
                        @if($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger btn-sm text-start py-2">
                            <i class="fas fa-cog me-2"></i>Admin Panel
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
}

.rounded-top-3 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}

.rounded-3 {
    border-radius: 1rem !important;
}

.account-stats {
    border-radius: 10px;
}
</style>
@endsection