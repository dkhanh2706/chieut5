@extends('layouts.app')

@section('title', 'Tạo dự án')

@section('content')

<div class="auth-container">
    <div class="card">
        <div class="card-body p-5">

            <h2 class="mb-4 text-center">Tạo dự án mới</h2>

            {{-- 🔥 LỖI CUSTOM (TRÙNG MÃ) --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 🔥 LỖI VALIDATE --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Mã dự án</label>
                    <input type="text" name="project_code" class="form-control" value="{{ old('project_code') }}" required>
                    @error('project_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Tên dự án</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Ngày bắt đầu</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Ngày kết thúc</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="Chưa bắt đầu" {{ old('status') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                        <option value="Đang làm" {{ old('status') == 'Đang làm' ? 'selected' : '' }}>Đang làm</option>
                        <option value="Hoàn thành" {{ old('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button class="btn btn-primary w-100">
                    Tạo dự án
                </button>

            </form>

        </div>
    </div>
</div>

@endsection