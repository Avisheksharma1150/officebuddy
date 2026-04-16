@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Team Management - {{ $project->name }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.project-teams.index') }}" class="btn btn-secondary">
                Back to Teams
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Current Team Members</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->teamMembers as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>
                                        <form action="{{ route('admin.project-teams.update-role', [$project, $member]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="team_member" {{ $member->pivot->role == 'team_member' ? 'selected' : '' }}>Team Member</option>
                                                <option value="team_lead" {{ $member->pivot->role == 'team_lead' ? 'selected' : '' }}>Team Lead</option>
                                                <option value="project_manager" {{ $member->pivot->role == 'project_manager' ? 'selected' : '' }}>Project Manager</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.project-teams.remove-member', [$project, $member]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this member?')">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add Team Member</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.project-teams.add-member', $project) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Select Employee</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">Select Employee</option>
                                @foreach($availableEmployees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="team_member">Team Member</option>
                                <option value="team_lead">Team Lead</option>
                                <option value="project_manager">Project Manager</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add to Team</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection