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
                <div class="login-welcome">Create your account to access the system.</div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" required autofocus value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label for="register_as" class="form-label">Register as</label>
                    <select name="register_as" id="register_as" class="form-select" required>
                        <option value="student" selected>Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="login-links mt-4 text-center">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </div>
    </div>
</div>
@endsection 