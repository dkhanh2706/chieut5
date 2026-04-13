@extends('layouts.app')

@section('title', 'Danh sách dự án - IT Project Management')

@section('css')
<style>
    .page-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
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

    .project-card {
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: white;
        overflow: hidden;
    }

    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: #c4b5fd;
    }

    .project-card .card-body {
        padding: 1.5rem;
    }

    .project-code-display {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #4f46e5;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 1rem;
        display: inline-block;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .project-code-display:hover {
        background: linear-gradient(135deg, #c7d2fe 0%, #a5b4fc 100%);
    }

    .project-title {
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0.75rem 0;
        color: #1e293b;
    }

    .project-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .project-info i {
        width: 16px;
        color: #6366f1;
    }

    .status-badge {
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .member-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .member-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #1e293b;
        font-weight: 600;
    }

    .empty-state p {
        color: #64748b;
    }

    .access-modal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .access-modal .modal-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: white;
        border: none;
        padding: 1.5rem;
    }

    .access-modal .modal-header .btn-close {
        filter: invert(1);
    }

    .access-modal .modal-body {
        padding: 2rem;
    }

    .code-box {
        background: #f1f5f9;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin: 1.5rem 0;
    }

    .code-box label {
        display: block;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .code-box .code-text {
        font-family: 'Courier New', monospace;
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.25em;
    }

    .enter-code-input {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        font-size: 1.25rem;
        text-align: center;
        letter-spacing: 0.25em;
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }

    .enter-code-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        outline: none;
    }

    .action-btn {
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .success-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #059669;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }
</style>
@endsection

@section('content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-folder-tree me-2"></i>Danh sách dự án</h1>
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

    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-4">
        <i class="fas fa-plus me-2"></i>Tạo dự án mới
    </a>

    @if($projects->count() > 0)
        <div class="row g-4">
            @foreach($projects as $p)
                <div class="col-md-6 col-lg-4">
                    <div class="card project-card h-100" data-bs-toggle="modal" data-bs-target="#accessModal{{ $p->id }}" style="cursor: pointer;">
                        <div class="card-body">
                            <span class="project-code-display" onclick="event.stopPropagation(); copyCode('{{ $p->project_code }}')">
                                <i class="fas fa-copy me-2"></i>{{ $p->project_code }}
                            </span>
                            <h5 class="project-title">{{ $p->name }}</h5>
                            
                            <div class="project-info">
                                <i class="fas fa-user"></i>
                                <span>Leader: {{ $p->leader }}</span>
                            </div>
                            
                            <div class="project-info">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $p->start_date }} → {{ $p->end_date }}</span>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                @if($p->status == 'Hoàn thành')
                                    <span class="status-badge bg-success text-white">Hoàn thành</span>
                                @elseif($p->status == 'Đang làm')
                                    <span class="status-badge" style="background: #fef3c7; color: #b45309;">Đang làm</span>
                                @else
                                    <span class="status-badge bg-secondary text-white">Chưa bắt đầu</span>
                                @endif

                                <div class="member-list">
                                    @foreach($p->users->take(3) as $u)
                                        <div class="member-avatar" title="{{ $u->email }}">
                                            {{ strtoupper(substr($u->email, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($p->users->count() > 3)
                                        <div class="member-avatar" style="background: #94a3b8;">
                                            +{{ $p->users->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade access-modal" id="accessModal{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-key me-2"></i>Xác nhận truy cập
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p class="mb-2">Nhập mã dự án để truy cập</p>
                                <h4 class="mb-3" style="color: #1e293b;">{{ $p->name }}</h4>
                                
                                <div class="code-box">
                                    <label>Mã truy cập</label>
                                    <div class="code-text">{{ $p->project_code }}</div>
                                </div>

                                <form action="{{ url('/projects/access') }}" method="GET">
                                    <div class="mb-3">
                                        <input type="text" name="code" class="form-control enter-code-input" 
                                               placeholder="Nhập mã..." required autocomplete="off">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 action-btn">
                                        <i class="fas fa-arrow-right me-2"></i>Truy cập dự án
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>Chưa có dự án nào</h3>
                <p>Tạo dự án đầu tiên của bạn ngay bây giờ!</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>Tạo dự án
                </a>
            </div>
        </div>
    @endif

</div>

<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            alert('Đã copy mã: ' + code);
        });
    }
</script>

@endsection