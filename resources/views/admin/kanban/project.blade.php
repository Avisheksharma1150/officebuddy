@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Kanban Board - {{ $project->name }}</h2>
            <p class="text-muted">{{ $project->description }}</p>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="fas fa-plus"></i> Add Task
            </button>
            <a href="{{ route('admin.kanban.index') }}" class="btn btn-secondary">
                Back to Projects
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
                    @include('admin.kanban.partials.task-card', ['task' => $task, 'nextStatus' => 'in_progress'])
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
                    @include('admin.kanban.partials.task-card', ['task' => $task, 'nextStatus' => 'review'])
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
                    @include('admin.kanban.partials.task-card', ['task' => $task, 'nextStatus' => 'completed'])
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
                    @include('admin.kanban.partials.task-card', ['task' => $task, 'nextStatus' => null])
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

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kanban.tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assign To *</label>
                                <select class="form-control" id="assigned_to" name="assigned_to" required>
                                    @foreach($project->teamMembers as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="1">Low</option>
                                    <option value="2" selected>Medium</option>
                                    <option value="3">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
<script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeKanbanBoard();
    
    function initializeKanbanBoard() {
        initializeClickToMove();
        initializeQuickMove();
        initializeDragAndDrop();
    }
    
    // ==================== CLICK TO MOVE FUNCTIONALITY ====================
    function initializeClickToMove() {
        document.querySelectorAll('.status-move-btn').forEach(button => {
            button.addEventListener('click', handleStatusMove);
        });
    }
    
    function handleStatusMove(e) {
        const button = e.target.closest('.status-move-btn');
        if (!button) return;
        
        const taskCard = button.closest('.task-card');
        const taskId = taskCard.dataset.taskId;
        const newStatus = button.dataset.targetStatus;
        
        console.log('Moving task:', taskId, 'to status:', newStatus); // Debug log
        moveTask(taskId, newStatus, taskCard);
    }
    
    // ==================== QUICK MOVE FUNCTIONALITY ====================
    function initializeQuickMove() {
        document.querySelectorAll('.quick-move-btn').forEach(button => {
            button.addEventListener('click', handleQuickMove);
        });
    }
    
    function handleQuickMove(e) {
        const button = e.target.closest('.quick-move-btn');
        if (!button) return;
        
        const taskCard = button.closest('.task-card');
        const taskId = taskCard.dataset.taskId;
        const newStatus = button.dataset.targetStatus;
        
        console.log('Quick moving task:', taskId, 'to status:', newStatus); // Debug log
        moveTask(taskId, newStatus, taskCard);
    }
    
    // ==================== DRAG AND DROP FUNCTIONALITY ====================
    function initializeDragAndDrop() {
        const taskCards = document.querySelectorAll('.task-card');
        const columns = document.querySelectorAll('.kanban-column');
        
        taskCards.forEach(card => {
            card.setAttribute('draggable', 'true');
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });
        
        columns.forEach(column => {
            column.addEventListener('dragover', handleDragOver);
            column.addEventListener('dragenter', handleDragEnter);
            column.addEventListener('dragleave', handleDragLeave);
            column.addEventListener('drop', handleDrop);
        });
    }
    
    function handleDragStart(e) {
        const taskCard = e.target.closest('.task-card');
        taskCard.classList.add('dragging');
        e.dataTransfer.setData('text/plain', taskCard.dataset.taskId);
        e.dataTransfer.effectAllowed = 'move';
    }
    
    function handleDragEnd(e) {
        const taskCard = e.target.closest('.task-card');
        taskCard.classList.remove('dragging');
        document.querySelectorAll('.kanban-column').forEach(col => {
            col.classList.remove('drag-over');
        });
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }
    
    function handleDragEnter(e) {
        e.preventDefault();
        const column = e.target.closest('.kanban-column');
        column?.classList.add('drag-over');
    }
    
    function handleDragLeave(e) {
        const column = e.target.closest('.kanban-column');
        if (column && !column.contains(e.relatedTarget)) {
            column.classList.remove('drag-over');
        }
    }
    
    function handleDrop(e) {
        e.preventDefault();
        const column = e.target.closest('.kanban-column');
        if (!column) return;
        
        column.classList.remove('drag-over');
        const taskId = e.dataTransfer.getData('text/plain');
        const newStatus = column.dataset.status;
        const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
        
        if (taskCard) {
            console.log('Drag moving task:', taskId, 'to status:', newStatus); // Debug log
            moveTask(taskId, newStatus, taskCard);
        }
    }
    
    // ==================== CORE TASK MOVEMENT LOGIC ====================
    function moveTask(taskId, newStatus, taskCard) {
        const currentColumn = taskCard.closest('.kanban-column');
        const targetColumn = document.querySelector(`[data-status="${newStatus}"]`);
        
        if (!targetColumn) {
            console.error('Target column not found for status:', newStatus);
            return;
        }
        
        const newPosition = targetColumn.querySelectorAll('.task-card').length;
        
        console.log('Moving task to position:', newPosition); // Debug log
        
        // Show loading state
        setLoadingState(taskCard, true);
        
        // Move task via AJAX
        updateTaskStatus(taskId, newStatus, newPosition, taskCard, currentColumn, targetColumn);
    }
    
    function updateTaskStatus(taskId, newStatus, newPosition, taskCard, currentColumn, targetColumn) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        console.log('Sending AJAX request...'); // Debug log
        
        fetch(`/admin/kanban/tasks/${taskId}/move`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: newStatus,
                position: newPosition
            })
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Server response:', data); // Debug log
            if (data.success) {
                moveTaskToColumn(taskCard, currentColumn, targetColumn, newStatus);
                showToast('Task moved successfully!', 'success');
            } else {
                throw new Error('Server responded with error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error moving task:', error);
            showToast('Error moving task: ' + error.message, 'error');
            setLoadingState(taskCard, false);
        });
    }
    
    function moveTaskToColumn(taskCard, currentColumn, targetColumn, newStatus) {
        // Remove from current column
        taskCard.remove();
        
        // Update task card UI
        updateTaskCardUI(taskCard, newStatus);
        
        // Add to target column
        targetColumn.appendChild(taskCard);
        
        // Add visual feedback
        animateTaskMove(taskCard);
        
        // Update UI state
        updateTaskCounts();
        setLoadingState(taskCard, false);
        
        console.log('Task moved successfully to:', newStatus); // Debug log
    }
    
    function updateTaskCardUI(taskCard, newStatus) {
        updateTaskCardNextStatus(taskCard, newStatus);
        updateQuickActions(taskCard, newStatus);
    }
    
    function updateTaskCardNextStatus(taskCard, currentStatus) {
        const moveButton = taskCard.querySelector('.status-move-btn');
        if (!moveButton) {
            console.log('No status-move-btn found in task card'); // Debug log
            return;
        }
        
        const statusMap = {
            'todo': { 
                next: 'in_progress', 
                text: 'Start Progress', 
                class: 'btn-warning', 
                icon: 'fas fa-play' 
            },
            'in_progress': { 
                next: 'review', 
                text: 'Send for Review', 
                class: 'btn-info', 
                icon: 'fas fa-eye' 
            },
            'review': { 
                next: 'completed', 
                text: 'Mark Complete', 
                class: 'btn-success', 
                icon: 'fas fa-check' 
            },
            'completed': { 
                next: null, 
                text: 'Completed', 
                class: 'btn-secondary', 
                icon: 'fas fa-check' 
            }
        };
        
        const nextStatus = statusMap[currentStatus];
        console.log('Updating button for status:', currentStatus, 'next:', nextStatus); // Debug log
        
        if (nextStatus && nextStatus.next) {
            moveButton.dataset.targetStatus = nextStatus.next;
            moveButton.innerHTML = `<i class="${nextStatus.icon}"></i><span class="d-none d-sm-inline">${nextStatus.text}</span>`;
            moveButton.className = `btn btn-sm status-move-btn ${nextStatus.class}`;
            moveButton.style.display = 'block';
            console.log('Button updated to:', nextStatus.text); // Debug log
        } else {
            moveButton.style.display = 'none';
            console.log('Hiding button for completed task'); // Debug log
        }
    }
    
    function updateQuickActions(taskCard, currentStatus) {
        const quickButtons = taskCard.querySelectorAll('.quick-move-btn');
        quickButtons.forEach(button => {
            button.disabled = false;
            // Reset to original icon
            const originalIcon = getQuickButtonIcon(button.dataset.targetStatus);
            button.innerHTML = `<i class="${originalIcon}"></i>`;
        });
    }
    
    function getQuickButtonIcon(status) {
        const icons = {
            'todo': 'fas fa-tasks',
            'in_progress': 'fas fa-spinner', 
            'review': 'fas fa-eye',
            'completed': 'fas fa-check'
        };
        return icons[status] || 'fas fa-arrow-right';
    }
    
    function setLoadingState(taskCard, isLoading) {
        const buttons = taskCard.querySelectorAll('.status-move-btn, .quick-move-btn');
        buttons.forEach(button => {
            if (isLoading) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            } else {
                button.disabled = false;
                // Restore original content
                if (button.classList.contains('status-move-btn')) {
                    const currentStatus = taskCard.closest('.kanban-column').dataset.status;
                    updateTaskCardNextStatus(taskCard, currentStatus);
                } else {
                    const status = button.dataset.targetStatus;
                    button.innerHTML = `<i class="${getQuickButtonIcon(status)}"></i>`;
                }
            }
        });
    }
    
    function animateTaskMove(taskCard) {
        taskCard.classList.add('task-moved');
        setTimeout(() => taskCard.classList.remove('task-moved'), 500);
    }
    
    function updateTaskCounts() {
        document.querySelectorAll('.kanban-column').forEach(column => {
            const status = column.dataset.status;
            const taskCount = column.querySelectorAll('.task-card').length;
            const badge = column.closest('.card').querySelector('.badge');
            
            if (badge) badge.textContent = taskCount;
            updateEmptyState(column, status, taskCount);
        });
    }
    
    function updateEmptyState(column, status, taskCount) {
        const emptyState = column.querySelector('.text-center');
        
        if (taskCount === 0 && !emptyState) {
            column.innerHTML = getEmptyStateHtml(status);
        } else if (taskCount > 0 && emptyState) {
            emptyState.remove();
        }
    }
    
    function getEmptyStateHtml(status) {
        const config = {
            'todo': { icon: 'fas fa-tasks', message: 'No tasks to do' },
            'in_progress': { icon: 'fas fa-spinner', message: 'No tasks in progress' },
            'review': { icon: 'fas fa-eye', message: 'No tasks in review' },
            'completed': { icon: 'fas fa-check', message: 'No completed tasks' }
        };
        
        const { icon, message } = config[status] || config.todo;
        
        return `
            <div class="text-center text-muted p-3">
                <i class="${icon} fa-2x mb-2"></i>
                <p>${message}</p>
            </div>
        `;
    }
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
        `;
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
});
</script>
@endpush
@push('scripts')
<script src="{{ asset('js/kanban.js') }}"></script>
@endpush