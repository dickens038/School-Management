<div class="alert alert-info">Audit logs and system logs will be displayed here. (Feature coming soon)</div>

<h5>System Logs</h5>
<ul class="list-group mb-3">
    @foreach($logs as $log)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ basename($log) }}
            <a href="{{ route('settings.logs.view', ['file' => basename($log)]) }}" class="btn btn-sm btn-outline-primary">View</a>
        </li>
    @endforeach
</ul>
@if(isset($logContent))
    <div class="card">
        <div class="card-header">Log Content</div>
        <div class="card-body" style="max-height:300px;overflow:auto;white-space:pre-wrap;">
            <code>{{ $logContent }}</code>
        </div>
    </div>
@endif 