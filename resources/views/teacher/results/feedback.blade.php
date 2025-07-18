@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Results Feedback</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Score</th>
                                <th>Term</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $result->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $result->score }}</td>
                                    <td>{{ ucfirst($result->term) }}</td>
                                    <td>{{ $result->year }}</td>
                                    <td>
                                        @if($result->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($result->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">{{ ucfirst($result->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result->status === 'rejected')
                                            <small class="text-danger">Result was rejected by IT. Please review and resubmit.</small>
                                        @elseif($result->status === 'approved')
                                            <small class="text-success">Result approved and published to student.</small>
                                        @else
                                            <small class="text-muted">Under review by IT.</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No results feedback available.</p>
                    <a href="{{ route('teacher.results.create') }}" class="btn btn-primary">Submit New Result</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 