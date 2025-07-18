<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;

class TeacherClassController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? collect();
        return view('teacher.classes.index', compact('classes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('teacher.classes.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
        ]);

        $class = SchoolClass::create([
            'name' => $request->name,
        ]);

        // Assign teacher to class
        $teacher = Auth::user()->teacher;
        $class->teachers()->attach($teacher->id);

        return redirect()->route('teacher.classes.index')->with('success', 'Class created successfully.');
    }

    public function show($id)
    {
        $teacher = Auth::user()->teacher;
        $class = SchoolClass::with(['students.user', 'subjects', 'teachers'])->findOrFail($id);
        
        // Check if teacher is assigned to this class
        if (!$class->teachers->contains($teacher->id)) {
            abort(403, 'You are not assigned to this class.');
        }

        return view('teacher.classes.show', compact('class'));
    }

    public function edit($id)
    {
        $teacher = Auth::user()->teacher;
        $class = SchoolClass::findOrFail($id);
        
        // Check if teacher is assigned to this class
        if (!$class->teachers->contains($teacher->id)) {
            abort(403, 'You are not assigned to this class.');
        }

        $subjects = Subject::all();
        return view('teacher.classes.edit', compact('class', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Auth::user()->teacher;
        $class = SchoolClass::findOrFail($id);
        
        // Check if teacher is assigned to this class
        if (!$class->teachers->contains($teacher->id)) {
            abort(403, 'You are not assigned to this class.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $id,
        ]);

        $class->update([
            'name' => $request->name,
        ]);

        return redirect()->route('teacher.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy($id)
    {
        $teacher = Auth::user()->teacher;
        $class = SchoolClass::findOrFail($id);
        
        // Check if teacher is assigned to this class
        if (!$class->teachers->contains($teacher->id)) {
            abort(403, 'You are not assigned to this class.');
        }

        $class->delete();
        return redirect()->route('teacher.classes.index')->with('success', 'Class deleted successfully.');
    }
} 