<form action="{{ route('settings.update') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">System Announcement</label>
        <textarea name="system_announcement" class="form-control" rows="3">{{ $settings->where('key','system_announcement')->first()->value ?? '' }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Announcement</button>
</form> 