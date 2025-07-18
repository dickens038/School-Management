@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Subject Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $subject->name }}</h5>
            <p class="card-text"><strong>Class:</strong> {{ $subject->class ? $subject->class->name : '-' }}</p>
            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 