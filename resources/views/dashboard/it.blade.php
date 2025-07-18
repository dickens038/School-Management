@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">IT Department Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Departments</h5>
                    <p class="card-text">{{ \App\Models\Department::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">System Settings</h5>
                    <p class="card-text">Configure system preferences</p>
                    <a href="/admin/settings" class="btn btn-light btn-sm mt-2">Go to System Settings</a>
                </div>
            </div>
        </div>
    </div>
    <div class="list-group mb-4">
        <a href="/users" class="list-group-item list-group-item-action">Manage Users</a>
        <a href="/departments" class="list-group-item list-group-item-action">Manage Departments</a>
        <a href="/admin/settings" class="list-group-item list-group-item-action">System Settings</a>
        <a href="{{ route('admin.admissions.index') }}" class="list-group-item list-group-item-action">Student Admissions</a>
        <a href="{{ route('admin.teacher-class-assignments.index') }}" class="list-group-item list-group-item-action">Teacher-Class Assignments</a>
        <a href="{{ route('admin.school-performance.index') }}" class="list-group-item list-group-item-action">School Performance</a>
        <a href="#" class="list-group-item list-group-item-action disabled">Support Tools (Coming soon)</a>
    </div>
    <div class="alert alert-info">Welcome, IT Department! Here you can manage users and provide technical support.</div>
</div>
@endsection 