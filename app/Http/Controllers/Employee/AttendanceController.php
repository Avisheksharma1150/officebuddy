<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('employee.attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $user = auth()->user();
            $today = now()->toDateString();
            
            // Check if already checked in today
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
                
            if ($todayAttendance) {
                return redirect()->back()
                    ->with('error', 'You have already checked in today at ' . $todayAttendance->check_in->format('h:i A'));
            }
            
            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->date = $today;
            $attendance->check_in = now();
            
            // Check if late (9:00 AM cutoff)
            $expectedCheckIn = Carbon::createFromTime(9, 0, 0);
            $currentTime = now();
            
            if ($currentTime->gt($expectedCheckIn)) {
                $attendance->status = 'late';
                $attendance->late_minutes = $currentTime->diffInMinutes($expectedCheckIn);
            } else {
                $attendance->status = 'present';
            }
            
            $attendance->save();
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Checked in successfully at ' . now()->format('h:i A'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Check-in failed: ' . $e->getMessage());
        }
    }

    public function checkOut(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $user = auth()->user();
            $today = now()->toDateString();
            
            // Get today's attendance
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
                
            if (!$attendance) {
                return redirect()->back()
                    ->with('error', 'You need to check in first before checking out.');
            }
            
            if ($attendance->check_out) {
                return redirect()->back()
                    ->with('error', 'You have already checked out today at ' . $attendance->check_out->format('h:i A'));
            }
            
            $attendance->check_out = now();
            $currentTime = now();
            
            // Check if early leave (5:00 PM standard)
            $expectedCheckOut = Carbon::createFromTime(17, 0, 0);
            if ($currentTime->lt($expectedCheckOut)) {
                $attendance->early_leave_minutes = $expectedCheckOut->diffInMinutes($currentTime);
                if ($attendance->status === 'present' || $attendance->status === 'late') {
                    $attendance->status = 'early_leave';
                }
            }
            
            // Calculate overtime (after 6:00 PM)
            $overtimeStart = Carbon::createFromTime(18, 0, 0);
            if ($currentTime->gt($overtimeStart)) {
                $attendance->overtime_minutes = $currentTime->diffInMinutes($overtimeStart);
            }
            
            $attendance->save();
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Checked out successfully at ' . now()->format('h:i A'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Check-out failed: ' . $e->getMessage());
        }
    }
}