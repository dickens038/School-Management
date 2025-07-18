@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Manage Students</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Students</h5>
                    <a href="{{ route('teacher.students.create') }}" class="btn btn-primary">Add New Student</a>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Admission Number</th>
                                        <th>Email</th>
                                        <th>Class</th>
                                        <th>Gender</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                                            <td>{{ $student->admission_number }}</td>
                                            <td>{{ $student->user->email ?? 'N/A' }}</td>
                                            <td>{{ $student->class->name ?? 'N/A' }}</td>
                                            <td>{{ ucfirst($student->gender ?? 'N/A') }}</td>
                                            <td>
                                                <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this student?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No students found in your classes.</p>
                            <a href="{{ route('teacher.students.create') }}" class="btn btn-primary">Add Your First Student</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 