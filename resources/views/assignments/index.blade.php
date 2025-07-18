@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assignments</h1>
    <a href="{{ route('assignments.create') }}" class="btn btn-primary mb-3">Add Assignment</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->id }}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ $assignment->class ? $assignment->class->name : '-' }}</td>
                    <td>{{ $assignment->subject ? $assignment->subject->name : '-' }}</td>
                    <td>{{ $assignment->due_date }}</td>
                    <td>
                        <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $assignments->links() }}
</div>
@endsection 