@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Attendance Management</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($classes->count() > 0)
        @foreach($classes as $class)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $class->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        
                        @if($class->students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Present</th>
                                            <th>Absent</th>
                                            <th>Late</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($class->students as $student)
                                            <tr>
                                                <td>{{ $student->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="form-check-input" checked>
                                                </td>
                                                <td>
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="form-check-input">
                                                </td>
                                                <td>
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="form-check-input">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary">Mark Attendance</button>
                        @else
                            <p class="text-muted">No students assigned to this class.</p>
                        @endif
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">No classes assigned to you.</p>
                <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary">Manage Classes</a>
            </div>
        </div>
    @endif
</div>
@endsection 