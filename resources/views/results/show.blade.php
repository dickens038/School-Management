@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Result Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Result</h5>
            <p class="card-text"><strong>Student:</strong> {{ $result->student && $result->student->user ? $result->student->user->name : '-' }}</p>
            <p class="card-text"><strong>Subject:</strong> {{ $result->subject ? $result->subject->name : '-' }}</p>
            <p class="card-text"><strong>Score:</strong> {{ $result->score }}</p>
            <p class="card-text"><strong>Term:</strong> {{ $result->term }}</p>
            <a href="{{ route('results.edit', $result) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('results.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 