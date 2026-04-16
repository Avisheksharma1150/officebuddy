@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>My Projects</h2>
            <p class="text-muted">All projects assigned to you</p>
        </div>
    </div>

    @if($projects->count() > 0)
    <div class="row">
        @foreach($projects as $project)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card project-card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $project->name }}</h5>
                    <small class="text-white-50">Manager: {{ $project->projectManager->name }}</small>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">{{ Str::limit($project->description, 120) }}</p>
                    
                    <div class="project-stats mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span><i class="fas fa-tasks"></i> {{ $project->tasks->count() }} tasks</span>
                            <span><i class="fas fa-users"></i> {{ $project->teamMembers->count() }} members</span>
                        </div>
                        
                        @php
                            $completedTasks = $project->tasks->where('status', 'completed')->count();
                            $totalTasks = $project->tasks->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Progress</span>
                            <span>{{ round($progress) }}%</span>
                        </div>
                    </div>
                    
                    <div class="project-meta mb-3">
                        <div class="row text-muted small">
                            <div class="col-6">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $project->start_date->format('M d, Y') }}
                            </div>
                            <div class="col-6 text-end">
                                @if($project->end_date)
                                <i class="fas fa-flag me-1"></i>
                                {{ $project->end_date->format('M d, Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">
                            {{ ucfirst($project->status) }}
                        </span>
                        <div class="btn-group">
                            <a href="{{ route('employee.projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('employee.kanban.project', $project) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-columns"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $projects->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="icon-circle bg-secondary mx-auto mb-4">
            <i class="fas fa-project-diagram fa-3x text-white"></i>
        </div>
        <h4 class="text-muted">No Projects Assigned</h4>
        <p class="text-muted">You are not assigned to any projects yet.</p>
        <a href="{{ route('employee.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
    @endif
</div>

<style>
.project-card {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #667eea;
}

.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #6c757d;
}

.project-stats {
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    padding: 15px 0;
}

.project-meta {
    font-size: 0.85rem;
}
</style>
@endsection