@extends('layouts.app')

@push('auth-styles')
<style>
    body.login-bg {
        background: #e3f0ff !important;
        min-height: 100vh;
    }
    .login-card {
        border: 2px solid #b3d8fd;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(79, 140, 255, 0.10);
        background: #fff;
        backdrop-filter: blur(6px);
    }
    .login-logo {
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
        color: #4f8cff;
    }
    .login-title {
        font-weight: 700;
        font-size: 2rem;
        letter-spacing: 1px;
        color: #2563eb;
    }
    .login-welcome {
        color: #4f8cff;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .login-form .form-control {
        border-radius: 0.75rem;
        font-size: 1.1rem;
        border: 1.5px solid #b3d8fd;
        background: #f4faff;
    }
    .login-form .form-control:focus {
        border-color: #4f8cff;
        box-shadow: 0 0 0 0.2rem rgba(79, 140, 255, 0.15);
    }
    .login-form .btn-primary {
        border-radius: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        background: #4f8cff;
        border: none;
        box-shadow: 0 2px 8px 0 rgba(79, 140, 255, 0.10);
    }
    .login-form .btn-primary:hover {
        background: #2563eb;
    }
    .login-links a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }
    .login-links a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card login-card p-4" style="max-width: 420px; width: 100%;">
            <div class="text-center mb-4">
                <svg class="login-logo" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l9 6-9 6-9-6 9-6zm0 6v12"/></svg>
                <div class="login-title">Manyama High School</div>
                <div class="login-welcome">Welcome back! Please sign in to your account.</div>
            </div>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required autofocus value="{{ old('email') }}">
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="login-links mt-4 text-center">
                <a href="{{ route('register') }}">Don't have an account? Register</a>
            </div>
        </div>
    </div>
</div>
@endsection 