@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Headmaster Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text">{{ \App\Models\Student::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Teachers</h5>
                    <p class="card-text">{{ \App\Models\Teacher::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Departments</h5>
                    <p class="card-text">{{ \App\Models\Department::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Classes</h5>
                    <p class="card-text">{{ \App\Models\SchoolClass::count() }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="list-group mb-4">
        <a href="/users" class="list-group-item list-group-item-action">Manage Users</a>
        <a href="/departments" class="list-group-item list-group-item-action">Manage Departments</a>
        <a href="/students" class="list-group-item list-group-item-action">Manage Students</a>
        <a href="/teachers" class="list-group-item list-group-item-action">Manage Teachers</a>
        <a href="/classes" class="list-group-item list-group-item-action">Manage Classes</a>
        <a href="/subjects" class="list-group-item list-group-item-action">Manage Subjects</a>
        <a href="/assignments" class="list-group-item list-group-item-action">Manage Assignments</a>
        <a href="/results" class="list-group-item list-group-item-action">Manage Results</a>
        <a href="{{ route('admin.admissions.index') }}" class="list-group-item list-group-item-action">Student Admissions</a>
        <a href="{{ route('admin.teacher-class-assignments.index') }}" class="list-group-item list-group-item-action">Teacher-Class Assignments</a>
        <a href="{{ route('admin.school-performance.index') }}" class="list-group-item list-group-item-action">School Performance</a>
    </div>
    <div class="alert alert-info">Welcome, Headmaster! Here you can manage the entire school system.</div>
</div>
@endsection 