@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-calendar-check"></i> Attendance Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attendances</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.attendances.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Record Attendance
            </a>
            <a href="{{ route('admin.attendances.report') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> View Report
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.attendances.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary ms-2">Today</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendances Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Attendance Records - {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
        </div>
        <div class="card-body">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Employee ID</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Working Hours</th>
                                <th>Status</th>
                                <th>Late Minutes</th>
                                <th>Overtime</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $attendance->user->name }}</strong>
                                    </td>
                                    <td>{{ $attendance->user->employee_id }}</td>
                                    <td>
                                        @if($attendance->check_in)
                                            {{ $attendance->check_in->format('h:i A') }}
                                        @else
                                            <span class="text-muted">Not checked in</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out)
                                            {{ $attendance->check_out->format('h:i A') }}
                                        @else
                                            <span class="text-danger">Not checked out</span>
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

                <!-- Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h6 class="card-title">Total Present</h6>
                                <h4 class="mb-0">{{ $attendances->count() }}</h4>
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
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h6 class="card-title">Early Leaves</h6>
                                <h4 class="mb-0">{{ $attendances->where('early_leave_minutes', '>', 0)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h6 class="card-title">Overtime</h6>
                                <h4 class="mb-0">{{ $attendances->where('overtime_minutes', '>', 0)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4>No Attendance Records Found</h4>
                    <p class="text-muted">No attendance records found for the selected date.</p>
                    <a href="{{ route('admin.attendances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Record First Attendance
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection