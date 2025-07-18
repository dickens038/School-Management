@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">My Results <small class="text-muted">({{ $selectedYear }})</small></h1>
    <form method="GET" class="mb-3 d-flex align-items-center" action="">
        <label for="year" class="me-2">Academic Year:</label>
        <select name="year" id="year" class="form-select w-auto me-2" onchange="this.form.submit()">
            @foreach($years as $year)
                <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
            @endforeach
        </select>
    </form>
    <ul class="nav nav-tabs mb-3" id="resultTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">Monthly Test</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="midterm-tab" data-bs-toggle="tab" data-bs-target="#midterm" type="button" role="tab">Midterm Exams</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="final-tab" data-bs-toggle="tab" data-bs-target="#final" type="button" role="tab">Final Exams</button>
        </li>
    </ul>
    <div class="tab-content" id="resultTabsContent">
        <div class="tab-pane fade show active" id="monthly" role="tabpanel">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly as $result)
                        <tr>
                            <td>{{ $result->subject->name ?? '-' }}</td>
                            <td>{{ $result->score }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">No monthly test results.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="midterm" role="tabpanel">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($midterm as $result)
                        <tr>
                            <td>{{ $result->subject->name ?? '-' }}</td>
                            <td>{{ $result->score }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">No midterm exam results.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="final" role="tabpanel">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($final as $result)
                        <tr>
                            <td>{{ $result->subject->name ?? '-' }}</td>
                            <td>{{ $result->score }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">No final exam results.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.location.hash) {
        const tabTrigger = document.querySelector(`button[data-bs-target='${window.location.hash}']`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function (tab) {
        tab.addEventListener('shown.bs.tab', function (e) {
            history.replaceState(null, null, e.target.dataset.bsTarget);
        });
    });
});
</script>
@endsection 