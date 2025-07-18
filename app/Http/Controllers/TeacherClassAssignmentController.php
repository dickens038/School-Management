<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Department;

class TeacherClassAssignmentController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with(['teachers.user', 'students'])->get();
        $teachers = Teacher::with(['user', 'classes', 'subjects'])->get();
        $departments = Department::all();

        return view('admin.teacher-class-assignments.index', compact('classes', 'teachers', 'departments'));
    }

    public function show(SchoolClass $class)
    {
        $class->load(['teachers.user', 'students.user', 'subjects']);
        $availableTeachers = Teacher::with(['user', 'subjects'])
            ->whereDoesntHave('classes', function($query) use ($class) {
                $query->where('class_id', $class->id);
            })
            ->get();

        return view('admin.teacher-class-assignments.show', compact('class', 'availableTeachers'));
    }

    public function assignTeacher(Request $request, SchoolClass $class)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subjects' => 'array',
            'is_class_teacher' => 'boolean'
        ]);

        $teacher = Teacher::findOrFail($request->teacher_id);
        
        // Assign teacher to class
        $class->teachers()->attach($request->teacher_id, [
            'is_class_teacher' => $request->is_class_teacher ?? false,
            'assigned_at' => now(),
            'assigned_by' => auth()->id()
        ]);

        // Assign subjects if provided
        if ($request->subjects) {
            foreach ($request->subjects as $subjectId) {
                $teacher->subjects()->attach($subjectId, [
                    'is_primary' => false,
                    'assigned_at' => now()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Teacher assigned to class successfully!');
    }

    public function removeTeacher(Request $request, SchoolClass $class)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id'
        ]);

        $class->teachers()->detach($request->teacher_id);

        return redirect()->back()->with('success', 'Teacher removed from class successfully!');
    }

    public function setClassTeacher(Request $request, SchoolClass $class)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id'
        ]);

        // Remove existing class teacher status
        $class->teachers()->updateExistingPivot($class->teachers->pluck('id'), [
            'is_class_teacher' => false
        ]);

        // Set new class teacher
        $class->teachers()->updateExistingPivot($request->teacher_id, [
            'is_class_teacher' => true
        ]);

        return redirect()->back()->with('success', 'Class teacher updated successfully!');
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:teachers,id'
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        
        foreach ($request->teacher_ids as $teacherId) {
            $class->teachers()->attach($teacherId, [
                'assigned_at' => now(),
                'assigned_by' => auth()->id()
            ]);
        }

        return redirect()->back()->with('success', 'Teachers assigned to class successfully!');
    }

    public function getTeachersByDepartment(Request $request)
    {
        $departmentId = $request->department_id;
        $teachers = Teacher::with('user')
            ->where('department', $departmentId)
            ->get();

        return response()->json($teachers);
    }

    public function getClassDetails(SchoolClass $class)
    {
        $class->load(['teachers.user', 'students.user', 'subjects']);
        
        return response()->json([
            'class' => $class,
            'teacher_count' => $class->teachers->count(),
            'student_count' => $class->students->count(),
            'subject_count' => $class->subjects->count()
        ]);
    }

    public function exportAssignments()
    {
        $classes = SchoolClass::with(['teachers.user', 'students'])->get();
        
        $data = [];
        foreach ($classes as $class) {
            foreach ($class->teachers as $teacher) {
                $data[] = [
                    'Class' => $class->name,
                    'Teacher' => $teacher->user->name,
                    'Employee Number' => $teacher->employee_number,
                    'Department' => $teacher->department,
                    'Is Class Teacher' => $teacher->pivot->is_class_teacher ? 'Yes' : 'No',
                    'Assigned At' => $teacher->pivot->assigned_at
                ];
            }
        }

        return response()->json($data);
    }
}
