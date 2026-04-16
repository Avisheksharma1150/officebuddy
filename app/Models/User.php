<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
        'joining_date',
        'salary_structure_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'joining_date' => 'date',
    ];

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    // Add to User model
public function projects()
{
    return $this->belongsToMany(Project::class, 'project_team')
                ->withPivot('role')
                ->withTimestamps();
}

public function managedProjects()
{
    return $this->hasMany(Project::class, 'project_manager_id');
}

public function tasks()
{
    return $this->hasMany(Task::class, 'assigned_to');
}


}

