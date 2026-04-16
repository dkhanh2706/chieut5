@extends('layouts.app')

@section('title', 'Lịch trình dự án - IT Project Management')

@section('css')
<style>
    .page-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
    }

    .calendar-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .calendar-nav h3 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
    }

    .nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .nav-btn:hover {
        border-color: #6366f1;
        color: #6366f1;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .calendar-header {
        background: #f8fafc;
        padding: 1rem;
        text-align: center;
        font-weight: 600;
        color: #64748b;
        font-size: 0.85rem;
    }

    .calendar-day {
        background: white;
        min-height: 120px;
        padding: 0.75rem;
        position: relative;
    }

    .calendar-day.other-month {
        background: #f8fafc;
    }

    .calendar-day.other-month .day-number {
        color: #cbd5e1;
    }

    .calendar-day.today {
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
    }

    .calendar-day.today::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0ea5e9, #38bdf8);
    }

    .day-number {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .project-event {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 0.35rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 0.35rem;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .project-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .project-event.start-event {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    }

    .project-event.end-event {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    }

    .project-event.status-done {
        background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
    }

    .project-event.status-pending {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .project-event.status-progress {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .view-toggle {
        display: flex;
        gap: 0.5rem;
        background: #f1f5f9;
        padding: 0.25rem;
        border-radius: 10px;
    }

    .view-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .view-btn.active {
        background: white;
        color: #6366f1;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-calendar me-2"></i>Lịch trình dự án</h1>
        <p class="mt-2" style="opacity: 0.9;">Xem lịch trình các dự án của bạn</p>
    </div>

    @php
        $currentMonth = request('month', date('m'));
        $currentYear = request('year', date('Y'));
        
        $month = (int)$currentMonth;
        $year = (int)$currentYear;
        
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dayOfWeek = date('w', $firstDay);
        
        $prevMonth = $month == 1 ? 12 : $month - 1;
        $prevYear = $month == 1 ? $year - 1 : $year;
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        
        $monthName = date('m/Y', $firstDay);
        
        $daysOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        
        $projectsByDate = [];
        foreach ($projects as $p) {
            if ($p->start_date) {
                $startKey = date('Y-m-d', strtotime($p->start_date));
                if (!isset($projectsByDate[$startKey])) {
                    $projectsByDate[$startKey] = [];
                }
                $projectsByDate[$startKey][] = ['project' => $p, 'type' => 'start'];
            }
            if ($p->end_date && $p->end_date != $p->start_date) {
                $endKey = date('Y-m-d', strtotime($p->end_date));
                if (!isset($projectsByDate[$endKey])) {
                    $projectsByDate[$endKey] = [];
                }
                $projectsByDate[$endKey][] = ['project' => $p, 'type' => 'end'];
            }
        }
        
        $today = date('Y-m-d');
    @endphp

    <div class="calendar-nav">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('projects.calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}" class="nav-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h3>{{ $monthName }}</h3>
            <a href="{{ route('projects.calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}" class="nav-btn">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        
        <a href="{{ route('projects.calendar') }}" class="btn btn-outline-primary">
            <i class="fas fa-home me-2"></i>Hôm nay
        </a>
    </div>

    <div class="calendar-grid">
        @foreach($daysOfWeek as $day)
            <div class="calendar-header">{{ $day }}</div>
        @endforeach
        
        @for($i = 0; $i < $dayOfWeek; $i++)
            <?php 
                $prevDay = $dayOfWeek - $i - 1;
                $otherDay = cal_days_in_month(CAL_GREGORIAN, $month == 1 ? 12 : $month - 1, $month == 1 ? $year - 1 : $year) - $prevDay;
            ?>
            <div class="calendar-day other-month">
                <div class="day-number">{{ $otherDay }}</div>
            </div>
        @endfor
        
        @for($day = 1; $day <= $daysInMonth; $day++)
            <?php 
                $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $isToday = $dateKey == $today;
                $dayProjects = $projectsByDate[$dateKey] ?? [];
            ?>
            <div class="calendar-day {{ $isToday ? 'today' : '' }}">
                <div class="day-number">{{ $day }}</div>
                @foreach($dayProjects as $item)
                    @php 
                        $p = $item['project'];
                        $type = $item['type'];
                        $statusClass = $p->status == 'Hoàn thành' ? 'status-done' : ($p->status == 'Đang làm' ? 'status-progress' : 'status-pending');
                        $typeClass = $type == 'start' ? 'start-event' : ($type == 'end' ? 'end-event' : '');
                    @endphp
                    @php $projectUrl = route('projects.show', $p->id); @endphp
                    <div class="project-event {{ $typeClass }} {{ $statusClass }}" 
                         title="{{ $p->name }} - {{ $type == 'start' ? 'Bắt đầu' : 'Kết thúc' }}"
                         onclick="window.location.href = '{{ $projectUrl }}'">
                        @if($type == 'start')
                            <i class="fas fa-play me-1"></i>
                        @else
                            <i class="fas fa-flag me-1"></i>
                        @endif
                        {{ $p->name }}
                    </div>
                @endforeach
            </div>
        @endfor
    </div>

    <div class="mt-4 p-3 bg-white rounded-3" style="border: 2px solid #e2e8f0;">
        <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Chú thích</h6>
        <div class="d-flex flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="project-event status-pending" style="width: 20px; height: 20px; padding: 0;"></span>
                <span class="small">Chưa bắt đầu</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="project-event status-progress" style="width: 20px; height: 20px; padding: 0;"></span>
                <span class="small">Đang làm</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="project-event status-done" style="width: 20px; height: 20px; padding: 0;"></span>
                <span class="small">Hoàn thành</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="project-event start-event" style="width: 20px; height: 20px; padding: 0;"></span>
                <span class="small">Ngày bắt đầu</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="project-event end-event" style="width: 20px; height: 20px; padding: 0;"></span>
                <span class="small">Ngày kết thúc</span>
            </div>
        </div>
    </div>

</div>

@endsection