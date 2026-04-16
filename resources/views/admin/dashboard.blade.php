@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Admin Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Attendances</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayAttendances }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Payrolls</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPayrolls }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Administrators</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmins }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white py-4 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('admin.employees.create') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-user-plus fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Add Employee</h6>
                                    <p class="text-muted small mb-0">Add new team members to the system</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-primary">Get Started</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('admin.attendances.create') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-calendar-plus fa-2x text-success"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Record Attendance</h6>
                                    <p class="text-muted small mb-0">Log employee attendance records</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-success">Record Now</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('admin.payrolls.create') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-calculator fa-2x text-info"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Generate Payroll</h6>
                                    <p class="text-muted small mb-0">Process salary calculations</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-info">Generate</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('admin.salary-structures.create') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Salary Structure</h6>
                                    <p class="text-muted small mb-0">Create compensation packages</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-warning">Configure</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Management Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-gradient-secondary text-white py-4 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-project-diagram me-2"></i>Project Management
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-xl-4 col-md-6">
                            <a href="{{ route('admin.projects.index') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-tasks fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Running Projects</h6>
                                    <p class="text-muted small mb-0">View and manage active projects</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-primary">View Projects</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-4 col-md-6">
                            <a href="{{ route('admin.project-teams.index') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-users fa-2x text-success"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Project Teams</h6>
                                    <p class="text-muted small mb-0">Manage project teams and members</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-success">Manage Teams</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-4 col-md-6">
                            <a href="{{ route('admin.kanban.index') }}" class="card action-card h-100 text-decoration-none">
                                <div class="card-body text-center p-4">
                                    <div class="action-icon mb-3">
                                        <i class="fas fa-columns fa-2x text-info"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Project Kanban</h6>
                                    <p class="text-muted small mb-0">Track tasks with Kanban board</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 py-3">
                                    <span class="btn btn-sm btn-outline-info">Open Board</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.action-card {
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #fff;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #667eea;
}

.action-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(102, 126, 234, 0.1);
    transition: all 0.3s ease;
}

.action-card:hover .action-icon {
    background: rgba(102, 126, 234, 0.2);
    transform: scale(1.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%) !important;
}

.rounded-top-3 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}

.rounded-3 {
    border-radius: 1rem !important;
}

.action-card .btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.action-card .btn-outline-success {
    border-color: #28a745;
    color: #28a745;
}

.action-card .btn-outline-info {
    border-color: #17a2b8;
    color: #17a2b8;
}

.action-card .btn-outline-warning {
    border-color: #ffc107;
    color: #ffc107;
}

.action-card:hover .btn-outline-primary {
    background: #667eea;
    color: white;
}

.action-card:hover .btn-outline-success {
    background: #28a745;
    color: white;
}

.action-card:hover .btn-outline-info {
    background: #17a2b8;
    color: white;
}

.action-card:hover .btn-outline-warning {
    background: #ffc107;
    color: white;
}
</style>
@endsection