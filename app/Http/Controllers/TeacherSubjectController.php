<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class TeacherSubjectController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $teachers = Teacher::with(['user', 'subjects'])->get();
        $subjects = Subject::all();

        return view('admin.teacher-subjects.index', compact('departments', 'teachers', 'subjects'));
    }

    public function assignSubjects(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'primary_subject_id' => 'nullable|exists:subjects,id'
        ]);

        $teacher = Teacher::findOrFail($request->teacher_id);

        // Assign subjects, set primary
        $syncData = [];
        foreach ($request->subject_ids as $subjectId) {
            $syncData[$subjectId] = [
                'is_primary' => $request->primary_subject_id == $subjectId,
            ];
        }
        $teacher->subjects()->sync($syncData);

        return redirect()->back()->with('success', 'Subjects assigned successfully.');
    }

    public function getSubjectsByDepartment(Request $request)
    {
        $department = $request->department;
        $subjects = Subject::where('department', $department)->get();
        
        return response()->json($subjects);
    }

    public function getTeachersByDepartment(Request $request)
    {
        $department = $request->department;
        $teachers = Teacher::where('department', $department)
                          ->with(['user', 'subjects'])
                          ->get();
        
        return response()->json($teachers);
    }

    public function removeSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id'
        ]);
        $teacher = Teacher::findOrFail($request->teacher_id);
        $teacher->subjects()->detach($request->subject_id);

        return redirect()->back()->with('success', 'Subject removed from teacher.');
    }

    public function updatePrimarySubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id'
        ]);
        $teacher = Teacher::findOrFail($request->teacher_id);

        // Set all to not primary, then set the selected one to primary
        foreach ($teacher->subjects as $subject) {
            $teacher->subjects()->updateExistingPivot($subject->id, ['is_primary' => $subject->id == $request->subject_id]);
        }

        return redirect()->back()->with('success', 'Primary subject updated.');
    }
}
