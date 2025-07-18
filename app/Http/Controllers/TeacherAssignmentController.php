<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Subject;

class TeacherAssignmentController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $assignments = Assignment::where('teacher_id', $teacher->id)->with(['class', 'subject'])->get();
        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? [];
        $subjects = Subject::all();
        return view('teacher.assignments.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user()->teacher;
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);
        Assignment::create([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);
        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment created.');
    }

    public function edit(Assignment $assignment)
    {
        $teacher = Auth::user()->teacher;
        if ($assignment->teacher_id !== $teacher->id) abort(403);
        $classes = $teacher->classes ?? [];
        $subjects = Subject::all();
        return view('teacher.assignments.edit', compact('assignment', 'classes', 'subjects'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $teacher = Auth::user()->teacher;
        if ($assignment->teacher_id !== $teacher->id) abort(403);
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);
        $assignment->update($request->only('class_id', 'subject_id', 'title', 'description', 'due_date'));
        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment updated.');
    }

    public function destroy(Assignment $assignment)
    {
        $teacher = Auth::user()->teacher;
        if ($assignment->teacher_id !== $teacher->id) abort(403);
        $assignment->delete();
        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment deleted.');
    }
} 