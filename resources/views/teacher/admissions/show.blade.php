@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Student Admission Details</h3>
                    <div>
                        <a href="{{ route('teacher.admissions.edit', $student) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('teacher.admissions.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Personal Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $student->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $student->user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Admission Number:</strong></td>
                                    <td>{{ $student->admission_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($student->gender) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Academic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $student->class->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Combination:</strong></td>
                                    <td>
                                        <span class="badge bg-success">{{ $student->combination }}</span>
                                        <br>
                                        <small class="text-muted">{{ \App\Models\Student::getAvailableCombinations()[$student->combination] ?? '' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Admission Status:</strong></td>
                                    <td>
                                        @if($student->admission_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($student->admission_status == 'admitted')
                                            <span class="badge bg-success">Admitted</span>
                                        @elseif($student->admission_status == 'approved')
                                            <span class="badge bg-primary">Approved</span>
                                        @elseif($student->admission_status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Admitted By:</strong></td>
                                    <td>{{ $student->admittedBy->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Admission Date:</strong></td>
                                    <td>{{ $student->admitted_at ? $student->admitted_at->format('M d, Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($student->admission_notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Admission Notes</h5>
                                <div class="alert alert-light">
                                    {{ $student->admission_notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                @if($student->admission_status == 'pending')
                                    <div>
                                        <form action="{{ route('teacher.admissions.approve', $student) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Approve Admission</button>
                                        </form>
                                        <form action="{{ route('teacher.admissions.reject', $student) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject Admission</button>
                                        </form>
                                    </div>
                                @endif
                                
                                <div>
                                    <form action="{{ route('teacher.admissions.destroy', $student) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this admission?')">
                                            Cancel Admission
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.admissions.edit', $student) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Student
                        </a>
                        <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-info">
                            <i class="fas fa-user"></i> View Student Profile
                        </a>
                        <a href="{{ route('teacher.results.create') }}?student_id={{ $student->id }}" class="btn btn-success">
                            <i class="fas fa-chart-line"></i> Add Results
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Account Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Username:</strong> {{ $student->user->email }}</p>
                    <p><strong>Default Password:</strong> password</p>
                    <div class="alert alert-warning">
                        <small>Student should change their password upon first login.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 