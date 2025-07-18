@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Welcome, {{ $teacher->user->name }}</h1>
    <div class="mb-4">
        <div class="nav nav-pills flex-wrap gap-2">
            <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary">Assign Assignment</a>
            <a href="{{ route('teacher.results.create') }}" class="btn btn-success">Send Results to IT</a>
            <a href="{{ route('teacher.results.feedback') }}" class="btn btn-warning">Results Feedback</a>
            <a href="{{ route('teacher.students.index') }}" class="btn btn-info">Manage Students</a>
            <a href="{{ route('teacher.admissions.index') }}" class="btn btn-success">Student Admissions</a>
            <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">Tick Attendance</a>
            <a href="{{ route('teacher.reports.index') }}" class="btn btn-dark">Prepare/View Reports</a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Profile</h5>
                    <p><strong>Email:</strong> {{ $teacher->user->email }}</p>
                    <p><strong>Employee #:</strong> {{ $teacher->employee_number }}</p>
                    <p><strong>Department:</strong> {{ $teacher->department ?? '-' }}</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Awards Given</h5>
                    @forelse($teacher->awards as $award)
                        <div class="mb-2">
                            <strong>{{ $award->title }}</strong><br>
                            <small>{{ $award->description }}</small><br>
                            <span class="text-muted">To {{ $award->student->user->name ?? 'N/A' }}</span>
                        </div>
                    @empty
                        <p>No awards given yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Classes & Subjects</h5>
                    @forelse($teacher->classes ?? [] as $class)
                        <div class="mb-2">
                            <strong>{{ $class->name }}</strong>:
                            @foreach($class->subjects as $subject)
                                <span class="badge bg-info">{{ $subject->name }}</span>
                            @endforeach
                        </div>
                    @empty
                        <p>No classes assigned.</p>
                    @endforelse
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Students</h5>
                    @forelse($teacher->classes ?? [] as $class)
                        <div class="mb-2">
                            <strong>{{ $class->name }}</strong>:
                            @foreach($class->students as $student)
                                <span class="badge bg-secondary">{{ $student->user->name }}</span>
                            @endforeach
                        </div>
                    @empty
                        <p>No students assigned.</p>
                    @endforelse
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Assignments</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($teacher->assignments ?? [] as $assignment)
                            <li class="list-group-item">
                                <strong>{{ $assignment->title }}</strong> ({{ $assignment->subject->name ?? '-' }})<br>
                                Due: {{ $assignment->due_date }}
                                <div class="text-muted small">{{ $assignment->description }}</div>
                            </li>
                        @empty
                            <li class="list-group-item">No assignments created.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Results Entered</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($teacher->results ?? [] as $result)
                            <li class="list-group-item">
                                <strong>{{ $result->student->user->name ?? '-' }}</strong> - {{ $result->subject->name ?? '-' }}: {{ $result->score }} ({{ ucfirst($result->term) }})
                            </li>
                        @empty
                            <li class="list-group-item">No results entered.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Comments Given</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($teacher->comments ?? [] as $comment)
                            <li class="list-group-item">
                                <strong>{{ $comment->student->user->name ?? '-' }}:</strong> {{ $comment->comment }}
                            </li>
                        @empty
                            <li class="list-group-item">No comments given.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 