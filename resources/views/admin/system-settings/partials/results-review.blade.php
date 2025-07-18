@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<h5>Pending Results for Approval</h5>
<form action="{{ route('settings.results.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <label for="results_csv" class="form-label mb-0">Upload Results (CSV):</label>
        </div>
        <div class="col-auto">
            <input type="file" name="results_csv" id="results_csv" class="form-control" accept=".csv" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Student</th>
            <th>Subject</th>
            <th>Score</th>
            <th>Term</th>
            <th>Year</th>
            <th>Teacher</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendingResults as $result)
            <tr>
                <td>{{ $result->student->user->name ?? '-' }}</td>
                <td>{{ $result->subject->name ?? '-' }}</td>
                <td>{{ $result->score }}</td>
                <td>{{ ucfirst($result->term) }}</td>
                <td>{{ $result->year }}</td>
                <td>{{ $result->teacher->user->name ?? '-' }}</td>
                <td>
                    <form action="{{ route('settings.results.approve', $result->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No pending results.</td></tr>
        @endforelse
    </tbody>
</table> 