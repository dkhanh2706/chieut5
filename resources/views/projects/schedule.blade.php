@extends('layouts.project')

@section('css')
<style>
    .page-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

    .timeline-container {
        position: relative;
        padding-left: 30px;
    }

    .timeline-container::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #6366f1, #8b5cf6);
        border-radius: 3px;
    }

    .timeline-day {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-date {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .timeline-date::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        background: #6366f1;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.4);
    }

    .timeline-tasks {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .timeline-task {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .timeline-task:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }

    .task-title {
        font-weight: 600;
        font-size: 1rem;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }

    .task-info {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .task-info-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .task-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-pending {
        background: #f1f5f9;
        color: #64748b;
    }

    .status-inprogress {
        background: #fef3c7;
        color: #b45309;
    }

    .status-completed {
        background: #d1fae5;
        color: #059669;
    }

    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .date-start {
        background: #e0f2fe;
        color: #0369a1;
    }

    .date-end {
        background: #fef3c7;
        color: #b45309;
    }

    .no-tasks {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    .no-tasks i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .timeline-empty {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }

    .timeline-empty i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .today-marker {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
</style>
@endsection

@section('project-content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-calendar-alt me-2"></i>Lịch trình công việc</h1>
        <p class="mt-2" style="opacity: 0.9;">{{ $project->name }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $tasks = $project->tasks->filter(function($task) {
            return $task->start_date || $task->end_date;
        });

        $groupedTasks = [];
        
        foreach ($tasks as $task) {
            if ($task->start_date) {
                $date = \Carbon\Carbon::parse($task->start_date)->format('Y-m-d');
                if (!isset($groupedTasks[$date])) {
                    $groupedTasks[$date] = [];
                }
                $groupedTasks[$date][] = ['task' => $task, 'type' => 'start'];
            }
            if ($task->end_date && $task->end_date != $task->start_date) {
                $date = \Carbon\Carbon::parse($task->end_date)->format('Y-m-d');
                if (!isset($groupedTasks[$date])) {
                    $groupedTasks[$date] = [];
                }
                $groupedTasks[$date][] = ['task' => $task, 'type' => 'end'];
            }
        }

        ksort($groupedTasks);

        $today = \Carbon\Carbon::today()->format('Y-m-d');
    @endphp

    @if(count($groupedTasks) > 0)
        <div class="timeline-container">
            @foreach($groupedTasks as $date => $dayTasks)
                <div class="timeline-day">
                    <div class="timeline-date">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                        @if($date == $today)
                            <span class="today-marker">
                                <i class="fas fa-bolt"></i> Hôm nay
                            </span>
                        @endif
                        @if(\Carbon\Carbon::parse($date)->isPast() && $date != $today)
                            <span style="opacity: 0.7; font-size: 0.8rem;">(Đã qua)</span>
                        @endif
                    </div>
                    
                    <div class="timeline-tasks">
                        @foreach($dayTasks as $item)
                            @php $task = $item['task']; @endphp
                            <div class="timeline-task">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="task-title">{{ $task->name }}</div>
                                    @if($task->status == 'Hoàn thành')
                                        <span class="task-status status-completed">Hoàn thành</span>
                                    @elseif($task->status == 'Đang làm')
                                        <span class="task-status status-inprogress">Đang làm</span>
                                    @else
                                        <span class="task-status status-pending">Chưa làm</span>
                                    @endif
                                </div>
                                
                                <div class="task-info">
                                    <div class="task-info-item">
                                        <i class="fas fa-user"></i>
                                        {{ $task->assigned_to }}
                                    </div>
                                    <div class="task-info-item">
                                        <i class="fas fa-clock"></i>
                                        {{ $task->estimated_hours }}h
                                    </div>
                                    <div class="task-info-item">
                                        <i class="fas fa-money-bill"></i>
                                        {{ number_format($task->cost) }}₫
                                    </div>
                                </div>

                                <div class="mt-2 d-flex gap-2">
                                    @if($task->start_date)
                                        <span class="date-badge date-start">
                                            <i class="fas fa-play"></i>
                                            {{ \Carbon\Carbon::parse($task->start_date)->format('d/m') }}
                                        </span>
                                    @endif
                                    @if($task->end_date)
                                        <span class="date-badge date-end">
                                            <i class="fas fa-flag"></i>
                                            {{ \Carbon\Carbon::parse($task->end_date)->format('d/m') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-tasks">
            <i class="fas fa-calendar-times"></i>
            <h4>Chưa có lịch trình</h4>
            <p>Các công việc có ngày bắt đầu hoặc ngày kết thúc sẽ hiển thị tại đây</p>
            <a href="{{ route('projects.kanban', $project->id) }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus me-2"></i>Thêm công việc có ngày
            </a>
        </div>
    @endif

</div>

@endsection