@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">My Assignments</h1>
    <a href="{{ route('teacher.assignments.create') }}" class="btn btn-success mb-3">Create Assignment</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ $assignment->class->name ?? '-' }}</td>
                    <td>{{ $assignment->subject->name ?? '-' }}</td>
                    <td>{{ $assignment->due_date }}</td>
                    <td>
                        <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this assignment?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No assignments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 