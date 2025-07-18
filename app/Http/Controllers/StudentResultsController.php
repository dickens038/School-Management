<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StudentResultsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student()->with(['results.subject'])->first();
        if (!$student) {
            abort(403, 'Not a student.');
        }
        // Get available years from results
        $years = $student->results->pluck('year')->unique()->sort()->values();
        $selectedYear = request('year', $years->last());
        $selectedTerm = request('term', null); // Not used for tab, but for future
        // Filter results by year and only approved
        $filtered = $student->results->where('year', $selectedYear)->where('status', 'approved');
        $monthly = $filtered->where('term', 'monthly');
        $midterm = $filtered->where('term', 'midterm');
        $final = $filtered->where('term', 'final');
        return view('results.student', compact('student', 'monthly', 'midterm', 'final', 'years', 'selectedYear', 'selectedTerm'));
    }
} 