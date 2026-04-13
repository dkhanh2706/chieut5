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
        font-size: 1.75rem;
        font-weight: 700;
    }

    .trash-card {
        border-radius: var(--radius-lg);
        transition: all 0.3s ease;
    }

    .trash-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .trash-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .trash-info {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .btn-restore {
        background: var(--success);
        border: none;
    }

    .btn-restore:hover {
        background: #059669;
    }

    .btn-force-delete {
        background: var(--danger);
    }

    .btn-force-delete:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('project-content')

<div class="container-fluid p-4">

    <div class="page-header fade-in-up">
        <h1><i class="fas fa-trash me-2"></i>Thùng rác</h1>
        <p class="mt-2" style="opacity: 0.9;">Các task đã bị xóa</p>
    </div>

    @if($tasks->count() > 0)
        <div class="row g-4">
            @foreach($tasks as $t)
                <div class="col-md-6 col-lg-4">
                    <div class="card trash-card h-100">
                        <div class="card-body">
                            <h5 class="trash-title">{{ $t->name }}</h5>
                            <p class="trash-info mb-0">
                                <i class="fas fa-user me-1"></i> {{ $t->assigned_to }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex gap-2">
                            <form method="POST" action="{{ route('tasks.restore', $t->id) }}" class="flex-fill">
                                @csrf
                                <button class="btn btn-restore btn-sm w-100 text-white">
                                    <i class="fas fa-rotate-left me-1"></i> Khôi phục
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tasks.forceDelete', $t->id) }}" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-force-delete btn-sm w-100 text-white">
                                    <i class="fas fa-trash me-1"></i> Xóa luôn
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body empty-state">
                <i class="fas fa-trash-can"></i>
                <h4>Thùng rác trống</h4>
                <p>Không có task nào bị xóa</p>
            </div>
        </div>
    @endif

</div>

@endsection