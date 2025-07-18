@extends('layouts.app')

<!-- Font Awesome for Professional Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@section('content')
<div class="container-fluid">
    <!-- Professional Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0">School Performance Analytics</h1>
                    <p class="text-muted mb-0">Comprehensive performance analysis and insights</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="printReport()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Analytics Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.school-performance.index') }}" class="row">
                        <div class="col-md-3">
                            <label for="class" class="form-label">Filter by Class</label>
                            <select class="form-select" id="class" name="class">
                                <option value="">All Classes</option>
                                @foreach($availableClasses as $class)
                                    <option value="{{ $class->name }}" {{ $selectedClass == $class->name ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="combination" class="form-label">Filter by Combination</label>
                            <select class="form-select" id="combination" name="combination">
                                <option value="">All Combinations</option>
                                @if(isset($availableCombinations['Advanced Level']))
                                    <optgroup label="Advanced Level">
                                        @foreach($availableCombinations['Advanced Level'] as $key => $value)
                                            <option value="{{ $key }}" {{ $selectedCombination == $key ? 'selected' : '' }}>
                                                {{ $key }} - {{ $value }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if(isset($availableCombinations['Ordinary Level']))
                                    <optgroup label="Ordinary Level">
                                        @foreach($availableCombinations['Ordinary Level'] as $key => $value)
                                            <option value="{{ $key }}" {{ $selectedCombination == $key ? 'selected' : '' }}>
                                                {{ $key }} - {{ $value }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="subject" class="form-label">Filter by Subject</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="">All Subjects</option>
                                @foreach($availableSubjects as $subject)
                                    <option value="{{ $subject->name }}" {{ $selectedSubject == $subject->name ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Apply Filters
                                </button>
                                <a href="{{ route('admin.school-performance.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    @if($selectedClass || $selectedCombination || $selectedSubject)
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <strong>Active Filters:</strong>
                                @if($selectedClass) <span class="badge bg-primary">{{ $selectedClass }}</span> @endif
                                @if($selectedCombination) <span class="badge bg-success">{{ $selectedCombination }}</span> @endif
                                @if($selectedSubject) <span class="badge bg-warning">{{ $selectedSubject }}</span> @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Summary -->
    @if($selectedClass || $selectedCombination || $selectedSubject)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtered Analytics Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-info">{{ $totalStudents }}</div>
                                    <small class="text-muted">Filtered Students</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-success">{{ $totalResults }}</div>
                                    <small class="text-muted">Filtered Results</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-warning">{{ number_format($averageScore, 1) }}</div>
                                    <small class="text-muted">Filtered Average</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-primary">{{ $passRate }}%</div>
                                    <small class="text-muted">Filtered Pass Rate</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Executive Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Executive Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="display-6 text-primary">{{ $totalStudents }}</div>
                                <small class="text-muted">Total Students</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="display-6 text-success">{{ $totalResults }}</div>
                                <small class="text-muted">Total Results</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="display-6 text-info">{{ number_format($averageScore, 1) }}</div>
                                <small class="text-muted">Average Score</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="display-6 text-warning">{{ $passRate }}%</div>
                                <small class="text-muted">Pass Rate</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="display-6 text-danger">{{ $failRate }}%</div>
                                <small class="text-muted">Fail Rate</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <div class="display-6 text-secondary">{{ number_format(($passRate * $averageScore) / 100, 1) }}</div>
                                <small class="text-muted">Success Index</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Grade Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="gradeChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Performance Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Analysis Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="performanceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                                <i class="fas fa-users"></i> Top Students
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="improvement-tab" data-bs-toggle="tab" data-bs-target="#improvement" type="button" role="tab">
                                <i class="fas fa-chart-line"></i> Improvement Analysis
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab">
                                <i class="fas fa-graduation-cap"></i> Class Performance
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab">
                                <i class="fas fa-book"></i> Subject Analysis
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="performanceTabContent">
                        <!-- Top Students Tab -->
                        <div class="tab-pane fade show active" id="students" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Student Name</th>
                                            <th>Admission No.</th>
                                            <th>Class</th>
                                            <th>Combination</th>
                                            <th>Average Score</th>
                                            <th>Pass Rate</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topStudents as $i => $student)
                                            <tr>
                                                <td>
                                                    @if($i < 3)
                                                        <span class="badge bg-warning">{{ $i+1 }}</span>
                                                    @else
                                                        {{ $i+1 }}
                                                    @endif
                                                </td>
                                                <td><strong>{{ $student['name'] }}</strong></td>
                                                <td>{{ $student['admission_number'] }}</td>
                                                <td>{{ $student['class'] }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $student['combination'] }}</span>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $student['average'] }}%">
                                                            {{ number_format($student['average'], 1) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student['total_results'] > 0 ? round(($student['pass_count'] / $student['total_results']) * 100, 1) : 0 }}%</td>
                                                <td>
                                                    @if($student['average'] >= 80)
                                                        <span class="badge bg-success">Excellent</span>
                                                    @elseif($student['average'] >= 70)
                                                        <span class="badge bg-info">Good</span>
                                                    @elseif($student['average'] >= 60)
                                                        <span class="badge bg-warning">Average</span>
                                                    @else
                                                        <span class="badge bg-danger">Needs Improvement</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Improvement Analysis Tab -->
                        <div class="tab-pane fade" id="improvement" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            <th>Combination</th>
                                            <th>First Score</th>
                                            <th>Last Score</th>
                                            <th>Improvement</th>
                                            <th>% Change</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($improvementStats as $i => $student)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td><strong>{{ $student['name'] }}</strong></td>
                                                <td>{{ $student['class'] }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $student['combination'] }}</span>
                                                </td>
                                                <td>{{ $student['first_score'] }}</td>
                                                <td>{{ $student['last_score'] }}</td>
                                                <td>
                                                    <span class="badge {{ $student['improvement'] > 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $student['improvement'] > 0 ? '+' : '' }}{{ $student['improvement'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $student['improvement_percentage'] }}%</td>
                                                <td>
                                                    @if($student['improvement'] > 0)
                                                        <i class="fas fa-arrow-up text-success"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down text-danger"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Class Performance Tab -->
                        <div class="tab-pane fade" id="classes" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Class</th>
                                            <th>Students</th>
                                            <th>Average Score</th>
                                            <th>Pass Rate</th>
                                            <th>Grade Distribution</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classStats as $stat)
                                            <tr>
                                                <td><strong>{{ $stat['class'] }}</strong></td>
                                                <td>{{ $stat['student_count'] }}</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-info" style="width: {{ $stat['average'] }}%">
                                                            {{ number_format($stat['average'], 1) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $stat['pass_rate'] }}%</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-success">{{ $stat['grade_distribution']['A'] }}A</span>
                                                        <span class="badge bg-info">{{ $stat['grade_distribution']['B'] }}B</span>
                                                        <span class="badge bg-warning">{{ $stat['grade_distribution']['C'] }}C</span>
                                                        <span class="badge bg-secondary">{{ $stat['grade_distribution']['D'] }}D</span>
                                                        <span class="badge bg-danger">{{ $stat['grade_distribution']['F'] }}F</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($stat['average'] >= 80)
                                                        <span class="badge bg-success">Excellent</span>
                                                    @elseif($stat['average'] >= 70)
                                                        <span class="badge bg-info">Good</span>
                                                    @elseif($stat['average'] >= 60)
                                                        <span class="badge bg-warning">Average</span>
                                                    @else
                                                        <span class="badge bg-danger">Needs Improvement</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Subject Analysis Tab -->
                        <div class="tab-pane fade" id="subjects" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Average Score</th>
                                            <th>Pass Rate</th>
                                            <th>Total Results</th>
                                            <th>Grade Distribution</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjectStats as $stat)
                                            <tr>
                                                <td><strong>{{ $stat['subject'] }}</strong></td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-primary" style="width: {{ $stat['average'] }}%">
                                                            {{ number_format($stat['average'], 1) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $stat['pass_rate'] }}%</td>
                                                <td>{{ $stat['total_results'] }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-success">{{ $stat['grade_distribution']['A'] }}A</span>
                                                        <span class="badge bg-info">{{ $stat['grade_distribution']['B'] }}B</span>
                                                        <span class="badge bg-warning">{{ $stat['grade_distribution']['C'] }}C</span>
                                                        <span class="badge bg-secondary">{{ $stat['grade_distribution']['D'] }}D</span>
                                                        <span class="badge bg-danger">{{ $stat['grade_distribution']['F'] }}F</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($stat['average'] >= 80)
                                                        <span class="badge bg-success">Excellent</span>
                                                    @elseif($stat['average'] >= 70)
                                                        <span class="badge bg-info">Good</span>
                                                    @elseif($stat['average'] >= 60)
                                                        <span class="badge bg-warning">Average</span>
                                                    @else
                                                        <span class="badge bg-danger">Needs Improvement</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Term Performance</h5>
                </div>
                <div class="card-body">
                    <canvas id="termChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-venus-mars"></i> Gender Performance</h5>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights and Recommendations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Key Insights & Recommendations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Top Performers</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-trophy text-warning"></i> <strong>Best Class:</strong> {{ $classStats->sortByDesc('average')->first()['class'] ?? 'N/A' }}</li>
                                <li><i class="fas fa-medal text-success"></i> <strong>Best Subject:</strong> {{ $subjectStats->sortByDesc('average')->first()['subject'] ?? 'N/A' }}</li>
                                <li><i class="fas fa-star text-info"></i> <strong>Top Student:</strong> {{ $topStudents->first()['name'] ?? 'N/A' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger">Areas for Improvement</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning"></i> <strong>Lowest Class:</strong> {{ $classStats->sortBy('average')->first()['class'] ?? 'N/A' }}</li>
                                <li><i class="fas fa-exclamation-circle text-danger"></i> <strong>Challenging Subject:</strong> {{ $subjectStats->sortBy('average')->first()['subject'] ?? 'N/A' }}</li>
                                <li><i class="fas fa-chart-line text-info"></i> <strong>Overall Trend:</strong> 
                                    @if($trends->count() > 1)
                                        {{ $trends->last()['average'] > $trends->first()['average'] ? 'Improving' : 'Declining' }}
                                    @else
                                        Insufficient data
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Professional Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Professional Color Scheme
    const colors = {
        primary: '#007bff',
        success: '#28a745',
        warning: '#ffc107',
        danger: '#dc3545',
        info: '#17a2b8',
        secondary: '#6c757d',
        dark: '#343a40'
    };

    // Grade Distribution Chart
    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
    new Chart(gradeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($gradeDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($gradeDistribution)) !!},
                backgroundColor: [
                    colors.success, // A
                    colors.info,    // B
                    colors.warning, // C
                    colors.secondary, // D
                    colors.danger   // F
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Performance Trends Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trends->count() > 0 ? $trends->pluck('month') : []) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($trends->count() > 0 ? $trends->pluck('average') : []) !!},
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }, {
                label: 'Pass Rate (%)',
                data: {!! json_encode($trends->count() > 0 ? $trends->pluck('pass_rate') : []) !!},
                borderColor: colors.success,
                backgroundColor: colors.success + '20',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colors.success,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: '#e9ecef'
                    }
                },
                x: {
                    grid: {
                        color: '#e9ecef'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Term Performance Chart
    const termCtx = document.getElementById('termChart').getContext('2d');
    new Chart(termCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($termStats->count() > 0 ? $termStats->pluck('term') : []) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($termStats->count() > 0 ? $termStats->pluck('average') : []) !!},
                backgroundColor: colors.info,
                borderColor: colors.info,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Gender Performance Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($genderStats->count() > 0 ? $genderStats->pluck('gender') : []) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($genderStats->count() > 0 ? $genderStats->pluck('average') : []) !!},
                backgroundColor: [colors.primary, colors.success],
                borderColor: [colors.primary, colors.success],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});

// Export Functions
function exportToPDF() {
    window.print();
}

function exportToExcel() {
    // Implementation for Excel export
    alert('Excel export functionality will be implemented');
}

function printReport() {
    window.print();
}
</script>

<!-- Professional Print Styles -->
<style>
@media print {
    .btn-group, .nav-tabs, .card-header {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .container-fluid {
        width: 100% !important;
        max-width: none !important;
    }
}
</style>
@endsection 