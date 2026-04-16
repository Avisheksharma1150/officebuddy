<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'priority',
        'budget',
        'project_manager_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_team')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getTodoTasksAttribute()
    {
        return $this->tasks()->where('status', 'todo')->orderBy('position')->get();
    }

    public function getInProgressTasksAttribute()
    {
        return $this->tasks()->where('status', 'in_progress')->orderBy('position')->get();
    }

    public function getReviewTasksAttribute()
    {
        return $this->tasks()->where('status', 'review')->orderBy('position')->get();
    }

    public function getCompletedTasksAttribute()
    {
        return $this->tasks()->where('status', 'completed')->orderBy('position')->get();
    }
}