@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Teacher Reports</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($classes->count() > 0)
        <!-- Performance Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Performance Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($classes as $class)
                                @if(isset($performanceData[$class->name]))
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">{{ $class->name }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <h4 class="text-primary">{{ $performanceData[$class->name]['total_students'] }}</h4>
                                                        <small class="text-muted">Students</small>
                                                    </div>
                                                    <div class="col-6">
                                                        <h4 class="text-success">{{ $performanceData[$class->name]['total_results'] }}</h4>
                                                        <small class="text-muted">Results</small>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <h6 class="text-info">{{ $performanceData[$class->name]['average_score'] }}%</h6>
                                                        <small class="text-muted">Average</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="text-success">{{ $performanceData[$class->name]['highest_score'] }}%</h6>
                                                        <small class="text-muted">Highest</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="text-warning">{{ $performanceData[$class->name]['lowest_score'] }}%</h6>
                                                        <small class="text-muted">Lowest</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Reports -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Assignment Reports</h5>
                    </div>
                    <div class="card-body">
                        @foreach($classes as $class)
                            @if(isset($assignmentData[$class->name]))
                                <div class="mb-3">
                                    <h6>{{ $class->name }} - {{ $assignmentData[$class->name]['total_assignments'] }} Assignments</h6>
                                    @if($assignmentData[$class->name]['recent_assignments']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Subject</th>
                                                        <th>Due Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($assignmentData[$class->name]['recent_assignments'] as $assignment)
                                                        <tr>
                                                            <td>{{ $assignment->title }}</td>
                                                            <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                                                            <td>{{ $assignment->due_date }}</td>
                                                            <td>
                                                                @if(strtotime($assignment->due_date) < time())
                                                                    <span class="badge bg-danger">Overdue</span>
                                                                @else
                                                                    <span class="badge bg-success">Active</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No assignments for this class.</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Performance by Class -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Student Performance by Class</h5>
                    </div>
                    <div class="card-body">
                        @foreach($classes as $class)
                            <div class="mb-4">
                                <h6>{{ $class->name }}</h6>
                                @if($class->students->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Student</th>
                                                    <th>Admission #</th>
                                                    <th>Results Count</th>
                                                    <th>Average Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($class->students as $student)
                                                    @php
                                                        $studentResults = \App\Models\Result::where('student_id', $student->id)
                                                            ->where('teacher_id', $teacher->id)
                                                            ->get();
                                                        $avgScore = $studentResults->count() > 0 ? round($studentResults->avg('score'), 2) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $student->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $student->admission_number }}</td>
                                                        <td>{{ $studentResults->count() }}</td>
                                                        <td>
                                                            @if($avgScore > 0)
                                                                <span class="badge bg-{{ $avgScore >= 70 ? 'success' : ($avgScore >= 50 ? 'warning' : 'danger') }}">
                                                                    {{ $avgScore }}%
                                                                </span>
                                                            @else
                                                                <span class="text-muted">No results</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No students in this class.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Export Reports</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100 mb-2">Export Performance Report</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success w-100 mb-2">Export Assignment Report</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-info w-100 mb-2">Export Student List</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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