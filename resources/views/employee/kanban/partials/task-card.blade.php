<div class="card task-card mb-3 priority-{{ $task->priority == 3 ? 'high' : ($task->priority == 2 ? 'medium' : 'low') }}" 
     data-task-id="{{ $task->id }}">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="card-title mb-0 flex-grow-1">{{ $task->title }}</h6>
        </div>
        
        @if($task->description)
        <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 80) }}</p>
        @endif
        
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
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-{{ $task->priority == 3 ? 'danger' : ($task->priority == 2 ? 'warning' : 'success') }}">
                {{ $task->priority == 3 ? 'High' : ($task->priority == 2 ? 'Medium' : 'Low') }}
            </span>
            
            <!-- Done Button - Always show for non-completed tasks -->
            @if($task->status !== 'completed')
                @php
                    $nextStatusMap = [
                        'todo' => 'in_progress',
                        'in_progress' => 'review', 
                        'review' => 'completed'
                    ];
                    $nextStatus = $nextStatusMap[$task->status] ?? null;
                    $buttonText = [
                        'todo' => 'Start Task',
                        'in_progress' => 'Mark for Review',
                        'review' => 'Complete Task'
                    ];
                    $buttonClass = [
                        'todo' => 'btn-warning',
                        'in_progress' => 'btn-info',
                        'review' => 'btn-success'
                    ];
                @endphp
                
                @if($nextStatus)
                <button class="btn btn-sm done-btn {{ $buttonClass[$task->status] }}"
                        data-target-status="{{ $nextStatus }}"
                        title="Move to {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}">
                    <i class="fas fa-arrow-right me-1"></i>
                    {{ $buttonText[$task->status] }}
                </button>
                @endif
            @else
                <span class="badge bg-success">
                    <i class="fas fa-check"></i> Completed
                </span>
            @endif
        </div>
        
        <!-- Quick Status Actions -->
        <div class="quick-actions">
            <div class="btn-group w-100" role="group">
                @foreach(['todo', 'in_progress', 'review', 'completed'] as $status)
                    @if($status != $task->status)
                        @php
                            $statusConfig = [
                                'todo' => ['class' => 'outline-primary', 'icon' => 'fas fa-tasks', 'text' => 'To Do'],
                                'in_progress' => ['class' => 'outline-warning', 'icon' => 'fas fa-spinner', 'text' => 'In Progress'],
                                'review' => ['class' => 'outline-info', 'icon' => 'fas fa-eye', 'text' => 'Review'],
                                'completed' => ['class' => 'outline-success', 'icon' => 'fas fa-check', 'text' => 'Completed'],
                            ];
                        @endphp
                        <button type="button" 
                                class="btn btn-sm btn-{{ $statusConfig[$status]['class'] }} quick-move-btn"
                                data-target-status="{{ $status }}"
                                title="Move to {{ $statusConfig[$status]['text'] }}">
                            <i class="{{ $statusConfig[$status]['icon'] }}"></i>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>