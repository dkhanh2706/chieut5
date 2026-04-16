@extends('layouts.app')

@section('title', 'Dashboard - IT Project Management')

@section('css')
<style>
    .dashboard-wrapper {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .welcome-section {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
        color: white;
        padding: 3rem;
        border-radius: 24px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-section::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.85;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .welcome-user {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255,255,255,0.15);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        margin-top: 1.5rem;
        backdrop-filter: blur(10px);
    }

    .welcome-user-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .action-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .action-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: #e0e7ff;
    }

    .action-card:hover::before {
        opacity: 1;
    }

    .action-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }

    .action-icon-purple {
        background: linear-gradient(135deg, #eef2ff 0%, #c7d2fe 100%);
        color: #4f46e5;
    }

    .action-icon-green {
        background: linear-gradient(135deg, #ecfdf5 0%, #a7f3d0 100%);
        color: #059669;
    }

    .action-icon-orange {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #d97706;
    }

    .action-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1e293b;
    }

    .action-desc {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .btn-action {
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateX(4px);
    }

    .quick-actions {
        background: white;
        border-radius: 20px;
        padding: 2rem;
    }

    .quick-actions h3 {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 0.5rem;
        cursor: pointer;
    }

    .quick-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .quick-link i {
        width: 24px;
        text-align: center;
        color: #6366f1;
    }

    .quick-link span {
        font-weight: 500;
    }

    .support-modal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .support-modal .modal-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: white;
        border: none;
    }

    .support-modal .modal-header .btn-close {
        filter: invert(1);
    }

    .support-modal .modal-body {
        padding: 2rem;
    }

    .support-modal .form-label {
        font-weight: 600;
        color: #374151;
    }

    .support-modal .form-select,
    .support-modal .form-control,
    .support-modal textarea {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
    }

    .support-modal .form-select:focus,
    .support-modal .form-control:focus,
    .support-modal textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .support-modal .btn-primary {
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .welcome-title {
            font-size: 1.75rem;
        }
        
        .dashboard-wrapper {
            padding: 1rem;
        }

        .action-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-wrapper">

    <div class="welcome-section fade-in-up">
        <h1 class="welcome-title">IT Project Management</h1>
        <p class="welcome-subtitle">Quản lý công việc và dự án một cách thông minh</p>
        
        <div class="welcome-user">
            <div class="welcome-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
            </div>
            <span>{{ auth()->user()->name ?? 'Guest' }}</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="action-card">
                <div class="action-icon action-icon-purple">
                    <i class="fas fa-plus"></i>
                </div>
                <h3 class="action-title">Tạo dự án mới</h3>
                <p class="action-desc">Bắt đầu một dự án mới và quản lý công việc hiệu quả</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-action">
                    <i class="fas fa-arrow-right"></i>
                    Tạo ngay
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="action-card">
                <div class="action-icon action-icon-green">
                    <i class="fas fa-key"></i>
                </div>
                <h3 class="action-title">Truy cập dự án</h3>
                <p class="action-desc">Nhập mã để truy cập nhanh vào dự án có sẵn</p>
                <form method="GET" action="#" class="d-flex gap-2">
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã dự án...">
                    <button type="submit" class="btn btn-success btn-action">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-lg-6">
            <div class="quick-actions">
                <h3><i class="fas fa-bolt me-2" style="color: #f59e0b;"></i>Truy cập nhanh</h3>
                <a href="{{ route('projects.calendar') }}" class="quick-link">
                    <i class="fas fa-calendar"></i>
                    <span>Lịch trình dự án</span>
                </a>
                <a href="{{ route('projects.index') }}" class="quick-link">
                <a href="#" class="quick-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Thống kê</span>
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="quick-actions">
                <h3><i class="fas fa-circle-question me-2" style="color: #6366f1;"></i>Trợ giúp</h3>
                <a href="#" class="quick-link">
                    <i class="fas fa-book"></i>
                    <span>Tài liệu hướng dẫn</span>
                </a>
                <a href="#" class="quick-link" data-bs-toggle="modal" data-bs-target="#supportModal">
                    <i class="fas fa-headset"></i>
                    <span>Liên hệ hỗ trợ</span>
                </a>
            </div>
        </div>
    </div>

</div>

<div class="modal fade support-modal" id="supportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-headset me-2"></i>Liên hệ hỗ trợ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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