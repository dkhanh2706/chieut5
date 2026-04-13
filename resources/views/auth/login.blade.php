@extends('layouts.app')

@section('title', 'Đăng nhập - IT Project Management')

@section('content')
<div class="container-main">
    <div class="auth-card fade-in-up">
        <div class="card-body">
            <div class="auth-header">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Chào mừng trở lại</h2>
                <p>Đăng nhập vào tài khoản của bạn</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Đăng nhập thất bại!</strong>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Địa chỉ email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="name@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required
                           placeholder="Nhập mật khẩu">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-4">
                    <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                </button>
            </form>

            <div class="auth-footer">
                <a href="/forgot-password" class="forgot-link">Quên mật khẩu?</a>
            </div>

            <hr class="my-4">

            <p class="text-center mb-0">
                Chưa có tài khoản? 
                <a href="/register" class="forgot-link">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>
@endsection