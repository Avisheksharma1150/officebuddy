@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Kanban Board - {{ $project->name }}</h2>
            <p class="text-muted">{{ $project->description }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.projects.show', $project) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Project
            </a>
            <a href="{{ route('employee.projects.index') }}" class="btn btn-secondary">
                <i class="fas fa-list me-2"></i> All Projects
            </a>
        </div>
    </div>

    <div class="row kanban-board" data-project-id="{{ $project->id }}">
        <!-- To Do Column -->
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">To Do</h6>
                    <span class="badge bg-light text-dark">{{ $project->todo_tasks->count() }}</span>
                </div>
                <div class="card-body kanban-column" data-status="todo" style="min-height: 600px;">
                    @foreach($project->todo_tasks as $task)
                    @include('employee.kanban.partials.task-card', ['task' => $task, 'nextStatus' => 'in_progress'])
                    @endforeach
                    @if($project->todo_tasks->count() == 0)
                    <div class="text-center text-muted p-3">
                        <i class="fas fa-tasks fa-2x mb-2"></i>
                        <p>No tasks</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">In Progress</h6>
                    <span class="badge bg-light text-dark">{{ $project->in_progress_tasks->count() }}</span>
                </div>
                <div class="card-body kanban-column" data-status="in_progress" style="min-height: 600px;">
                    @foreach($project->in_progress_tasks as $task)
                    @include('employee.kanban.partials.task-card', ['task' => $task, 'nextStatus' => 'review'])
                    @endforeach
                    @if($project->in_progress_tasks->count() == 0)
                    <div class="text-center text-muted p-3">
                        <i class="fas fa-spinner fa-2x mb-2"></i>
                        <p>No tasks</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Review Column -->
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Review</h6>
                    <span class="badge bg-light text-dark">{{ $project->review_tasks->count() }}</span>
                </div>
                <div class="card-body kanban-column" data-status="review" style="min-height: 600px;">
                    @foreach($project->review_tasks as $task)
                    @include('employee.kanban.partials.task-card', ['task' => $task, 'nextStatus' => 'completed'])
                    @endforeach
                    @if($project->review_tasks->count() == 0)
                    <div class="text-center text-muted p-3">
                        <i class="fas fa-eye fa-2x mb-2"></i>
                        <p>No tasks</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Completed Column -->
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Completed</h6>
                    <span class="badge bg-light text-dark">{{ $project->completed_tasks->count() }}</span>
                </div>
                <div class="card-body kanban-column" data-status="completed" style="min-height: 600px;">
                    @foreach($project->completed_tasks as $task)
                    @include('employee.kanban.partials.task-card', ['task' => $task, 'nextStatus' => null])
                    @endforeach
                    @if($project->completed_tasks->count() == 0)
                    <div class="text-center text-muted p-3">
                        <i class="fas fa-check fa-2x mb-2"></i>
                        <p>No tasks</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the same JavaScript and CSS from admin kanban -->
@push('styles')
<style>
.kanban-column {
    transition: all 0.3s ease;
    padding: 10px;
    min-height: 600px;
}

.kanban-column.drag-over {
    background-color: #f8f9fa;
    border: 2px dashed #007bff !important;
}

.task-card {
    cursor: grab;
    transition: all 0.3s ease;
    margin-bottom: 12px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
}

.task-card.dragging {
    opacity: 0.6;
    transform: rotate(5deg);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.task-card:active {
    cursor: grabbing;
}

.task-card.task-moved {
    animation: taskMove 0.5s ease;
}

.priority-high { 
    border-left: 4px solid #dc3545; 
}
.priority-medium { 
    border-left: 4px solid #ffc107; 
}
.priority-low { 
    border-left: 4px solid #28a745; 
}

.status-move-btn {
    transition: all 0.3s ease;
}

.quick-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 8px;
    margin-top: 8px;
}

@keyframes taskMove {
    0% { transform: scale(1); }
    50% { transform: scale(0.95); }
    100% { transform: scale(1); }
}
</style>
@endpush

@push('scripts')
<!-- Use the same JavaScript from admin kanban -->
<script src="{{ asset('js/kanban.js') }}"></script>
@endpush
@endsection