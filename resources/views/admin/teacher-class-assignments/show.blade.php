@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manage Teachers for {{ $class->name }}</h3>
                    <a href="{{ route('admin.teacher-class-assignments.index') }}" class="btn btn-secondary">Back to All Classes</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Class Information -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Students</h5>
                                    <h3 class="text-primary">{{ $class->students->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Assigned Teachers</h5>
                                    <h3 class="text-success">{{ $class->teachers->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Subjects</h5>
                                    <h3 class="text-info">{{ $class->subjects->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Teachers -->
                    <h5>Currently Assigned Teachers</h5>
                    @if($class->teachers->count() > 0)
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Teacher</th>
                                        <th>Employee #</th>
                                        <th>Department</th>
                                        <th>Role</th>
                                        <th>Assigned At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class->teachers as $teacher)
                                        <tr>
                                            <td>
                                                <strong>{{ $teacher->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $teacher->user->email }}</small>
                                            </td>
                                            <td>{{ $teacher->employee_number }}</td>
                                            <td>{{ $teacher->department ?? 'No Department' }}</td>
                                            <td>
                                                @if($teacher->pivot->is_class_teacher)
                                                    <span class="badge bg-success">Class Teacher</span>
                                                @else
                                                    <span class="badge bg-primary">Subject Teacher</span>
                                                @endif
                                            </td>
                                            <td>{{ $teacher->pivot->assigned_at ? \Carbon\Carbon::parse($teacher->pivot->assigned_at)->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(!$teacher->pivot->is_class_teacher)
                                                        <form action="{{ route('admin.teacher-class-assignments.set-class-teacher', $class) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                                            <button type="submit" class="btn btn-success btn-sm">Set as Class Teacher</button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('admin.teacher-class-assignments.remove', $class) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this teacher from the class?')">Remove</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <strong>No teachers assigned to this class.</strong>
                            <p>Use the form below to assign teachers to this class.</p>
                        </div>
                    @endif

                    <!-- Assign New Teacher -->
                    <h5>Assign New Teacher</h5>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.teacher-class-assignments.assign', $class) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="teacher_id" class="form-label">Select Teacher *</label>
                                            <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                                                <option value="">Choose a teacher...</option>
                                                @foreach($availableTeachers as $teacher)
                                                    <option value="{{ $teacher->id }}">
                                                        {{ $teacher->user->name }} ({{ $teacher->employee_number }}) - {{ $teacher->department ?? 'No Department' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('teacher_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_class_teacher" name="is_class_teacher" value="1">
                                                <label class="form-check-label" for="is_class_teacher">
                                                    Set as Class Teacher
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Assign Teacher</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Class Details -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Class Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Class:</strong> {{ $class->name }}</p>
                    <p><strong>Students:</strong> {{ $class->students->count() }}</p>
                    <p><strong>Teachers:</strong> {{ $class->teachers->count() }}</p>
                    <p><strong>Subjects:</strong> {{ $class->subjects->count() }}</p>
                </div>
            </div>

            <!-- Available Teachers -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Available Teachers</h5>
                </div>
                <div class="card-body">
                    @if($availableTeachers->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($availableTeachers as $teacher)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $teacher->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $teacher->department ?? 'No Department' }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $teacher->classes->count() }} classes</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">All teachers are already assigned to this class.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.teacher-subjects.index') }}" class="btn btn-info">Manage Teacher Subjects</a>
                        <a href="{{ route('admin.admissions.index') }}" class="btn btn-success">Student Admissions</a>
                        <a href="{{ route('classes.show', $class) }}" class="btn btn-secondary">View Class Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 