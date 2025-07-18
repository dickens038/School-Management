@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">IT System Settings</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <ul class="nav nav-tabs mb-4" id="settingsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="school-tab" data-bs-toggle="tab" data-bs-target="#school" type="button" role="tab">School Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">User Management</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Roles & Permissions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="prefs-tab" data-bs-toggle="tab" data-bs-target="#prefs" type="button" role="tab">Preferences</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button" role="tab">Backup</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">Logs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">Branding</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="announce-tab" data-bs-toggle="tab" data-bs-target="#announce" type="button" role="tab">Announcements</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="results-review-tab" data-bs-toggle="tab" data-bs-target="#results-review" type="button" role="tab">Results Review</button>
        </li>
    </ul>
    <div class="tab-content" id="settingsTabContent">
        <div class="tab-pane fade show active" id="school" role="tabpanel">
            @include('admin.system-settings.partials.school-info', ['settings' => $settings['school'] ?? collect()])
        </div>
        <div class="tab-pane fade" id="users" role="tabpanel">
            @include('admin.system-settings.partials.user-management')
        </div>
        <div class="tab-pane fade" id="roles" role="tabpanel">
            @include('admin.system-settings.partials.roles-permissions')
        </div>
        <div class="tab-pane fade" id="prefs" role="tabpanel">
            @include('admin.system-settings.partials.preferences', ['settings' => $settings['preferences'] ?? collect()])
        </div>
        <div class="tab-pane fade" id="backup" role="tabpanel">
            @include('admin.system-settings.partials.backup')
        </div>
        <div class="tab-pane fade" id="logs" role="tabpanel">
            @include('admin.system-settings.partials.logs')
        </div>
        <div class="tab-pane fade" id="branding" role="tabpanel">
            @include('admin.system-settings.partials.branding', ['settings' => $settings['branding'] ?? collect()])
        </div>
        <div class="tab-pane fade" id="announce" role="tabpanel">
            @include('admin.system-settings.partials.announcements', ['settings' => $settings['announcements'] ?? collect()])
        </div>
        <div class="tab-pane fade" id="results-review" role="tabpanel">
            @include('admin.system-settings.partials.results-review')
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Activate tab from hash on page load
    if (window.location.hash) {
        const tabTrigger = document.querySelector(`button[data-bs-target='${window.location.hash}']`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
    // Update hash when tab is shown
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function (tab) {
        tab.addEventListener('shown.bs.tab', function (e) {
            history.replaceState(null, null, e.target.dataset.bsTarget);
        });
    });
});
</script>
@endsection 