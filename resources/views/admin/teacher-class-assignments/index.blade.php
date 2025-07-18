@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Teacher-Class Assignments</h3>
                    <div>
                        <a href="{{ route('admin.teacher-class-assignments.export') }}" class="btn btn-success">Export Assignments</a>
                    </div>
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
                                @foreach($classes as $class)
                                    <option value="{{ $class->name }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="department_filter">Filter by Department:</label>
                            <select id="department_filter" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->name }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="teacher_filter">Filter by Teacher:</label>
                            <select id="teacher_filter" class="form-select">
                                <option value="">All Teachers</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->user->name }}">{{ $teacher->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Classes and Teachers Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Students</th>
                                    <th>Assigned Teachers</th>
                                    <th>Class Teacher</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classes as $class)
                                    <tr data-class="{{ $class->name }}" data-department="{{ $class->teachers->first()->department ?? '' }}">
                                        <td>
                                            <strong>{{ $class->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $class->students->count() }} students</small>
                                        </td>
                                        <td>
                                            @if($class->students->count() > 0)
                                                <span class="badge bg-info">{{ $class->students->count() }} students</span>
                                            @else
                                                <span class="text-muted">No students</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($class->teachers->count() > 0)
                                                @foreach($class->teachers as $teacher)
                                                    <div class="mb-1">
                                                        <span class="badge bg-primary">{{ $teacher->user->name }}</span>
                                                        @if($teacher->pivot->is_class_teacher)
                                                            <span class="badge bg-success">Class Teacher</span>
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">{{ $teacher->department ?? 'No Department' }}</small>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No teachers assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $classTeacher = $class->teachers->where('pivot.is_class_teacher', true)->first();
                                            @endphp
                                            @if($classTeacher)
                                                <span class="badge bg-success">{{ $classTeacher->user->name }}</span>
                                            @else
                                                <span class="text-warning">No class teacher</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.teacher-class-assignments.show', $class) }}" class="btn btn-info btn-sm">Manage</a>
                                                <button class="btn btn-success btn-sm" onclick="showBulkAssignModal('{{ $class->id }}', '{{ $class->name }}')">Bulk Assign</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Assignment Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Assign Teachers to Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.teacher-class-assignments.bulk-assign') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="bulk_class_id" name="class_id">
                    <div class="mb-3">
                        <label class="form-label">Class: <span id="bulk_class_name"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_teacher_ids" class="form-label">Select Teachers:</label>
                        <select id="bulk_teacher_ids" name="teacher_ids[]" class="form-select" multiple required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }} ({{ $teacher->department ?? 'No Department' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Teachers</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classFilter = document.getElementById('class_filter');
    const departmentFilter = document.getElementById('department_filter');
    const teacherFilter = document.getElementById('teacher_filter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const selectedClass = classFilter.value;
        const selectedDepartment = departmentFilter.value;
        const selectedTeacher = teacherFilter.value;

        tableRows.forEach(row => {
            const rowClass = row.dataset.class;
            const rowDepartment = row.dataset.department;
            const rowTeachers = row.querySelector('td:nth-child(3)').textContent;

            const classMatch = !selectedClass || rowClass === selectedClass;
            const departmentMatch = !selectedDepartment || rowDepartment === selectedDepartment;
            const teacherMatch = !selectedTeacher || rowTeachers.includes(selectedTeacher);

            row.style.display = classMatch && departmentMatch && teacherMatch ? '' : 'none';
        });
    }

    classFilter.addEventListener('change', filterTable);
    departmentFilter.addEventListener('change', filterTable);
    teacherFilter.addEventListener('change', filterTable);
});

function showBulkAssignModal(classId, className) {
    document.getElementById('bulk_class_id').value = classId;
    document.getElementById('bulk_class_name').textContent = className;
    new bootstrap.Modal(document.getElementById('bulkAssignModal')).show();
}
</script>
@endsection 