@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Student Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $student->user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $student->user->email }}</p>
            <p class="card-text"><strong>Admission Number:</strong> {{ $student->admission_number }}</p>
            <p class="card-text"><strong>Class:</strong> {{ $student->class ? $student->class->name : '-' }}</p>
            <p class="card-text"><strong>Date of Birth:</strong> {{ $student->date_of_birth }}</p>
            <p class="card-text"><strong>Gender:</strong> {{ $student->gender }}</p>
            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 