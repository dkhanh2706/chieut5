@extends('layouts.project')

@section('css')
<style>
    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--radius-xl);
        margin-bottom: 2rem;
    }

    .page-header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .kanban-board {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
    }

    .kanban-column {
        flex: 0 0 320px;
        background: white;
        border-radius: 16px;
        padding: 1rem;
        min-height: 500px;
    }

    .kanban-column-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .kanban-column-header h4 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .kanban-column-header .count {
        background: rgba(255,255,255,0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .column-todo .kanban-column-header {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #64748b;
    }

    .column-inprogress .kanban-column-header {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
    }

    .column-done .kanban-column-header {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #059669;
    }

    .task-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: grab;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }

    .task-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #c7d2fe;
    }

    .task-card:active {
        cursor: grabbing;
    }

    .task-card.dragging {
        opacity: 0.5;
        transform: rotate(3deg);
    }

    .task-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        color: #1e293b;
    }

    .task-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #64748b;
    }

    .task-meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .task-meta-item i {
        font-size: 0.75rem;
    }

    .task-date {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .task-date.has-date {
        background: #eef2ff;
        color: #4f46e5;
    }

    .task-date.no-date {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .add-task-btn {
        width: 100%;
        padding: 0.75rem;
        border-radius: 10px;
        border: 2px dashed #e2e8f0;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .add-task-btn:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: #eef2ff;
    }

    .task-form {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #e0e7ff;
    }

    .task-form input, 
    .task-form select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }

    .task-form input:focus,
    .task-form select:focus {
        border-color: #6366f1;
        outline: none;
    }

    .task-form-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .task-form-actions button {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .kanban-column.drag-over {
        background: #eef2ff;
    }

    .task-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: #94a3b8;
    }

    .task-empty i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .kanban-column {
            flex: 0 0 280px;
        }
    }
</style>
@endsection

@section('project-content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-columns me-2"></i>Bảng công việc</h1>
        <p class="mt-2" style="opacity: 0.9;">{{ $project->name }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="kanban-board">
        @php
            $columns = [
                'Chưa làm' => ['class' => 'column-todo', 'icon' => 'fa-clock'],
                'Đang làm' => ['class' => 'column-inprogress', 'icon' => 'fa-spinner'],
                'Hoàn thành' => ['class' => 'column-done', 'icon' => 'fa-check-circle']
            ];
        @endphp

        @foreach($columns as $status => $column)
            <div class="kanban-column {{ $column['class'] }}" data-status="{{ $status }}">
                <div class="kanban-column-header">
                    <h4><i class="fas {{ $column['icon'] }} me-2"></i>{{ $status }}</h4>
                    <span class="count">{{ $tasksByStatus[$status]->count() }}</span>
                </div>

                <div class="task-list">
                    @forelse($tasksByStatus[$status] as $task)
                        <div class="task-card" draggable="true" data-task-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->name }}</div>
                            <div class="task-meta">
                                <div class="task-meta-item">
                                    <i class="fas fa-user"></i>
                                    {{ $task->assigned_to }}
                                </div>
                                <div class="task-meta-item">
                                    <i class="fas fa-clock"></i>
                                    {{ $task->estimated_hours }}h
                                </div>
                                <div class="task-meta-item">
                                    <i class="fas fa-money-bill"></i>
                                    {{ number_format($task->cost) }}₫
                                </div>
                            </div>
                            @if($task->start_date || $task->end_date)
                                <div class="mt-2">
                                    @if($task->start_date && $task->end_date)
                                        <span class="task-date has-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($task->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('d/m') }}
                                        </span>
                                    @elseif($task->start_date)
                                        <span class="task-date has-date">
                                            <i class="fas fa-play me-1"></i>
                                            {{ \Carbon\Carbon::parse($task->start_date)->format('d/m') }}
                                        </span>
                                    @elseif($task->end_date)
                                        <span class="task-date has-date">
                                            <i class="fas fa-flag me-1"></i>
                                            {{ \Carbon\Carbon::parse($task->end_date)->format('d/m') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="task-empty">
                            <i class="fas fa-inbox"></i>
                            <p>Chưa có công việc</p>
                        </div>
                    @endforelse
                </div>

                <button class="add-task-btn mt-2" onclick="toggleTaskForm('{{ $status }}')">
                    <i class="fas fa-plus"></i> Thêm công việc
                </button>

                <div class="task-form" id="taskForm{{ $status }}" style="display: none;">
                    <form action="{{ route('projects.addTask', $project->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="text" name="name" class="form-control mb-2" placeholder="Tên công việc" required>
                        <input type="text" name="assigned_to" class="form-control mb-2" placeholder="Người làm" required>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <input type="number" name="estimated_hours" class="form-control" placeholder="Số giờ" required>
                            </div>
                            <div class="col-6">
                                <input type="number" name="cost" class="form-control" placeholder="Chi phí" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <input type="date" name="start_date" class="form-control" placeholder="Ngày bắt đầu">
                            </div>
                            <div class="col-6">
                                <input type="date" name="end_date" class="form-control" placeholder="Ngày kết thúc">
                            </div>
                        </div>
                        <div class="task-form-actions">
                            <button type="submit" class="btn btn-primary btn-sm">Thêm</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleTaskForm('{{ $status }}')">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</div>

<script>
    function toggleTaskForm(status) {
        const form = document.getElementById('taskForm' + status);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const taskCards = document.querySelectorAll('.task-card');
        const columns = document.querySelectorAll('.kanban-column');

        taskCards.forEach(card => {
            card.addEventListener('dragstart', function(e) {
                this.classList.add('dragging');
                e.dataTransfer.setData('taskId', this.dataset.taskId);
            });

            card.addEventListener('dragend', function() {
                this.classList.remove('dragging');
            });
        });

        columns.forEach(column => {
            column.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });

            column.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            column.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                const taskId = e.dataTransfer.getData('taskId');
                const newStatus = this.dataset.status;

                fetch('{{ url("/tasks") }}/' + taskId + '/status', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            });
        });
    });
</script>

@endsection