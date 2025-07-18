<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchoolPerformanceController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $selectedClass = $request->get('class');
        $selectedCombination = $request->get('combination');
        $selectedSubject = $request->get('subject');

        // Build base query for results
        $baseQuery = Result::query();

        // Apply filters
        if ($selectedClass) {
            $baseQuery->whereHas('student.class', function($query) use ($selectedClass) {
                $query->where('name', $selectedClass);
            });
        }

        if ($selectedCombination) {
            $baseQuery->whereHas('student', function($query) use ($selectedCombination) {
                $query->where('combination', $selectedCombination);
            });
        }

        if ($selectedSubject) {
            $baseQuery->whereHas('subject', function($query) use ($selectedSubject) {
                $query->where('name', $selectedSubject);
            });
        }

        // Basic statistics with filters
        $totalStudents = Student::when($selectedClass, function($query) use ($selectedClass) {
            $query->whereHas('class', function($q) use ($selectedClass) {
                $q->where('name', $selectedClass);
            });
        })->when($selectedCombination, function($query) use ($selectedCombination) {
            $query->where('combination', $selectedCombination);
        })->count();

        $totalResults = $baseQuery->count();
        $averageScore = $baseQuery->avg('score') ?? 0;
        $passCount = $baseQuery->where('score', '>=', 50)->count();
        $failCount = $baseQuery->where('score', '<', 50)->count();
        $passRate = $totalResults > 0 ? round(($passCount / $totalResults) * 100, 2) : 0;
        $failRate = $totalResults > 0 ? round(($failCount / $totalResults) * 100, 2) : 0;

        // Grade distribution with filters
        $gradeDistribution = [
            'A (80-100)' => $baseQuery->whereBetween('score', [80, 100])->count(),
            'B (70-79)' => $baseQuery->whereBetween('score', [70, 79])->count(),
            'C (60-69)' => $baseQuery->whereBetween('score', [60, 69])->count(),
            'D (50-59)' => $baseQuery->whereBetween('score', [50, 59])->count(),
            'F (0-49)' => $baseQuery->whereBetween('score', [0, 49])->count(),
        ];

        // Performance trends (last 6 months) with filters
        $trends = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthResults = $baseQuery->clone()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->get();
            
            $trends->push([
                'month' => $date->format('M Y'),
                'average' => $monthResults->avg('score') ?? 0,
                'count' => $monthResults->count(),
                'pass_rate' => $monthResults->count() > 0 ? 
                    round(($monthResults->where('score', '>=', 50)->count() / $monthResults->count()) * 100, 2) : 0
            ]);
        }

        // Top students with filters
        $topStudents = Student::with(['user', 'results', 'class'])
            ->when($selectedClass, function($query) use ($selectedClass) {
                $query->whereHas('class', function($q) use ($selectedClass) {
                    $q->where('name', $selectedClass);
                });
            })
            ->when($selectedCombination, function($query) use ($selectedCombination) {
                $query->where('combination', $selectedCombination);
            })
            ->get()
            ->map(function($student) {
                $avg = $student->results->avg('score') ?? 0;
                $totalResults = $student->results->count();
                return [
                    'name' => $student->user->name ?? 'Unknown',
                    'admission_number' => $student->admission_number ?? 'N/A',
                    'class' => $student->class->name ?? 'N/A',
                    'combination' => $student->combination ?? 'N/A',
                    'average' => $avg,
                    'total_results' => $totalResults,
                    'pass_count' => $student->results->where('score', '>=', 50)->count(),
                ];
            })
            ->filter(function($student) {
                return $student['total_results'] > 0;
            })
            ->sortByDesc('average')
            ->take(10);

        // Class performance with filters
        $classes = SchoolClass::with(['students.results'])
            ->when($selectedClass, function($query) use ($selectedClass) {
                $query->where('name', $selectedClass);
            })
            ->get();
        
        $classStats = $classes->map(function($class) use ($selectedCombination) {
            $students = $class->students;
            
            // Apply combination filter if selected
            if ($selectedCombination) {
                $students = $students->where('combination', $selectedCombination);
            }
            
            $results = $students->flatMap->results;
            $avg = $results->avg('score') ?? 0;
            $pass = $results->where('score', '>=', 50)->count();
            $total = $results->count();
            $passRate = $total > 0 ? round(($pass / $total) * 100, 2) : 0;
            
            // Grade distribution for this class
            $gradeDist = [
                'A' => $results->whereBetween('score', [80, 100])->count(),
                'B' => $results->whereBetween('score', [70, 79])->count(),
                'C' => $results->whereBetween('score', [60, 69])->count(),
                'D' => $results->whereBetween('score', [50, 59])->count(),
                'F' => $results->whereBetween('score', [0, 49])->count(),
            ];
            
            return [
                'class' => $class->name,
                'student_count' => $students->count(),
                'average' => $avg,
                'pass_rate' => $passRate,
                'total_results' => $total,
                'grade_distribution' => $gradeDist,
                'highest_score' => $results->max('score') ?? 0,
                'lowest_score' => $results->min('score') ?? 0,
            ];
        });

        // Subject performance with filters
        $subjects = Subject::with('results')
            ->when($selectedSubject, function($query) use ($selectedSubject) {
                $query->where('name', $selectedSubject);
            })
            ->get();
        
        $subjectStats = $subjects->map(function($subject) use ($selectedClass, $selectedCombination) {
            $results = $subject->results;
            
            // Apply class filter
            if ($selectedClass) {
                $results = $results->filter(function($result) use ($selectedClass) {
                    return $result->student->class->name === $selectedClass;
                });
            }
            
            // Apply combination filter
            if ($selectedCombination) {
                $results = $results->filter(function($result) use ($selectedCombination) {
                    return $result->student->combination === $selectedCombination;
                });
            }
            
            $avg = $results->avg('score') ?? 0;
            $pass = $results->where('score', '>=', 50)->count();
            $total = $results->count();
            $passRate = $total > 0 ? round(($pass / $total) * 100, 2) : 0;
            
            // Grade distribution for this subject
            $gradeDist = [
                'A' => $results->whereBetween('score', [80, 100])->count(),
                'B' => $results->whereBetween('score', [70, 79])->count(),
                'C' => $results->whereBetween('score', [60, 69])->count(),
                'D' => $results->whereBetween('score', [50, 59])->count(),
                'F' => $results->whereBetween('score', [0, 49])->count(),
            ];
            
            return [
                'subject' => $subject->name,
                'average' => $avg,
                'pass_rate' => $passRate,
                'total_results' => $total,
                'grade_distribution' => $gradeDist,
                'highest_score' => $results->max('score') ?? 0,
                'lowest_score' => $results->min('score') ?? 0,
            ];
        });

        // Term-wise performance with filters
        $termStats = $baseQuery->clone()
            ->select('term', 
                DB::raw('AVG(score) as average'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN score >= 50 THEN 1 ELSE 0 END) as pass_count')
            )
            ->groupBy('term')
            ->get()
            ->map(function($term) {
                return [
                    'term' => ucfirst($term->term ?? 'Unknown'),
                    'average' => round($term->average ?? 0, 2),
                    'total' => $term->total ?? 0,
                    'pass_rate' => $term->total > 0 ? round(($term->pass_count / $term->total) * 100, 2) : 0,
                ];
            });

        // Gender-based performance with filters
        $genderStats = Student::with(['user', 'results'])
            ->when($selectedClass, function($query) use ($selectedClass) {
                $query->whereHas('class', function($q) use ($selectedClass) {
                    $q->where('name', $selectedClass);
                });
            })
            ->when($selectedCombination, function($query) use ($selectedCombination) {
                $query->where('combination', $selectedCombination);
            })
            ->get()
            ->groupBy('gender')
            ->map(function($students, $gender) use ($selectedSubject) {
                $allResults = $students->flatMap->results;
                
                // Apply subject filter
                if ($selectedSubject) {
                    $allResults = $allResults->filter(function($result) use ($selectedSubject) {
                        return $result->subject->name === $selectedSubject;
                    });
                }
                
                return [
                    'gender' => ucfirst($gender ?? 'Unknown'),
                    'student_count' => $students->count(),
                    'average' => $allResults->avg('score') ?? 0,
                    'pass_rate' => $allResults->count() > 0 ? 
                        round(($allResults->where('score', '>=', 50)->count() / $allResults->count()) * 100, 2) : 0,
                ];
            });

        // Performance improvement analysis with filters
        $improvementStats = Student::with(['results' => function($query) {
            $query->orderBy('created_at');
        }])
        ->when($selectedClass, function($query) use ($selectedClass) {
            $query->whereHas('class', function($q) use ($selectedClass) {
                $q->where('name', $selectedClass);
            });
        })
        ->when($selectedCombination, function($query) use ($selectedCombination) {
            $query->where('combination', $selectedCombination);
        })
        ->get()
        ->map(function($student) use ($selectedSubject) {
            $results = $student->results;
            
            // Apply subject filter
            if ($selectedSubject) {
                $results = $results->filter(function($result) use ($selectedSubject) {
                    return $result->subject->name === $selectedSubject;
                });
            }
            
            if ($results->count() < 2) return null;
            
            $firstScore = $results->first()->score ?? 0;
            $lastScore = $results->last()->score ?? 0;
            $improvement = $lastScore - $firstScore;
            
            return [
                'name' => $student->user->name ?? 'Unknown',
                'admission_number' => $student->admission_number ?? 'N/A',
                'class' => $student->class->name ?? 'N/A',
                'combination' => $student->combination ?? 'N/A',
                'first_score' => $firstScore,
                'last_score' => $lastScore,
                'improvement' => $improvement,
                'improvement_percentage' => $firstScore > 0 ? round(($improvement / $firstScore) * 100, 2) : 0,
            ];
        })
        ->filter()
        ->sortByDesc('improvement')
        ->take(10);

        // Get available filter options
        $availableClasses = SchoolClass::whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'])->get();
        $availableCombinations = Student::getAvailableCombinations();
        $availableSubjects = Subject::all();

        return view('admin.school-performance.index', compact(
            'totalStudents', 'totalResults', 'averageScore', 'passRate', 'failRate',
            'topStudents', 'classStats', 'subjectStats', 'gradeDistribution',
            'trends', 'termStats', 'genderStats', 'improvementStats',
            'selectedClass', 'selectedCombination', 'selectedSubject',
            'availableClasses', 'availableCombinations', 'availableSubjects'
        ));
    }
} 