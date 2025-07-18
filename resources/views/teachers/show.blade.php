@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Teacher Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $teacher->user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $teacher->user->email }}</p>
            <p class="card-text"><strong>Employee Number:</strong> {{ $teacher->employee_number }}</p>
            <p class="card-text"><strong>Department:</strong> {{ $teacher->department ?? '-' }}</p>
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 