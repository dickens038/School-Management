<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student()->with(['class.subjects', 'results.subject', 'comments.teacher', 'awards.teacher', 'class.assignments.subject'])->first();
        if (!$student) {
            abort(403, 'Not a student.');
        }
        return view('dashboard.student', compact('student'));
    }
} 