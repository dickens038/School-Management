@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Results</h1>
    <a href="{{ route('results.create') }}" class="btn btn-primary mb-3">Add Result</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Subject</th>
                <th>Score</th>
                <th>Term</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->id }}</td>
                    <td>{{ $result->student && $result->student->user ? $result->student->user->name : '-' }}</td>
                    <td>{{ $result->subject ? $result->subject->name : '-' }}</td>
                    <td>{{ $result->score }}</td>
                    <td>{{ $result->term }}</td>
                    <td>
                        <a href="{{ route('results.show', $result) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('results.edit', $result) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('results.destroy', $result) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $results->links() }}
</div>
@endsection 