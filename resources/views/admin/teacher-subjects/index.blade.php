@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Assign Teachers to Subjects by Department</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Department Filter -->
    <form class="mb-4">
        <label for="department_filter">Filter by Department:</label>
        <select id="department_filter" class="form-select" style="width:auto;display:inline-block;">
            <option value="">All Departments</option>
            @foreach($departments as $department)
                <option value="{{ $department->name }}">{{ $department->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('admin.teacher-subjects.assign') }}" method="POST" id="assignForm">
                @csrf
                <div class="mb-3">
                    <label for="teacher_id">Teacher</label>
                    <select name="teacher_id" id="teacher_id" class="form-select" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-department="{{ $teacher->department }}">
                                {{ $teacher->user->name }} ({{ $teacher->department }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Subjects</label>
                    <div id="subjects_container">
                        @foreach($subjects as $subject)
                            <div class="form-check subject-item" data-department="{{ $subject->department }}">
                                <input class="form-check-input" type="checkbox" name="subject_ids[]"
                                    value="{{ $subject->id }}" id="subject_{{ $subject->id }}">
                                <label class="form-check-label" for="subject_{{ $subject->id }}">
                                    {{ $subject->name }} ({{ $subject->department }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label for="primary_subject_id">Primary Subject</label>
                    <select name="primary_subject_id" id="primary_subject_id" class="form-select">
                        <option value="">Select primary subject...</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Assign Subjects</button>
            </form>
        </div>
        <div class="col-md-6">
            <h5>Current Assignments</h5>
            @foreach($teachers as $teacher)
                <div class="teacher-assignment mb-3" data-department="{{ $teacher->department }}">
                    <strong>{{ $teacher->user->name }} ({{ $teacher->department }})</strong>
                    @if($teacher->subjects->count())
                        <ul>
                            @foreach($teacher->subjects as $subject)
                                <li>
                                    {{ $subject->name }}
                                    @if($subject->pivot->is_primary)
                                        <span class="badge bg-primary">Primary</span>
                                    @endif
                                    <form action="{{ route('admin.teacher-subjects.remove') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                    @if(!$subject->pivot->is_primary)
                                        <form action="{{ route('admin.teacher-subjects.set-primary') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Set Primary</button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-muted">No subjects assigned</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentFilter = document.getElementById('department_filter');
    const teacherSelect = document.getElementById('teacher_id');
    const subjectsContainer = document.getElementById('subjects_container');
    const primarySubjectSelect = document.getElementById('primary_subject_id');

    // Filter teachers and subjects by department
    departmentFilter.addEventListener('change', function() {
        const dept = this.value;
        // Teachers
        Array.from(teacherSelect.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = !dept || opt.dataset.department === dept ? '' : 'none';
        });
        // Subjects
        Array.from(subjectsContainer.querySelectorAll('.subject-item')).forEach(item => {
            item.style.display = !dept || item.dataset.department === dept ? '' : 'none';
        });
        // Assignments
        document.querySelectorAll('.teacher-assignment').forEach(item => {
            item.style.display = !dept || item.dataset.department === dept ? '' : 'none';
        });
    });

    // Update primary subject options when subjects are selected
    document.querySelectorAll('input[name="subject_ids[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const selected = Array.from(document.querySelectorAll('input[name="subject_ids[]"]:checked'));
            primarySubjectSelect.innerHTML = '<option value="">Select primary subject...</option>';
            selected.forEach(cb => {
                const label = cb.nextElementSibling.textContent.trim();
                const opt = document.createElement('option');
                opt.value = cb.value;
                opt.textContent = label;
                primarySubjectSelect.appendChild(opt);
            });
        });
    });
});
</script>
@endsection
