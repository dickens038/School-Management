@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Welcome, {{ $student->user->name }}</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Profile</h5>
                    <p><strong>Admission #:</strong> {{ $student->admission_number }}</p>
                    <p><strong>Email:</strong> {{ $student->user->email }}</p>
                    <p><strong>Class:</strong> {{ $student->class->name ?? '-' }}</p>
                    <p><strong>Date of Birth:</strong> {{ $student->date_of_birth }}</p>
                    <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Awards</h5>
                    @forelse($student->awards as $award)
                        <div class="mb-2">
                            <strong>{{ $award->title }}</strong><br>
                            <small>{{ $award->description }}</small><br>
                            <span class="text-muted">By {{ $award->teacher->user->name ?? 'N/A' }}</span>
                        </div>
                    @empty
                        <p>No awards yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Subjects</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($student->class->subjects ?? [] as $subject)
                            <li class="list-group-item">{{ $subject->name }}</li>
                        @empty
                            <li class="list-group-item">No subjects assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Assignments</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($student->class->assignments ?? [] as $assignment)
                            <li class="list-group-item">
                                <strong>{{ $assignment->title }}</strong> ({{ $assignment->subject->name ?? '-' }})<br>
                                Due: {{ $assignment->due_date }}
                                <div class="text-muted small">{{ $assignment->description }}</div>
                            </li>
                        @empty
                            <li class="list-group-item">No assignments.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Results</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Score</th>
                                <th>Term</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->results as $result)
                                <tr>
                                    <td>{{ $result->subject->name ?? '-' }}</td>
                                    <td>{{ $result->score }}</td>
                                    <td>{{ $result->term }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No results yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Teacher Comments</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($student->comments as $comment)
                            <li class="list-group-item">
                                <strong>{{ $comment->teacher->user->name ?? 'N/A' }}:</strong> {{ $comment->comment }}
                            </li>
                        @empty
                            <li class="list-group-item">No comments yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 