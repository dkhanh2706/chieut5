@extends('layouts.app')

@section('content')

<div class="w-100">

    <div class="d-flex" style="min-height: calc(100vh - 56px);">

        {{-- 🔥 SIDEBAR TRÁI --}}
        <div style="
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 20px;
    min-height: 100vh;
    position: sticky;
    top: 0;
">

            <h5 class="mb-3">{{ $project->name }}</h5>
            <p style="font-size: 12px; opacity: 0.7;">
                Mã: {{ $project->project_code }}
            </p>

            <hr style="border-color: rgba(255,255,255,0.2)">

            <ul class="nav flex-column">

                <li class="nav-item mb-2">
                    <a href="{{ route('projects.show', $project->id) }}" class="nav-link text-white">
                        <i class="fas fa-chart-pie"></i> Tổng quan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-tasks"></i> Công việc
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-users"></i> Thành viên
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white">
                        <i class="fas fa-calendar"></i> Lịch trình
                    </a>
                </li>

                <li class="nav-item mt-3">
                   <a href="{{ route('projects.trash', $project->id) }}" class="nav-link text-danger">
    <i class="fas fa-trash"></i> Thùng rác
</a>
                </li>

            </ul>

        </div>

        {{-- 🔥 CONTENT --}}
        <div class="flex-fill p-4 w-100">
            @yield('project-content')
        </div>

    </div>

</div>

@endsection