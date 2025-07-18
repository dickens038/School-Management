<form action="{{ route('settings.update') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Default Language</label>
            <input type="text" name="default_language" class="form-control" value="{{ $settings->where('key','default_language')->first()->value ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Timezone</label>
            <input type="text" name="timezone" class="form-control" value="{{ $settings->where('key','timezone')->first()->value ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Grading System</label>
            <input type="text" name="grading_system" class="form-control" value="{{ $settings->where('key','grading_system')->first()->value ?? '' }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Notification Email</label>
        <input type="email" name="notification_email" class="form-control" value="{{ $settings->where('key','notification_email')->first()->value ?? '' }}">
    </div>
    <button type="submit" class="btn btn-primary">Save Preferences</button>
</form> 