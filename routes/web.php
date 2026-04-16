<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SalaryStructureController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\Admin\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication routes
Auth::routes();

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware(['auth'])->group(function () {
    
    // Home route (redirects based on user role)
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    
    // ==================== ADMIN ROUTES ====================
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Salary Structures (CRUD)
        Route::resource('salary-structures', SalaryStructureController::class);
        // Salary Structures - Additional routes
        Route::post('/salary-structures/calculate-preview', [SalaryStructureController::class, 'calculatePreview'])->name('salary-structures.calculate-preview');
        Route::get('/salary-structures/generate-all', [SalaryStructureController::class, 'generateAllStructures'])->name('salary-structures.generate-all');
        Route::get('/salary-structures/{salaryStructure}', [SalaryStructureController::class, 'show'])->name('salary-structures.show');
        
        // Attendances
        Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/create', [AdminAttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/attendances', [AdminAttendanceController::class, 'store'])->name('attendances.store');
        Route::get('/attendances/report', [AdminAttendanceController::class, 'report'])->name('attendances.report');
        
        // Payrolls
        Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
        Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
        Route::post('/payrolls/{payroll}/disburse', [PayrollController::class, 'disburse'])->name('payrolls.disburse');
        Route::get('/payrolls/{payroll}/download-payslip', [PayrollController::class, 'downloadPayslip'])->name('payrolls.download-payslip');
        
        // Employees (CRUD)
        Route::resource('employees', EmployeeController::class);
        
        // ==================== PROJECT MANAGEMENT ROUTES ====================
        // Projects (CRUD)
        Route::resource('projects', ProjectController::class);
        
        // Project Teams Routes
        Route::get('/project-teams', [ProjectController::class, 'teams'])->name('project-teams.index');
        Route::get('/project-teams/{project}', [ProjectController::class, 'teamShow'])->name('project-teams.show');
        Route::post('/project-teams/{project}/add-member', [ProjectController::class, 'addTeamMember'])->name('project-teams.add-member');
        Route::delete('/project-teams/{project}/remove-member/{user}', [ProjectController::class, 'removeTeamMember'])->name('project-teams.remove-member');
        Route::put('/project-teams/{project}/update-role/{user}', [ProjectController::class, 'updateTeamMemberRole'])->name('project-teams.update-role');
        
        // Kanban Board
        Route::get('/kanban', [ProjectController::class, 'kanban'])->name('kanban.index');
        Route::get('/kanban/{project}', [ProjectController::class, 'projectKanban'])->name('kanban.project');
        Route::post('/kanban/tasks', [ProjectController::class, 'storeTask'])->name('kanban.tasks.store');
        Route::put('/kanban/tasks/{task}', [ProjectController::class, 'updateTask'])->name('kanban.tasks.update');
        Route::delete('/kanban/tasks/{task}', [ProjectController::class, 'destroyTask'])->name('kanban.tasks.destroy');
        Route::post('/kanban/tasks/{task}/move', [ProjectController::class, 'moveTask'])->name('kanban.tasks.move');
    });
    
    // ==================== EMPLOYEE ROUTES ====================
    Route::prefix('employee')->name('employee.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [EmployeeDashboardController::class, 'profile'])->name('profile');
        
        // Attendances
        Route::get('/attendances', [EmployeeAttendanceController::class, 'index'])->name('attendances.index');
        Route::post('/check-in', [EmployeeAttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [EmployeeAttendanceController::class, 'checkOut'])->name('check-out');
        
        // Payslips
        Route::get('/payslips', [EmployeeDashboardController::class, 'payslips'])->name('payslips.index');
        Route::get('/payslip/{id}/download', [EmployeeDashboardController::class, 'downloadPayslip'])->name('payslip.download');
        
        // Employee Project Routes
        Route::get('/projects', [ProjectController::class, 'employeeProjects'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'employeeProjectShow'])->name('projects.show');
        Route::get('/kanban/{project}', [ProjectController::class, 'employeeKanban'])->name('kanban.project');
    });

// Employee routes
Route::prefix('employee')->name('employee.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [EmployeeDashboardController::class, 'profile'])->name('profile');
    
    // Attendance
    Route::get('/attendances', [EmployeeAttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/check-in', [EmployeeAttendanceController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [EmployeeAttendanceController::class, 'checkOut'])->name('check-out');
    
    // Payslips
    Route::get('/payslips', [EmployeeDashboardController::class, 'payslips'])->name('payslips.index');
    Route::get('/payslip/{id}/download', [EmployeeDashboardController::class, 'downloadPayslip'])->name('payslip.download');
    
    // Projects
    Route::get('/projects', [EmployeeDashboardController::class, 'projects'])->name('projects.index');
    Route::get('/projects/{project}', [EmployeeDashboardController::class, 'showProject'])->name('projects.show');
    Route::get('/kanban/{project}', [EmployeeDashboardController::class, 'projectKanban'])->name('kanban.project');
});
    
});

// Remove the duplicate Task Management Routes section at the bottom
// as all project/task routes are already defined in the admin group above