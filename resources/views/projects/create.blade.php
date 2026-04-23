@extends('layouts.app')

@section('title', 'Tạo dự án - IT Project Management')

@section('css')
<style>
    .form-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--radius-xl);
        margin-bottom: 2rem;
    }

    .form-header h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
    }

    .form-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .form-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-group .form-control {
        border: 2px solid #e2e8f0;
        border-radius: var(--radius);
        padding: 0.75rem 1rem;
    }

    .form-group .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    select.form-control {
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="form-header fade-in-up">
                <h1><i class="fas fa-folder-plus me-2"></i>Tạo dự án mới</h1>
                <p>Điền thông tin để tạo dự án mới</p>
            </div>

            <div class="card form-card">
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Có lỗi xảy ra!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã dự án</label>
                                    <input type="text" name="project_code" class="form-control" 
                                           value="{{ old('project_code') }}" required
                                           placeholder="VD: PRJ001">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên dự án</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name') }}" required
                                           placeholder="VD: Website bán hàng">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Mô tả dự án</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Mô tả ngắn về dự án">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày bắt đầu</label>
                                    <input type="date" name="start_date" class="form-control" 
                                           value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ước lượng ngày kết thúc</label>
                                    <input type="date" name="end_date" class="form-control" 
                                           value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="Chưa bắt đầu" {{ old('status') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                                <option value="Đang làm" {{ old('status') == 'Đang làm' ? 'selected' : '' }}>Đang làm</option>
                            </select>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fas fa-save me-2"></i>Lưu dự án
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection