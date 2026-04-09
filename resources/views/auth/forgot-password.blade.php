@extends('layouts.app')

@section('title', 'Forgot Password - IT Project Management')

@section('content')
<div class="container-main">
    <div class="auth-container">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-key" style="font-size: 3rem; color: #3498db;"></i>
                    <h2 class="mt-3" style="color: #2c3e50;">Reset Password</h2>
                    <p class="text-muted">Enter your email to receive a password reset link</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle"></i> Error!</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="/forgot-password" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                        <i class="fas fa-envelope"></i> Send Reset Link
                    </button>
                </form>

                <div class="text-center">
                    <a href="/login" class="forgot-link">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
