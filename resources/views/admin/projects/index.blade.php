@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Projects Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Project
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Project Name</th>
                            <th>Manager</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Team Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->projectManager->name }}</td>
                            <td>{{ $project->start_date->format('M d, Y') }}</td>
                            <td>{{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $project->priority == 'high' ? 'danger' : ($project->priority == 'medium' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($project->priority) }}
                                </span>
                            </td>
                            <td>{{ $project->teamMembers->count() }} members</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No projects found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection