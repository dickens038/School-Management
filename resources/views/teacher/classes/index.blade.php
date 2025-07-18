@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Manage Classes</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Classes</h5>
                    <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">Add New Class</a>
                </div>
                <div class="card-body">
                    @if($classes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th>Students</th>
                                        <th>Subjects</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classes as $class)
                                        <tr>
                                            <td>{{ $class->name }}</td>
                                            <td>
                                                @if($class->students->count() > 0)
                                                    @foreach($class->students->take(3) as $student)
                                                        <span class="badge bg-info">{{ $student->user->name ?? 'N/A' }}</span>
                                                    @endforeach
                                                    @if($class->students->count() > 3)
                                                        <span class="badge bg-secondary">+{{ $class->students->count() - 3 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No students</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($class->subjects->count() > 0)
                                                    @foreach($class->subjects->take(3) as $subject)
                                                        <span class="badge bg-success">{{ $subject->name }}</span>
                                                    @endforeach
                                                    @if($class->subjects->count() > 3)
                                                        <span class="badge bg-secondary">+{{ $class->subjects->count() - 3 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No subjects</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('teacher.classes.edit', $class->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('teacher.classes.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?')">
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
                            <p class="text-muted">No classes assigned to you.</p>
                            <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">Create Your First Class</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 