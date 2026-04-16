@extends('layouts.project')

@section('css')
<style>
    .page-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

    .member-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .member-card:hover {
        border-color: #e0e7ff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .member-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .member-info {
        flex: 1;
    }

    .member-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .member-email {
        color: #64748b;
        font-size: 0.9rem;
    }

    .leader-badge {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .add-member-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 2px dashed #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        color: #64748b;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-member-card:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: #f8fafc;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #6366f1;
    }

    .stats-label {
        color: #64748b;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('project-content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-users me-2"></i>Thành viên</h1>
        <p class="mt-2" style="opacity: 0.9;">{{ $project->name }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $project->users->count() }}</div>
                <div class="stats-label">Tổng thành viên</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $project->tasks->count() }}</div>
                <div class="stats-label">Tổng công việc</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $project->tasks->where('status', 'Hoàn thành')->count() }}</div>
                <div class="stats-label">Đã hoàn thành</div>
            </div>
        </div>
    </div>

    @if(auth()->id() == $project->leader)
        <div class="card mb-4" style="border-radius: 16px; border: 2px solid #e2e8f0;">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-user-plus me-2"></i>Thêm thành viên</h5>
                <form method="POST" action="{{ route('projects.addMember', $project->id) }}" class="d-flex gap-3">
                    @csrf
                    <input type="email" name="email" class="form-control" placeholder="Nhập email thành viên" required style="border-radius: 10px;">
                    <button class="btn btn-primary" style="border-radius: 10px;">
                        <i class="fas fa-plus me-2"></i>Thêm
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="row g-4">
        @foreach($project->users as $user)
            <div class="col-md-6 col-lg-4">
                <div class="member-card">
                    <div class="member-avatar">
                        {{ strtoupper(substr($user->email, 0, 1)) }}
                    </div>
                    <div class="member-info">
                        <div class="member-name">{{ $user->name ?? 'User' }}</div>
                        <div class="member-email">{{ $user->email }}</div>
                    </div>
                    @if($user->id == $project->leader)
                        <span class="leader-badge">
                            <i class="fas fa-crown me-1"></i>Leader
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection