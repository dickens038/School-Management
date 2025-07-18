@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Student Admission Details</h3>
                    <div>
                        <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Back to List</a>
                        <a href="{{ route('admin.admissions.edit', $student) }}" class="btn btn-warning">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Student Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Admission Number:</strong></td>
                                    <td>{{ $student->admission_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td>{{ $student->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $student->user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ ucfirst($student->gender) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ $student->date_of_birth }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Academic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td>{{ $student->class->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Level:</strong></td>
                                    <td>
                                        @if($student->isAdvancedLevel())
                                            <span class="badge bg-primary">Advanced Level</span>
                                        @else
                                            <span class="badge bg-info">Ordinary Level</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Combination:</strong></td>
                                    <td>
                                        <span class="badge bg-success">{{ $student->combination }}</span>
                                        <small class="d-block text-muted">
                                            @if($student->isAdvancedLevel())
                                                {{ \App\Models\Student::getAdvancedLevelCombinations()[$student->combination] ?? '' }}
                                            @else
                                                {{ \App\Models\Student::getOrdinaryLevelCombinations()[$student->combination] ?? '' }}
                                            @endif
                                        </small>
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
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Admission Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Admitted By:</strong></td>
                                    <td>{{ $student->admittedBy->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Admitted At:</strong></td>
                                    <td>{{ $student->admitted_at ? $student->admitted_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Notes:</strong></td>
                                    <td>{{ $student->admission_notes ?? 'No notes' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($student->admission_status == 'pending')
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Actions</h5>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('admin.admissions.approve', $student) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Approve Admission</button>
                                    </form>
                                    <form action="{{ route('admin.admissions.reject', $student) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Reject Admission</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 