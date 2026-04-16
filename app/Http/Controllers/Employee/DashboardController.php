<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Attendance data
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->first();

        $monthlyAttendances = Attendance::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Payroll data with null handling
        $latestPayroll = Payroll::where('user_id', $user->id)
            ->latest()
            ->first();

        // Project management data
        $activeProjects = $user->projects()
            ->whereIn('status', ['active', 'planning'])
            ->with(['tasks', 'teamMembers'])
            ->get();

        $activeProjectsCount = $activeProjects->count();

        // Upcoming tasks
        $upcomingTasks = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        return view('employee.dashboard', compact(
            'todayAttendance',
            'monthlyAttendances',
            'latestPayroll',
            'activeProjects',
            'activeProjectsCount',
            'upcomingTasks'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('employee.profile', compact('user'));
    }

    public function payslips()
    {
        $payslips = Payroll::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('employee.payslips', compact('payslips'));
    }

    public function downloadPayslip($id)
    {
        $payslip = Payroll::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        try {
            // Generate filename safely
            $filename = 'payslip-' . Carbon::now()->format('F-Y') . '.pdf';
            
            // If pay_period exists, use it for filename
            if ($payslip->pay_period) {
                $filename = 'payslip-' . $payslip->pay_period . '.pdf';
            }
            
            // Load the view with proper data
            $pdf = \PDF::loadView('employee.payslip-pdf', compact('payslip'));
            
            // Set PDF configuration
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('dpi', 150);
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            
            return redirect()->route('employee.payslips')
                ->with('error', 'PDF generation failed. Please try again.');
        }
    }

    // Add these methods for project management
    public function projects()
    {
        $projects = auth()->user()->projects()
            ->with(['projectManager', 'teamMembers', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('employee.projects.index', compact('projects'));
    }

    public function showProject(Project $project)
    {
        // Check if user has access to this project
        if (!auth()->user()->projects->contains($project)) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['projectManager', 'teamMembers', 'tasks.assignedUser']);
        return view('employee.projects.show', compact('project'));
    }

    public function projectKanban(Project $project)
    {
        // Check if user has access to this project
        if (!auth()->user()->projects->contains($project)) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['tasks.assignedUser']);
        return view('employee.kanban.project', compact('project'));
    }
}