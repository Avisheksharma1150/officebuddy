<div class="card task-card mb-3 priority-{{ $task->priority == 3 ? 'high' : ($task->priority == 2 ? 'medium' : 'low') }}" 
     data-task-id="{{ $task->id }}">
    <div class="card-body p-3">
        <!-- Header with title and dropdown -->
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="card-title mb-0 flex-grow-1">{{ $task->title }}</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <button class="dropdown-item" type="button" data-bs-toggle="modal" 
                                data-bs-target="#editTaskModal{{ $task->id }}">
                            <i class="fas fa-edit me-2"></i>Edit
                        </button>
                    </li>
                    <li>
                        <form action="{{ route('admin.kanban.tasks.destroy', $task) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Description -->
        @if($task->description)
        <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 80) }}</p>
        @endif
        
        <!-- Task metadata -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">
                <i class="fas fa-user me-1"></i> 
                {{ $task->assignedUser->name }}
            </small>
            @if($task->due_date)
            <small class="text-muted">
                <i class="fas fa-calendar me-1"></i> 
                <span class="due-date {{ $task->due_date->isPast() && $task->status != 'completed' ? 'text-danger' : '' }}">
                    {{ $task->due_date->format('M d') }}
                </span>
            </small>
            @endif
        </div>
        
        <!-- Priority and main action button -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-{{ $task->priority == 3 ? 'danger' : ($task->priority == 2 ? 'warning' : 'success') }}">
                {{ $task->priority == 3 ? 'High' : ($task->priority == 2 ? 'Medium' : 'Low') }}
            </span>
            
            @if(isset($nextStatus) && $nextStatus)
                @php
                    $buttonConfig = [
                        'todo' => ['class' => 'btn-warning', 'text' => 'Start Progress', 'icon' => 'fas fa-play'],
                        'in_progress' => ['class' => 'btn-info', 'text' => 'Send for Review', 'icon' => 'fas fa-eye'],
                        'review' => ['class' => 'btn-success', 'text' => 'Mark Complete', 'icon' => 'fas fa-check'],
                    ];
                    $config = $buttonConfig[$task->status] ?? ['class' => 'btn-secondary', 'text' => 'Move', 'icon' => 'fas fa-arrow-right'];
                @endphp
                
                <button class="btn btn-sm status-move-btn {{ $config['class'] }}"
                        data-target-status="{{ $nextStatus }}"
                        title="Move to {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}">
                    <i class="{{ $config['icon'] }}"></i>
                    <span class="d-none d-sm-inline">{{ $config['text'] }}</span>
                </button>
            @elseif($task->status === 'completed')
                <span class="badge bg-success">
                    <i class="fas fa-check"></i> Done
                </span>
            @endif
        </div>
        
        <!-- Quick status actions -->
        <div class="quick-actions">
            <div class="btn-group w-100" role="group">
                @foreach(['todo', 'in_progress', 'review', 'completed'] as $status)
                    @if($status != $task->status)
                        @php
                            $statusConfig = [
                                'todo' => ['class' => 'outline-primary', 'icon' => 'fas fa-tasks'],
                                'in_progress' => ['class' => 'outline-warning', 'icon' => 'fas fa-spinner'],
                                'review' => ['class' => 'outline-info', 'icon' => 'fas fa-eye'],
                                'completed' => ['class' => 'outline-success', 'icon' => 'fas fa-check'],
                            ];
                        @endphp
                        <button type="button" 
                                class="btn btn-sm btn-{{ $statusConfig[$status]['class'] }} quick-move-btn"
                                data-target-status="{{ $status }}"
                                title="Move to {{ ucfirst($status) }}">
                            <i class="{{ $statusConfig[$status]['icon'] }}"></i>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kanban.tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title{{ $task->id }}" class="form-label">Task Title *</label>
                        <input type="text" class="form-control" id="title{{ $task->id }}" name="title" 
                               value="{{ $task->title }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description{{ $task->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="description{{ $task->id }}" name="description" rows="3">{{ $task->description }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to{{ $task->id }}" class="form-label">Assign To *</label>
                                <select class="form-control" id="assigned_to{{ $task->id }}" name="assigned_to" required>
                                    @foreach($project->teamMembers as $member)
                                    <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority{{ $task->id }}" class="form-label">Priority</label>
                                <select class="form-control" id="priority{{ $task->id }}" name="priority">
                                    <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}>Low</option>
                                    <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>Medium</option>
                                    <option value="3" {{ $task->priority == 3 ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="due_date{{ $task->id }}" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date{{ $task->id }}" name="due_date" 
                               value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>