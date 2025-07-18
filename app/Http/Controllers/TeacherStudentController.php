<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Role;

class TeacherStudentController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? collect();
        $students = collect();
        
        foreach ($classes as $class) {
            $students = $students->merge($class->students);
        }
        
        return view('teacher.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? collect();
        return view('teacher.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'admission_number' => 'required|string|unique:students,admission_number',
            'class_id' => 'required|exists:classes,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => Role::where('name', 'student')->first()->id,
        ]);

        // Create student
        Student::create([
            'user_id' => $user->id,
            'admission_number' => $request->admission_number,
            'class_id' => $request->class_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return redirect()->route('teacher.students.index')->with('success', 'Student created successfully.');
    }

    public function show($id)
    {
        $teacher = Auth::user()->teacher;
        $student = Student::with(['user', 'class'])->findOrFail($id);
        
        // Check if student is in teacher's class
        $isInTeacherClass = false;
        foreach ($teacher->classes as $class) {
            if ($class->students->contains($student->id)) {
                $isInTeacherClass = true;
                break;
            }
        }
        
        if (!$isInTeacherClass) {
            abort(403, 'You are not authorized to view this student.');
        }

        return view('teacher.students.show', compact('student'));
    }

    public function edit($id)
    {
        $teacher = Auth::user()->teacher;
        $student = Student::with(['user', 'class'])->findOrFail($id);
        
        // Check if student is in teacher's class
        $isInTeacherClass = false;
        foreach ($teacher->classes as $class) {
            if ($class->students->contains($student->id)) {
                $isInTeacherClass = true;
                break;
            }
        }
        
        if (!$isInTeacherClass) {
            abort(403, 'You are not authorized to edit this student.');
        }

        $classes = $teacher->classes ?? collect();
        return view('teacher.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Auth::user()->teacher;
        $student = Student::findOrFail($id);
        
        // Check if student is in teacher's class
        $isInTeacherClass = false;
        foreach ($teacher->classes as $class) {
            if ($class->students->contains($student->id)) {
                $isInTeacherClass = true;
                break;
            }
        }
        
        if (!$isInTeacherClass) {
            abort(403, 'You are not authorized to edit this student.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'admission_number' => 'required|string|unique:students,admission_number,' . $id,
            'class_id' => 'required|exists:classes,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        // Update user
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update student
        $student->update([
            'admission_number' => $request->admission_number,
            'class_id' => $request->class_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return redirect()->route('teacher.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $teacher = Auth::user()->teacher;
        $student = Student::findOrFail($id);
        
        // Check if student is in teacher's class
        $isInTeacherClass = false;
        foreach ($teacher->classes as $class) {
            if ($class->students->contains($student->id)) {
                $isInTeacherClass = true;
                break;
            }
        }
        
        if (!$isInTeacherClass) {
            abort(403, 'You are not authorized to delete this student.');
        }

        $student->delete();
        return redirect()->route('teacher.students.index')->with('success', 'Student deleted successfully.');
    }
} 