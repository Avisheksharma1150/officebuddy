<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Payroll;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $todayAttendances = Attendance::whereDate('date', today())->count();
        $pendingPayrolls = Payroll::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact(
            'totalEmployees', 
            'totalAdmins', 
            'todayAttendances',
            'pendingPayrolls'
        ));
    }
    
}