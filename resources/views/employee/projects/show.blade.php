@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $project->name }}</h2>
            <p class="text-muted">{{ $project->description }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.kanban.project', $project) }}" class="btn btn-info">
                <i class="fas fa-columns me-2"></i> Kanban Board
            </a>
            <a href="{{ route('employee.projects.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Project Details -->
        <div class="col-md-8">
            <!-- Project Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Project Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Project Manager:</strong></td>
                                    <td>{{ $project->projectManager->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start Date:</strong></td>
                                    <td>{{ $project->start_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Priority:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $project->priority == 'high' ? 'danger' : ($project->priority == 'medium' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($project->priority) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Team Members:</strong></td>
                                    <td>{{ $project->teamMembers->count() }}</td>
                                </tr>
                                @if($project->end_date)
                                <tr>
                                    <td><strong>End Date:</strong></td>
                                    <td>{{ $project->end_date->format('M d, Y') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Card -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Project Tasks ({{ $project->tasks->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($project->tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->tasks as $task)
                                <tr>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                        <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $task->assignedUser->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $task->priority == 3 ? 'danger' : ($task->priority == 2 ? 'warning' : 'success') }}">
                                            {{ $task->priority == 3 ? 'High' : ($task->priority == 2 ? 'Medium' : 'Low') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <span class="{{ $task->due_date->isPast() && $task->status != 'completed' ? 'text-danger' : 'text-muted' }}">
                                                {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Tasks Yet</h5>
                        <p class="text-muted">No tasks have been created for this project.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Team Members Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Team Members ({{ $project->teamMembers->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($project->teamMembers as $member)
                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                        <div class="flex-grow-1">
                            <strong>{{ $member->name }}</strong>
                            <br>
                            <small class="text-muted">
                                <span class="badge bg-{{ $member->pivot->role == 'project_manager' ? 'primary' : ($member->pivot->role == 'team_lead' ? 'warning' : 'secondary') }}">
                                    {{ str_replace('_', ' ', ucfirst($member->pivot->role)) }}
                                </span>
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Project Progress Card -->
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Project Progress</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalTasks = $project->tasks->count();
                        $completedTasks = $project->tasks->where('status', 'completed')->count();
                        $inProgressTasks = $project->tasks->where('status', 'in_progress')->count();
                        $todoTasks = $project->tasks->where('status', 'todo')->count();
                        $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    @endphp
                    
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div class="progress-circle" data-progress="{{ $progress }}">
                                <span class="progress-value">{{ round($progress) }}%</span>
                            </div>
                        </div>
                        <h5 class="mt-3">Overall Progress</h5>
                    </div>
                    
                    <div class="task-stats">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Completed:</span>
                            <span class="badge bg-success">{{ $completedTasks }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>In Progress:</span>
                            <span class="badge bg-warning">{{ $inProgressTasks }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>To Do:</span>
                            <span class="badge bg-secondary">{{ $todoTasks }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: conic-gradient(#28a745 var(--progress), #e9ecef 0deg);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.progress-circle::before {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: white;
}

.progress-value {
    position: relative;
    font-weight: bold;
    font-size: 1.2rem;
    color: #28a745;
}

.task-stats {
    border-top: 1px solid #e9ecef;
    padding-top: 15px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressCircles = document.querySelectorAll('.progress-circle');
    progressCircles.forEach(circle => {
        const progress = circle.getAttribute('data-progress');
        circle.style.setProperty('--progress', `${progress}%`);
    });
});
</script>
@endsection