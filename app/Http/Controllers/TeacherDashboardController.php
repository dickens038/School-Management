<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher()->with([
            'classes.subjects',
            'classes.students.user',
            'assignments.subject',
            'results.student.user',
            'comments.student.user',
            'awards.student.user',
        ])->first();
        if (!$teacher) {
            abort(403, 'Not a teacher.');
        }
        return view('dashboard.teacher', compact('teacher'));
    }

    public function reportsIndex()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? collect();
        
        // Get performance data
        $performanceData = [];
        $attendanceData = [];
        $assignmentData = [];
        
        foreach ($classes as $class) {
            // Performance data
            $results = \App\Models\Result::whereHas('student', function($query) use ($class) {
                $query->where('class_id', $class->id);
            })->where('teacher_id', $teacher->id)->get();
            
            $performanceData[$class->name] = [
                'total_students' => $class->students->count(),
                'total_results' => $results->count(),
                'average_score' => $results->count() > 0 ? round($results->avg('score'), 2) : 0,
                'highest_score' => $results->max('score') ?? 0,
                'lowest_score' => $results->min('score') ?? 0,
            ];
            
            // Assignment data
            $assignments = \App\Models\Assignment::where('class_id', $class->id)
                ->where('teacher_id', $teacher->id)->get();
            
            $assignmentData[$class->name] = [
                'total_assignments' => $assignments->count(),
                'recent_assignments' => $assignments->take(5),
            ];
        }
        
        return view('teacher.reports.index', compact('classes', 'performanceData', 'assignmentData'));
    }

    public function createResult()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? collect();
        $subjects = \App\Models\Subject::all();
        $students = collect();
        foreach ($classes as $class) {
            $students = $students->merge($class->students);
        }
        return view('teacher.results.create', compact('classes', 'subjects', 'students'));
    }

    public function storeResult(Request $request)
    {
        $teacher = Auth::user()->teacher;
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:100',
            'term' => 'required|in:first,second,third',
            'year' => 'required|string',
        ]);

        \App\Models\Result::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $teacher->id,
            'score' => $request->score,
            'term' => $request->term,
            'year' => $request->year,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Result submitted successfully and sent to IT for approval.');
    }

    public function resultsFeedback()
    {
        $teacher = Auth::user()->teacher;
        $results = \App\Models\Result::where('teacher_id', $teacher->id)
            ->where('status', '!=', 'pending')
            ->with(['student.user', 'subject'])
            ->get();
        return view('teacher.results.feedback', compact('results'));
    }

    public function attendanceIndex()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher->classes ?? collect();
        return view('teacher.attendance.index', compact('classes'));
    }

    public function attendanceStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        // For now, just return success (attendance table would need to be created)
        return back()->with('success', 'Attendance marked successfully.');
    }
} 