// Add to your existing JavaScript in employee/kanban/project.blade.php
document.addEventListener('DOMContentLoaded', function() {
    // Initialize done buttons
    initializeDoneButtons();
    
    function initializeDoneButtons() {
        document.querySelectorAll('.done-btn').forEach(button => {
            button.addEventListener('click', handleDoneClick);
        });
    }
    
    function handleDoneClick(e) {
        const button = e.target.closest('.done-btn');
        const taskCard = button.closest('.task-card');
        const taskId = taskCard.dataset.taskId;
        const newStatus = button.dataset.targetStatus;
        
        console.log('Moving task to next status:', newStatus);
        moveTask(taskId, newStatus, taskCard);
    }
    
    // Your existing moveTask function should handle this
    function moveTask(taskId, newStatus, taskCard) {
        const currentColumn = taskCard.closest('.kanban-column');
        const targetColumn = document.querySelector(`[data-status="${newStatus}"]`);
        
        if (!targetColumn) {
            console.error('Target column not found for status:', newStatus);
            return;
        }
        
        const newPosition = targetColumn.querySelectorAll('.task-card').length;
        
        // Show loading state
        setLoadingState(taskCard, true);
        
        // Move task via AJAX
        updateTaskStatus(taskId, newStatus, newPosition, taskCard, currentColumn, targetColumn);
    }
    
    function setLoadingState(taskCard, isLoading) {
        const buttons = taskCard.querySelectorAll('.done-btn, .quick-move-btn');
        buttons.forEach(button => {
            if (isLoading) {
                button.disabled = true;
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.dataset.originalHTML = originalHTML;
            } else {
                button.disabled = false;
                if (button.dataset.originalHTML) {
                    button.innerHTML = button.dataset.originalHTML;
                }
            }
        });
    }
    
    function updateTaskStatus(taskId, newStatus, newPosition, taskCard, currentColumn, targetColumn) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
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
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                moveTaskToColumn(taskCard, currentColumn, targetColumn, newStatus);
                showToast('Task moved successfully!', 'success');
            } else {
                throw new Error('Server responded with error');
            }
        })
        .catch(error => {
            console.error('Error moving task:', error);
            showToast('Error moving task. Please try again.', 'error');
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
    }
    
    function updateTaskCardUI(taskCard, newStatus) {
        // Update the done button for the new status
        updateDoneButton(taskCard, newStatus);
        updateQuickActions(taskCard, newStatus);
    }
    
    function updateDoneButton(taskCard, currentStatus) {
        const doneButton = taskCard.querySelector('.done-btn');
        if (!doneButton) return;
        
        const nextStatusMap = {
            'todo': { next: 'in_progress', text: 'Start Task', class: 'btn-warning' },
            'in_progress': { next: 'review', text: 'Mark for Review', class: 'btn-info' },
            'review': { next: 'completed', text: 'Complete Task', class: 'btn-success' }
        };
        
        const nextStatus = nextStatusMap[currentStatus];
        
        if (nextStatus) {
            doneButton.dataset.targetStatus = nextStatus.next;
            doneButton.innerHTML = `<i class="fas fa-arrow-right me-1"></i>${nextStatus.text}`;
            doneButton.className = `btn btn-sm done-btn ${nextStatus.class}`;
            doneButton.style.display = 'block';
        } else {
            // If no next status (completed), remove the done button
            doneButton.remove();
            
            // Add completed badge
            const buttonContainer = taskCard.querySelector('.d-flex.justify-content-between.align-items-center.mb-2');
            if (buttonContainer) {
                const completedBadge = document.createElement('span');
                completedBadge.className = 'badge bg-success';
                completedBadge.innerHTML = '<i class="fas fa-check"></i> Completed';
                buttonContainer.querySelector('.badge').parentNode.appendChild(completedBadge);
            }
        }
    }
    
    function updateQuickActions(taskCard, currentStatus) {
        const quickButtons = taskCard.querySelectorAll('.quick-move-btn');
        quickButtons.forEach(button => {
            button.disabled = false;
            // Reset to original state
            const originalHTML = button.innerHTML;
            button.innerHTML = originalHTML;
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
            const emptyHtml = getEmptyStateHtml(status);
            column.innerHTML = emptyHtml;
        } else if (taskCount > 0 && emptyState) {
            emptyState.remove();
        }
    }
    
    function getEmptyStateHtml(status) {
        const icons = {
            'todo': 'fas fa-tasks',
            'in_progress': 'fas fa-spinner',
            'review': 'fas fa-eye',
            'completed': 'fas fa-check'
        };
        
        const messages = {
            'todo': 'No tasks to do',
            'in_progress': 'No tasks in progress',
            'review': 'No tasks in review',
            'completed': 'No completed tasks'
        };
        
        return `
            <div class="text-center text-muted p-3">
                <i class="${icons[status]} fa-2x mb-2"></i>
                <p>${messages[status]}</p>
            </div>
        `;
    }
    
    function showToast(message, type = 'success') {
        // Your existing toast function
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