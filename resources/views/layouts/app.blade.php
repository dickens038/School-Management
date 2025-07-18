<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manyama High School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
        }
        .school-logo {
            width: 32px;
            height: 32px;
            margin-right: 8px;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            letter-spacing: 1px;
        }
    </style>
    @stack('auth-styles')
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.pathname === '/login' || window.location.pathname === '/register') {
                document.body.classList.add('login-bg');
            }
        });
    </script>
    @if(!request()->routeIs('home') && !request()->routeIs('register') && !request()->routeIs('login'))
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <svg class="school-logo" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l9 6-9 6-9-6 9-6zm0 6v12"/></svg>
                Manyama High School
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-dark" href="/">Home</a>
                    </li>
                    @if(auth()->check() && in_array(auth()->user()->role->name ?? '', ['headmaster', 'it', 'it_department', 'it-staff', 'it_department_user']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="schoolMgmtDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                School Management
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="schoolMgmtDropdown">
                                <li><a class="dropdown-item" href="/students">Students</a></li>
                                <li><a class="dropdown-item" href="/teachers">Teachers</a></li>
                                <li><a class="dropdown-item" href="/classes">Classes</a></li>
                                <li><a class="dropdown-item" href="/subjects">Subjects</a></li>
                                <li><a class="dropdown-item" href="/assignments">Assignments</a></li>
                                <li><a class="dropdown-item" href="/results">Results</a></li>
                                <li><a class="dropdown-item" href="/users">Users</a></li>
                                <li><a class="dropdown-item" href="/departments">Departments</a></li>
                            </ul>
                        </li>
                        @if(in_array(auth()->user()->role->name ?? '', ['it', 'it_department', 'it-staff', 'it_department_user']))
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-dark" href="/admin/settings">System Settings</a>
                            </li>
                        @endif
                    @endif
                    @if(auth()->check() && (auth()->user()->role->name ?? '') === 'student')
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-dark" href="/dashboard/student">Student Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-dark" href="/results/student">Results</a>
                        </li>
                    @endif
                    @if(auth()->check() && (auth()->user()->role->name ?? '') === 'teacher')
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-dark" href="/dashboard/teacher">Teacher Dashboard</a>
                        </li>
                    @endif
                </ul>
                @if(auth()->check())
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role->name ?? '') }})
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/profile">View/Edit Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item ms-2">
                        <form method="POST" action="/logout" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </nav>
    @endif
    <main>
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 