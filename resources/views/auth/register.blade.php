@extends('layouts.app')

@section('title', 'Đăng ký - IT Project Management')

@section('content')
<div class="container-main">
    <div class="auth-card fade-in-up">
        <div class="card-body">
            <div class="auth-header">
                <i class="fas fa-user-plus"></i>
                <h2>Tạo tài khoản</h2>
                <p>Tham gia cùng chúng tôi</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Đăng ký thất bại!</strong>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="/register" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Nguyễn Văn A">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Địa chỉ email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required
                           placeholder="name@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required
                           placeholder="Tối thiểu 8 ký tự">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" name="password_confirmation" required
                           placeholder="Nhập lại mật khẩu">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-4">
                    <i class="fas fa-user-plus me-2"></i> Tạo tài khoản
                </button>
            </form>

            <hr class="my-4">

            <p class="text-center mb-0">
                Đã có tài khoản? 
                <a href="/login" class="forgot-link">Đăng nhập ngay</a>
            </p>
        </div>
    </div>
</div>
@endsection