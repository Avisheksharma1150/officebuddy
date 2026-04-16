@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Project Kanban Boards</h2>
        </div>
    </div>

    <div class="row">
        @foreach($projects as $project)
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">{{ $project->name }}</h5>
                    <small class="text-white-50">
                        {{ $project->tasks->count() }} tasks • 
                        {{ $project->todo_tasks->count() }} todo
                    </small>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                    
                    <div class="progress mb-3" style="height: 8px;">
                        @php
                            $totalTasks = $project->tasks->count();
                            $completedTasks = $project->completed_tasks->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between text-muted small">
                        <span>{{ $completedTasks }}/{{ $totalTasks }} completed</span>
                        <span>{{ round($progress) }}%</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.kanban.project', $project) }}" class="btn btn-sm btn-outline-info w-100">
                        Open Kanban Board
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($projects->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-columns fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No Active Projects</h4>
        <p class="text-muted">Create a project to start using Kanban boards.</p>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Create Project</a>
    </div>
    @endif
</div>
@endsection