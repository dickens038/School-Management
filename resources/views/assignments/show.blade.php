@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assignment Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $assignment->title }}</h5>
            <p class="card-text"><strong>Class:</strong> {{ $assignment->class ? $assignment->class->name : '-' }}</p>
            <p class="card-text"><strong>Subject:</strong> {{ $assignment->subject ? $assignment->subject->name : '-' }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $assignment->description }}</p>
            <p class="card-text"><strong>Due Date:</strong> {{ $assignment->due_date }}</p>
            <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 