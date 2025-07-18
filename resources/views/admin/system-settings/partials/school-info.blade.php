<form action="{{ route('settings.update') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">School Name</label>
            <input type="text" name="school_name" class="form-control" value="{{ $settings->where('key','school_name')->first()->value ?? '' }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Motto</label>
            <input type="text" name="school_motto" class="form-control" value="{{ $settings->where('key','school_motto')->first()->value ?? '' }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" name="school_address" class="form-control" value="{{ $settings->where('key','school_address')->first()->value ?? '' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Phone</label>
            <input type="text" name="school_phone" class="form-control" value="{{ $settings->where('key','school_phone')->first()->value ?? '' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Email</label>
            <input type="email" name="school_email" class="form-control" value="{{ $settings->where('key','school_email')->first()->value ?? '' }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Website</label>
            <input type="text" name="school_website" class="form-control" value="{{ $settings->where('key','school_website')->first()->value ?? '' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Academic Year</label>
            <input type="text" name="academic_year" class="form-control" value="{{ $settings->where('key','academic_year')->first()->value ?? '' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Term Dates</label>
            <input type="text" name="term_dates" class="form-control" value="{{ $settings->where('key','term_dates')->first()->value ?? '' }}">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save School Info</button>
</form> 