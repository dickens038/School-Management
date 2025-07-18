@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Student Admissions (Form 1-6)</h3>
                    <a href="{{ route('teacher.admissions.create') }}" class="btn btn-primary">Admit New Student</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="class_filter">Filter by Class:</label>
                            <select id="class_filter" class="form-select">
                                <option value="">All Classes</option>
                                <optgroup label="Ordinary Level">
                                    <option value="Form 1">Form 1</option>
                                    <option value="Form 2">Form 2</option>
                                    <option value="Form 3">Form 3</option>
                                    <option value="Form 4">Form 4</option>
                                </optgroup>
                                <optgroup label="Advanced Level">
                                    <option value="Form 5">Form 5</option>
                                    <option value="Form 6">Form 6</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="level_filter">Filter by Level:</label>
                            <select id="level_filter" class="form-select">
                                <option value="">All Levels</option>
                                <option value="ordinary">Ordinary Level (Form 1-4)</option>
                                <option value="advanced">Advanced Level (Form 5-6)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="combination_filter">Filter by Combination:</label>
                            <select id="combination_filter" class="form-select">
                                <option value="">All Combinations</option>
                                <optgroup label="Ordinary Level">
                                    <option value="SCIENCE">SCIENCE - Physics, Chemistry, Biology, Mathematics</option>
                                    <option value="ARTS">ARTS - History, Geography, Literature, Kiswahili</option>
                                    <option value="COMMERCE">COMMERCE - Commerce, Accounts, Economics, Mathematics</option>
                                    <option value="GENERAL">GENERAL - General Studies</option>
                                </optgroup>
                                <optgroup label="Advanced Level">
                                    <option value="CBG">CBG - Chemistry, Biology, Geography</option>
                                    <option value="PCM">PCM - Physics, Chemistry, Mathematics</option>
                                    <option value="PCB">PCB - Physics, Chemistry, Biology</option>
                                    <option value="PGM">PGM - Physics, Geography, Mathematics</option>
                                    <option value="HKL">HKL - History, Kiswahili, Literature</option>
                                    <option value="HGK">HGK - History, Geography, Kiswahili</option>
                                    <option value="HGE">HGE - History, Geography, Economics</option>
                                    <option value="ECA">ECA - Economics, Commerce, Accounts</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status_filter">Filter by Status:</label>
                            <select id="status_filter" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="admitted">Admitted</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Admission No.</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Level</th>
                                    <th>Combination</th>
                                    <th>Status</th>
                                    <th>Admitted By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr data-class="{{ $student->class->name }}" 
                                        data-level="{{ $student->isAdvancedLevel() ? 'advanced' : 'ordinary' }}"
                                        data-combination="{{ $student->combination }}" 
                                        data-status="{{ $student->admission_status }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->admission_number }}</td>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->class->name }}</td>
                                        <td>
                                            @if($student->isAdvancedLevel())
                                                <span class="badge bg-primary">Advanced Level</span>
                                            @else
                                                <span class="badge bg-info">Ordinary Level</span>
                                            @endif
                                        </td>
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
                                        <td>{{ $student->admittedBy->name ?? '-' }}</td>
                                        <td>{{ $student->admitted_at ? $student->admitted_at->format('M d, Y') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.admissions.show', $student) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('teacher.admissions.edit', $student) }}" class="btn btn-warning btn-sm">Edit</a>
                                                @if($student->admission_status == 'pending')
                                                    <form action="{{ route('teacher.admissions.approve', $student) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                    </form>
                                                    <form action="{{ route('teacher.admissions.reject', $student) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('teacher.admissions.destroy', $student) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this admission?')">Cancel</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classFilter = document.getElementById('class_filter');
    const levelFilter = document.getElementById('level_filter');
    const combinationFilter = document.getElementById('combination_filter');
    const statusFilter = document.getElementById('status_filter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const selectedClass = classFilter.value;
        const selectedLevel = levelFilter.value;
        const selectedCombination = combinationFilter.value;
        const selectedStatus = statusFilter.value;

        tableRows.forEach(row => {
            const rowClass = row.dataset.class;
            const rowLevel = row.dataset.level;
            const rowCombination = row.dataset.combination;
            const rowStatus = row.dataset.status;

            const classMatch = !selectedClass || rowClass === selectedClass;
            const levelMatch = !selectedLevel || rowLevel === selectedLevel;
            const combinationMatch = !selectedCombination || rowCombination === selectedCombination;
            const statusMatch = !selectedStatus || rowStatus === selectedStatus;

            row.style.display = classMatch && levelMatch && combinationMatch && statusMatch ? '' : 'none';
        });
    }

    classFilter.addEventListener('change', filterTable);
    levelFilter.addEventListener('change', filterTable);
    combinationFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endsection 