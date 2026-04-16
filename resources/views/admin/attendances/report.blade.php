@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-chart-bar"></i> Attendance Report</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendances.index') }}">Attendances</a></li>
                    <li class="breadcrumb-item active">Report</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Report</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.attendances.report') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <input type="month" class="form-control" id="month" name="month" value="{{ $month }}">
                    </div>
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">Select Employee (Optional)</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $user_id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->employee_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                        <a href="{{ route('admin.attendances.report') }}" class="btn btn-secondary ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Report Summary - {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h5>
        </div>
        <div class="card-body">
            @if($attendances->count() > 0)
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h6 class="card-title">Total Records</h6>
                                <h4 class="mb-0">{{ $attendances->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h6 class="card-title">Present Days</h6>
                                <h4 class="mb-0">{{ $attendances->where('status', 'present')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h6 class="card-title">Late Arrivals</h6>
                                <h4 class="mb-0">{{ $attendances->where('late_minutes', '>', 0)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h6 class="card-title">Total Late Minutes</h6>
                                <h4 class="mb-0">{{ $attendances->sum('late_minutes') }} mins</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Working Hours</th>
                                <th>Status</th>
                                <th>Late Minutes</th>
                                <th>Early Leave</th>
                                <th>Overtime</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $attendance->user->name }}</strong>
                                        <br><small class="text-muted">{{ $attendance->user->employee_id }}</small>
                                    </td>
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                    <td>
                                        @if($attendance->check_in)
                                            {{ $attendance->check_in->format('h:i A') }}
                                        @else
                                            <span class="text-danger">Absent</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out)
                                            {{ $attendance->check_out->format('h:i A') }}
                                        @else
                                            <span class="text-warning">Not checked out</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_in && $attendance->check_out)
                                            {{ $attendance->calculateWorkingHours() }} hours
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $attendance->status == 'present' ? 'success' : 
                                            ($attendance->status == 'late' ? 'warning' : 
                                            ($attendance->status == 'early_leave' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->late_minutes > 0)
                                            <span class="text-danger">{{ $attendance->late_minutes }} mins</span>
                                        @else
                                            <span class="text-success">On time</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->early_leave_minutes > 0)
                                            <span class="text-warning">{{ $attendance->early_leave_minutes }} mins</span>
                                        @else
                                            <span class="text-success">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->overtime_minutes > 0)
                                            <span class="text-success">{{ $attendance->overtime_minutes }} mins</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
                    <h4>No Attendance Records Found</h4>
                    <p class="text-muted">No attendance records found for the selected criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection