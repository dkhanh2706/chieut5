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

    .stat-card {
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
    }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-card p {
        margin: 0.5rem 0 0 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .info-card {
        border-radius: var(--radius-lg);
        padding: 1.5rem;
    }

    .info-card h5 {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item i {
        width: 20px;
        color: var(--primary);
    }

    .task-card {
        border-radius: var(--radius-lg);
        transition: all 0.3s ease;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .task-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }

    .task-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .member-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: var(--radius);
        background: #f8fafc;
        margin-bottom: 0.5rem;
    }

    .member-item i {
        color: var(--primary);
    }

    .task-form-card {
        border-radius: var(--radius-lg);
        padding: 1.5rem;
    }

    .task-form-card h5 {
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .add-task-btn {
        background: var(--success);
        border: none;
    }

    .add-task-btn:hover {
        background: #059669;
    }

    .status-pending {
        background: #f1f5f9;
        color: var(--text-secondary);
    }

    .status-in-progress {
        background: #fef3c7;
        color: #b45309;
    }

    .status-completed {
        background: #d1fae5;
        color: #047857;
    }
</style>
@endsection

@section('project-content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-folder me-2"></i>{{ $project->name }}</h1>
        <p class="mt-2" style="opacity: 0.9;">Mã dự án: {{ $project->project_code }}</p>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card" style="background: #6366f1; color: white;">
                <h3>{{ $totalHours }}</h3>
                <p>Tổng thời gian (giờ)</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card" style="background: #10b981; color: white;">
                <h3>{{ number_format($totalCost) }} ₫</h3>
                <p>Tổng chi phí</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card info-card">
                <h5><i class="fas fa-info-circle me-2"></i>Thông tin dự án</h5>
                <div class="info-item">
                    <i class="fas fa-code"></i>
                    <span><strong>Mã:</strong> {{ $project->project_code }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-user-tag"></i>
                    <span><strong>Leader:</strong> {{ $leader->email }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-chart-line"></i>
                    <span><strong>Trạ thái:</strong> {{ $project->status }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-tasks"></i>
                    <span><strong>Số task:</strong> {{ $project->tasks->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card info-card">
                <h5><i class="fas fa-users me-2"></i>Thành viên</h5>
                @php
                    $members = $project->users->where('id', '!=', $project->leader);
                @endphp

                @if($members->count() > 0)
                    @foreach($members as $u)
                        <div class="member-item">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ $u->email }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Chưa có thành viên</p>
                @endif

                @if(auth()->id() == $project->leader)
                    <form method="POST" action="{{ route('projects.addMember', $project->id) }}" class="d-flex gap-2 mt-3">
                        @csrf
                        <input type="email" name="email" class="form-control" placeholder="Nhập email thành viên" required>
                        <button class="btn btn-primary">Thêm</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="card task-form-card mb-4">
        <h5><i class="fas fa-plus-circle me-2"></i>Thêm công việc mới</h5>
        <form method="POST" action="{{ route('projects.addTask', $project->id) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Tên công việc" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="assigned_to" class="form-control" placeholder="Người làm" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="estimated_hours" class="form-control" placeholder="Số giờ" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="cost" class="form-control" placeholder="Chi phí (VNĐ)" required>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control">
                        <option value="Chưa làm">Chưa làm</option>
                        <option value="Đang làm">Đang làm</option>
                        <option value="Hoàn thành">Hoàn thành</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="col-md-6">
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn add-task-btn text-white">
                        <i class="fas fa-plus me-2"></i>Thêm Task
                    </button>
                </div>
            </div>
        </form>
    </div>

    <h4 class="mb-3" style="font-weight: 600;"><i class="fas fa-tasks me-2"></i>Danh sách công việc</h4>
    <div class="row g-4">
        @foreach($project->tasks as $t)
            <div class="col-md-6 col-lg-4">
                <div class="card task-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="task-title mb-0">{{ $t->name }}</h5>
                            @if($t->status == 'Hoàn thành')
                                <span class="badge status-completed">Hoàn thành</span>
                            @elseif($t->status == 'Đang làm')
                                <span class="badge status-in-progress">Đang làm</span>
                            @else
                                <span class="badge status-pending">Chưa làm</span>
                            @endif
                        </div>
                        
                        <div class="task-info">
                            <span><i class="fas fa-user me-1"></i> {{ $t->assigned_to }}</span>
                        </div>
                        <div class="task-info">
                            <span><i class="fas fa-clock me-1"></i> {{ $t->estimated_hours }} giờ</span>
                        </div>
                        <div class="task-info">
                            <span><i class="fas fa-money-bill me-1"></i> {{ number_format($t->cost) }} VNĐ</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $t->id }}">
                            <i class="fas fa-trash me-1"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal{{ $t->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc muốn xóa task <strong>{{ $t->name }}</strong> không?
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form method="POST" action="{{ route('tasks.delete', $t->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection