@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Project Teams</h2>
        </div>
    </div>

    <div class="row">
        @foreach($projects as $project)
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $project->name }}</h5>
                    <small class="text-white-50">Manager: {{ $project->projectManager->name }}</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Team Members ({{ $project->teamMembers->count() }})</strong>
                    </div>
                    
                    <div class="team-members">
                        @foreach($project->teamMembers as $member)
                        <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
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
                <div class="card-footer">
                    <a href="{{ route('admin.project-teams.show', $project) }}" class="btn btn-sm btn-outline-primary">
                        Manage Team
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection