@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Debug Messages Section -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 font-weight-bold text-dark mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-muted mb-0">Here's your daily overview and quick insights</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-calendar me-2"></i>{{ now()->format('l, F j, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Monthly Attendance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $monthlyAttendances }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                Last Salary
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ৳ {{ $latestPayroll ? number_format($latestPayroll->net_salary, 2) : '0.00' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Active Projects
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeProjectsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
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
                                Today's Status
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                @if($todayAttendance)
                                    <span class="badge bg-{{ $todayAttendance->status == 'present' ? 'success' : ($todayAttendance->status == 'late' ? 'warning' : ($todayAttendance->status == 'early_leave' ? 'info' : 'secondary')) }} fs-6">
                                        {{ ucfirst($todayAttendance->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6">Not Checked In</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Attendance & Projects -->
        <div class="col-lg-8 mb-4">
            <!-- Today's Attendance Card -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>Today's Attendance
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($todayAttendance)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="attendance-info mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-success me-3">
                                            <i class="fas fa-sign-in-alt text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Check In</h6>
                                            <p class="mb-0 text-dark fw-bold fs-5">
                                                {{ $todayAttendance->check_in ? $todayAttendance->check_in->format('h:i A') : 'N/A' }}
                                            </p>
                                            @if($todayAttendance->late_minutes > 0)
                                                <small class="text-danger">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Late by {{ $todayAttendance->late_minutes }} minutes
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($todayAttendance->check_out)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-circle bg-danger me-3">
                                                <i class="fas fa-sign-out-alt text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Check Out</h6>
                                                <p class="mb-0 text-dark fw-bold fs-5">
                                                    {{ $todayAttendance->check_out->format('h:i A') }}
                                                </p>
                                                @if($todayAttendance->early_leave_minutes > 0)
                                                    <small class="text-warning">
                                                        <i class="fas fa-running me-1"></i>
                                                        Early leave by {{ $todayAttendance->early_leave_minutes }} minutes
                                                    </small>
                                                @endif
                                                @if($todayAttendance->overtime_minutes > 0)
                                                    <small class="text-success">
                                                        <i class="fas fa-plus-circle me-1"></i>
                                                        Overtime {{ $todayAttendance->overtime_minutes }} minutes
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="attendance-stats">
                                    <div class="stat-card bg-light rounded p-3 mb-3">
                                        <h6 class="text-muted mb-1">Working Hours</h6>
                                        <p class="mb-0 fw-bold fs-5 text-primary">
                                            @if($todayAttendance->check_out)
                                                {{ $todayAttendance->calculateWorkingHours() }} hours
                                            @else
                                                In Progress...
                                            @endif
                                        </p>
                                    </div>
                                    <div class="stat-card bg-light rounded p-3">
                                        <h6 class="text-muted mb-1">Current Status</h6>
                                        <span class="badge bg-{{ $todayAttendance->status == 'present' ? 'success' : ($todayAttendance->status == 'late' ? 'warning' : ($todayAttendance->status == 'early_leave' ? 'info' : 'secondary')) }} fs-6 py-2">
                                            {{ ucfirst($todayAttendance->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$todayAttendance->check_out)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>You haven't checked out yet. Remember to check out when you finish your work.</div>
                                    </div>
                                    <form action="{{ route('employee.check-out') }}" method="POST" class="text-end" id="checkOutForm">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg px-4" id="checkOutBtn">
                                            <i class="fas fa-sign-out-alt me-2"></i>Check Out Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="icon-circle bg-secondary mx-auto mb-4">
                                <i class="fas fa-user-clock fa-2x text-white"></i>
                            </div>
                            <h5 class="text-muted mb-3">You haven't checked in today</h5>
                            <form action="{{ route('employee.check-in') }}" method="POST" id="checkInForm">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="checkInBtn">
                                    <i class="fas fa-sign-in-alt me-2"></i>Check In Now
                                </button>
                            </form>
                            <p class="text-muted small mt-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Check-in is available until 9:00 AM. After that, it will be marked as late.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Projects Section -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient-success text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-project-diagram me-2"></i>My Active Projects
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($activeProjects && $activeProjects->count() > 0)
                        <div class="row">
                            @foreach($activeProjects as $project)
                            <div class="col-md-6 mb-4">
                                <div class="project-card card border h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="card-title mb-0">{{ $project->name }}</h6>
                                            <span class="badge bg-{{ $project->status == 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </div>
                                        
                                        <p class="card-text small text-muted mb-3">
                                            {{ Str::limit($project->description, 100) }}
                                        </p>
                                        
                                        <div class="project-meta mb-3">
                                            <div class="d-flex justify-content-between text-muted small mb-2">
                                                <span><i class="fas fa-tasks me-1"></i> Tasks: {{ $project->tasks->count() }}</span>
                                                <span><i class="fas fa-users me-1"></i> Team: {{ $project->teamMembers->count() }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted small">
                                                <span><i class="fas fa-calendar me-1"></i> Start: {{ $project->start_date->format('M d') }}</span>
                                                @if($project->end_date)
                                                <span>End: {{ $project->end_date->format('M d') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="project-progress mb-3">
                                            @php
                                                $totalTasks = $project->tasks->count();
                                                $completedTasks = $project->tasks->where('status', 'completed')->count();
                                                $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                            @endphp
                                            <div class="d-flex justify-content-between small text-muted mb-1">
                                                <span>Progress</span>
                                                <span>{{ round($progress) }}%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('employee.projects.show', $project) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> View Project
                                            </a>
                                            <a href="{{ route('employee.kanban.project', $project) }}" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-columns me-1"></i> Kanban Board
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('employee.projects.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>View All Projects
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="icon-circle bg-secondary mx-auto mb-4">
                                <i class="fas fa-project-diagram fa-2x text-white"></i>
                            </div>
                            <h5 class="text-muted mb-3">No Active Projects</h5>
                            <p class="text-muted">You are not assigned to any active projects at the moment.</p>
                            <a href="{{ route('employee.projects.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Browse All Projects
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Latest Payroll Card -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient-info text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Latest Payslip
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($latestPayroll)
                        <div class="payslip-info">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Net Salary:</span>
                                <span class="fw-bold fs-5 text-success">৳ {{ number_format($latestPayroll->net_salary, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Pay Period:</span>
                                <span class="fw-bold">
                                    @if($latestPayroll->pay_period)
                                        {{ $latestPayroll->pay_period }}
                                    @else
                                        {{ $latestPayroll->created_at->format('M Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-{{ $latestPayroll->status == 'disbursed' ? 'success' : 'warning' }} fs-6">
                                    {{ ucfirst($latestPayroll->status) }}
                                </span>
                            </div>
                            
                            <!-- Download Payslip Button -->
                            <div class="text-center">
                                <a href="{{ route('employee.payslip.download', $latestPayroll->id) }}" 
                                   class="btn btn-success btn-lg w-100" 
                                   target="_blank">
                                    <i class="fas fa-download me-2"></i>Download Payslip
                                </a>
                                <small class="text-muted mt-2 d-block">PDF format</small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="icon-circle bg-secondary mx-auto mb-3">
                                <i class="fas fa-file-invoice fa-2x text-white"></i>
                            </div>
                            <h6 class="text-muted mb-3">No payroll records available</h6>
                            <p class="text-muted small">Your payslip will appear here once processed</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient-warning text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('employee.attendances.index') }}" class="btn btn-outline-primary btn-sm text-start py-2">
                            <i class="fas fa-history me-2"></i>Attendance History
                        </a>
                        <a href="{{ route('employee.profile') }}" class="btn btn-outline-info btn-sm text-start py-2">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a>
                        <a href="{{ route('employee.payslips.index') }}" class="btn btn-outline-success btn-sm text-start py-2">
                            <i class="fas fa-file-invoice me-2"></i>All Payslips
                        </a>
                        <a href="{{ route('employee.projects.index') }}" class="btn btn-outline-secondary btn-sm text-start py-2">
                            <i class="fas fa-project-diagram me-2"></i>My Projects
                        </a>
                        @if($latestPayroll)
                            <a href="{{ route('employee.payslip.download', $latestPayroll->id) }}" 
                               class="btn btn-outline-warning btn-sm text-start py-2">
                                <i class="fas fa-download me-2"></i>Download Latest Payslip
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Tasks -->
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient-danger text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-tasks me-2"></i>Upcoming Tasks
                    </h5>
                </div>
                <div class="card-body p-3">
                    @if($upcomingTasks && $upcomingTasks->count() > 0)
                        <div class="task-list">
                            @foreach($upcomingTasks as $task)
                            <div class="task-item mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 small">{{ $task->title }}</h6>
                                    <span class="badge bg-{{ $task->priority == 3 ? 'danger' : ($task->priority == 2 ? 'warning' : 'success') }} fs-7">
                                        {{ $task->priority == 3 ? 'High' : ($task->priority == 2 ? 'Medium' : 'Low') }}
                                    </span>
                                </div>
                                <p class="small text-muted mb-2">{{ Str::limit($task->description, 50) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-project-diagram me-1"></i>
                                        {{ $task->project->name }}
                                    </small>
                                    @if($task->due_date)
                                    <small class="text-{{ $task->due_date->isPast() ? 'danger' : 'muted' }}">
                                        {{ $task->due_date->format('M d') }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('employee.projects.index') }}" class="btn btn-outline-danger btn-sm">
                                View All Tasks
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-2x text-muted mb-3"></i>
                            <p class="text-muted small mb-0">No upcoming tasks</p>
                            <p class="text-muted small">You're all caught up!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for better UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to check-in button
    const checkInBtn = document.getElementById('checkInBtn');
    const checkOutBtn = document.getElementById('checkOutBtn');
    
    if (checkInBtn) {
        checkInBtn.addEventListener('click', function(e) {
            // Prevent multiple submissions
            if (this.disabled) {
                e.preventDefault();
                return;
            }
            
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking In...';
            this.disabled = true;
            
            // Optional: Auto-submit after a brief delay to show the loading state
            setTimeout(() => {
                document.getElementById('checkInForm').submit();
            }, 100);
        });
    }
    
    if (checkOutBtn) {
        checkOutBtn.addEventListener('click', function(e) {
            // Prevent multiple submissions
            if (this.disabled) {
                e.preventDefault();
                return;
            }
            
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking Out...';
            this.disabled = true;
            
            // Optional: Auto-submit after a brief delay to show the loading state
            setTimeout(() => {
                document.getElementById('checkOutForm').submit();
            }, 100);
        });
    }
    
    // Add confirmation for check-out
    const checkOutForm = document.getElementById('checkOutForm');
    if (checkOutForm) {
        checkOutForm.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to check out? This action cannot be undone.')) {
                e.preventDefault();
                if (checkOutBtn) {
                    checkOutBtn.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Check Out Now';
                    checkOutBtn.disabled = false;
                }
            }
        });
    }

    // Add hover effects to project cards
    const projectCards = document.querySelectorAll('.project-card');
    projectCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'all 0.3s ease';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
});
</script>

<style>
.icon-circle {
    width: 50px;
    height: 50px;
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

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
}

.rounded-top-3 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}

.rounded-3 {
    border-radius: 1rem !important;
}

.border-left-primary {
    border-left: 4px solid #667eea !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-lg {
    padding: 12px 30px;
    font-weight: 600;
}

.project-card {
    transition: all 0.3s ease;
}

.project-card:hover {
    border-color: #667eea !important;
}

.task-item {
    transition: all 0.3s ease;
}

.task-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-left: -8px;
    margin-right: -8px;
    padding-left: 8px;
    padding-right: 8px;
}

/* Loading animation */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fs-7 {
    font-size: 0.75rem !important;
}
</style>
@endsection