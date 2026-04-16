<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->get();
            
        return view('admin.attendances.index', compact('attendances', 'date'));
    }

    public function create()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i|after:check_in',
        ]);

        // Check if attendance already exists for this user and date
        $existingAttendance = Attendance::where('user_id', $validated['user_id'])
            ->whereDate('date', $validated['date'])
            ->first();

        if ($existingAttendance) {
            return redirect()->back()
                ->with('error', 'Attendance already exists for this user on the selected date.');
        }

        // Calculate late minutes and early leave
        $checkInTime = Carbon::createFromFormat('H:i', $validated['check_in']);
        $checkOutTime = Carbon::createFromFormat('H:i', $validated['check_out']);
        $expectedCheckIn = Carbon::createFromTime(9, 0, 0); // 9:00 AM
        $expectedCheckOut = Carbon::createFromTime(17, 0, 0); // 5:00 PM

        $lateMinutes = max(0, $checkInTime->diffInMinutes($expectedCheckIn, false));
        $earlyLeaveMinutes = max(0, $expectedCheckOut->diffInMinutes($checkOutTime, false));
        
        // Calculate overtime (after 6:00 PM)
        $overtimeStart = Carbon::createFromTime(18, 0, 0); // 6:00 PM
        $overtimeMinutes = max(0, $checkOutTime->diffInMinutes($overtimeStart, false));

        $attendance = new Attendance();
        $attendance->user_id = $validated['user_id'];
        $attendance->date = $validated['date'];
        $attendance->check_in = $validated['date'] . ' ' . $validated['check_in'];
        $attendance->check_out = $validated['date'] . ' ' . $validated['check_out'];
        $attendance->late_minutes = $lateMinutes;
        $attendance->early_leave_minutes = $earlyLeaveMinutes;
        $attendance->overtime_minutes = $overtimeMinutes;
        
        // Set status based on late/early leave
        if ($lateMinutes > 0 && $earlyLeaveMinutes > 0) {
            $attendance->status = 'late';
        } elseif ($lateMinutes > 0) {
            $attendance->status = 'late';
        } elseif ($earlyLeaveMinutes > 0) {
            $attendance->status = 'early_leave';
        } else {
            $attendance->status = 'present';
        }
        
        $attendance->save();

        return redirect()->route('admin.attendances.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function report(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $user_id = $request->input('user_id');
        
        $employees = User::where('role', 'employee')->get();
        
        $attendances = Attendance::with('user')
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2));
            
        if ($user_id) {
            $attendances->where('user_id', $user_id);
        }
        
        $attendances = $attendances->get();
        
        return view('admin.attendances.report', compact('attendances', 'employees', 'month', 'user_id'));
    }
}

