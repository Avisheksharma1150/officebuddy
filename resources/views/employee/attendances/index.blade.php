@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>My Attendance Records</h2>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Attendance History</h5>
        </div>
        <div class="card-body">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Working Hours</th>
                                <th>Late Minutes</th>
                                <th>Overtime Minutes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('h:i A') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $attendance->status == 'present' ? 'success' : 
                                            ($attendance->status == 'late' ? 'warning' : 
                                            ($attendance->status == 'early_leave' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->check_in && $attendance->check_out ? $attendance->calculateWorkingHours() : 'N/A' }} hours</td>
                                    <td>{{ $attendance->late_minutes }} mins</td>
                                    <td>{{ $attendance->overtime_minutes }} mins</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $attendances->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <p class="mb-0">No attendance records found.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <form action="{{ route('employee.check-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Check In
                            </button>
                        </form>
                        <form action="{{ route('employee.check-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-sign-out-alt"></i> Check Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        border-top: none;
    }
    .badge {
        font-size: 0.85em;
    }
</style>
@endsection