<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">School Logo</label><br>
        @if($settings->where('key','school_logo')->first())
            <img src="{{ asset('storage/branding/' . $settings->where('key','school_logo')->first()->value) }}" alt="Logo" style="height:60px;">
        @endif
        <input type="file" name="school_logo" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
        <label class="form-label">Favicon</label><br>
        @if($settings->where('key','school_favicon')->first())
            <img src="{{ asset('storage/branding/' . $settings->where('key','school_favicon')->first()->value) }}" alt="Favicon" style="height:32px;">
        @endif
        <input type="file" name="school_favicon" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Save Branding</button>
</form> 