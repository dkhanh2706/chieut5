@extends('layouts.app')

@section('css')
<style>
    .project-layout {
        display: flex;
        min-height: calc(100vh - 70px);
        background: #f8fafc;
    }

    .sidebar {
        width: 220px;
        background: white;
        color: white;
        padding: 0;
        min-height: 100vh;
        position: sticky;
        top: 70px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #e2e8f0;
    }

    .sidebar-project {
        padding: 1.5rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .sidebar-project-name {
        margin: 0;
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .sidebar-project-code {
        font-size: 0.7rem;
        color: #6366f1;
        font-family: 'Courier New', monospace;
        margin-top: 0.35rem;
        display: block;
        font-weight: 600;
    }

    .sidebar-nav {
        list-style: none;
        padding: 1rem 0.75rem;
        margin: 0;
    }

    .sidebar-nav li {
        margin-bottom: 0.25rem;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #64748b;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .sidebar-nav a:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .sidebar-nav a.active {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
    }

    .sidebar-nav a.active i {
        color: #6366f1;
    }

    .sidebar-nav a i {
        width: 18px;
        text-align: center;
        font-size: 0.9rem;
        color: #94a3b8;
    }

    .sidebar-nav a:hover i {
        color: #64748b;
    }

    .sidebar-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0.5rem 0.75rem;
    }

    .sidebar-footer {
        padding: 0.75rem;
        margin-top: auto;
        border-top: 1px solid #f1f5f9;
    }

    .sidebar-danger a {
        color: #ef4444;
    }

    .sidebar-danger a:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .sidebar-toggle {
        display: none;
    }

    .sidebar-backdrop {
        display: none;
    }

    @media (max-width: 991px) {
        .sidebar {
            position: fixed;
            left: -100%;
            top: 70px;
            z-index: 1000;
            transition: left 0.3s ease;
            width: 220px;
        }

        .sidebar.show {
            left: 0;
        }

        .sidebar-toggle {
            display: flex;
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            z-index: 1001;
            cursor: pointer;
            font-size: 1.1rem;
            align-items: center;
            justify-content: center;
        }

        .sidebar-backdrop.show {
            display: block;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-backdrop {
            display: none;
        }
    }

    .project-content {
        flex: 1;
        min-width: 0;
    }

    .sidebar-nav a[data-bs-toggle="modal"] {
        color: #64748b;
    }

    .sidebar-nav a[data-bs-toggle="modal"]:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    #supportModal .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    #supportModal .modal-body {
        padding: 1.5rem;
    }

    #supportModal .form-select,
    #supportModal .form-control,
    #supportModal textarea {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }

    #supportModal .form-select:focus,
    #supportModal .form-control:focus,
    #supportModal textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    #supportModal .btn-primary {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
    }
</style>
@endrection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        const backdrop = document.querySelector('.sidebar-backdrop');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });
            
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        }
    });
</script>
@endsection

@section('content')

<div class="project-layout">
    <div class="sidebar-backdrop"></div>

    <aside class="sidebar">
        <div class="sidebar-project">
            <h4 class="sidebar-project-name">{{ $project->name }}</h4>
            <span class="sidebar-project-code">{{ $project->project_code }}</span>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('projects.show', $project->id) }}" class="{{ request()->is('projects/' . $project->id) && !request()->is('*/trash*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Tổng quan
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-list-check"></i> Công việc
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-users"></i> Thành viên
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-calendar"></i> Lịch trình
                </a>
            </li>
            <li>
                <a href="#" data-bs-toggle="modal" data-bs-target="#supportModal">
                    <i class="fas fa-headset"></i> Liên hệ hỗ trợ
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>

        <ul class="sidebar-nav">
            <li class="sidebar-footer">
                <a href="{{ route('projects.trash', $project->id) }}" class="{{ request()->is('*/trash*') ? 'active' : '' }}">
                    <i class="fas fa-trash"></i> Thùng rác
                </a>
            </li>
        </ul>
    </aside>

    <main class="project-content">
        @yield('project-content')
    </main>

    <button class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="modal fade" id="supportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-headset me-2"></i>Liên hệ hỗ trợ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Vấn đề cần hỗ trợ</label>
                        <select class="form-select">
                            <option value="">Chọn loại vấn đề...</option>
                            <option value="bug">Báo lỗi hệ thống</option>
                            <option value="help">Cần hướng dẫn sử dụng</option>
                            <option value="feature">Đề xuất tính năng</option>
                            <option value="other">Vấn đề khác</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" rows="4" placeholder="Mô tả vấn đề của bạn..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email liên hệ</label>
                        <input type="email" class="form-control" placeholder="Nhập email của bạn">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection