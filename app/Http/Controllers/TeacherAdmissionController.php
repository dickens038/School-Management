<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherAdmissionController extends Controller
{
    public function index()
    {
        // Get all classes (Form 1-6)
        $allClasses = SchoolClass::whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])->get();
        
        $students = Student::with(['user', 'class', 'admittedBy'])
            ->whereIn('class_id', $allClasses->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $classes = $allClasses;
        $combinations = Student::getAvailableCombinations();

        return view('teacher.admissions.index', compact('students', 'classes', 'combinations'));
    }

    public function create()
    {
        $classes = SchoolClass::whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])->get();
        $combinations = Student::getAvailableCombinations();

        return view('teacher.admissions.create', compact('classes', 'combinations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'admission_number' => 'required|string|unique:students,admission_number',
            'class_id' => 'required|exists:classes,id',
            'combination' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'admission_notes' => 'nullable|string',
        ]);

        // Check if the class is valid (Form 1-6)
        $class = SchoolClass::find($request->class_id);
        if (!in_array($class->name, ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])) {
            return redirect()->back()->withErrors(['class_id' => 'Admissions are only allowed for Form 1-6.']);
        }

        // Validate combination based on class level
        $validCombinations = Student::getCombinationsForClass($class->name);
        if (!array_key_exists($request->combination, $validCombinations)) {
            return redirect()->back()->withErrors(['combination' => 'Invalid combination for this class level.']);
        }

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password
            'role_id' => \App\Models\Role::where('name', 'student')->first()->id,
        ]);

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'admission_number' => $request->admission_number,
            'class_id' => $request->class_id,
            'combination' => $request->combination,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'admission_status' => 'admitted',
            'admission_notes' => $request->admission_notes,
            'admitted_by' => auth()->id(),
            'admitted_at' => now(),
        ]);

        return redirect()->route('teacher.admissions.index')
            ->with('success', 'Student admitted successfully! Default password is "password".');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'class', 'admittedBy']);
        return view('teacher.admissions.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])->get();
        $combinations = Student::getAvailableCombinations();
        $student->load('user');

        return view('teacher.admissions.edit', compact('student', 'classes', 'combinations'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'combination' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'admission_notes' => 'nullable|string',
        ]);

        // Validate combination based on class level
        $class = SchoolClass::find($request->class_id);
        $validCombinations = Student::getCombinationsForClass($class->name);
        if (!array_key_exists($request->combination, $validCombinations)) {
            return redirect()->back()->withErrors(['combination' => 'Invalid combination for this class level.']);
        }

        // Update user
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update student
        $student->update([
            'admission_number' => $request->admission_number,
            'class_id' => $request->class_id,
            'combination' => $request->combination,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'admission_notes' => $request->admission_notes,
        ]);

        return redirect()->route('teacher.admissions.index')
            ->with('success', 'Student information updated successfully!');
    }

    public function destroy(Student $student)
    {
        // Delete user account
        $student->user->delete();
        
        return redirect()->route('teacher.admissions.index')
            ->with('success', 'Student admission cancelled successfully!');
    }

    public function approve(Student $student)
    {
        $student->update([
            'admission_status' => 'approved',
            'admitted_by' => auth()->id(),
            'admitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Student admission approved!');
    }

    public function reject(Student $student)
    {
        $student->update([
            'admission_status' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'Student admission rejected!');
    }

    public function pending()
    {
        $allClasses = SchoolClass::whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])->get();
        
        $students = Student::with(['user', 'class'])
            ->where('admission_status', 'pending')
            ->whereIn('class_id', $allClasses->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.admissions.pending', compact('students'));
    }

    public function export()
    {
        $allClasses = SchoolClass::whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])->get();
        
        $students = Student::with(['user', 'class', 'admittedBy'])
            ->whereIn('class_id', $allClasses->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'admissions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Admission Number',
                'Student Name',
                'Email',
                'Class',
                'Level',
                'Combination',
                'Gender',
                'Date of Birth',
                'Admission Status',
                'Admitted By',
                'Admitted At',
                'Notes'
            ]);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->admission_number,
                    $student->user->name,
                    $student->user->email,
                    $student->class->name ?? 'N/A',
                    $student->isAdvancedLevel() ? 'Advanced Level' : 'Ordinary Level',
                    $student->combination,
                    $student->gender,
                    $student->date_of_birth,
                    $student->admission_status,
                    $student->admittedBy->name ?? 'N/A',
                    $student->admitted_at ? $student->admitted_at->format('Y-m-d H:i:s') : 'N/A',
                    $student->admission_notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
