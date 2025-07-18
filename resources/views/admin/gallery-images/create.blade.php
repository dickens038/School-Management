@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add Gallery Image</h1>
    <form action="{{ route('gallery-images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image_path" class="form-label">Image</label>
            <input type="file" name="image_path" id="image_path" class="form-control" accept="image/*" required>
            @error('image_path')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <input type="text" name="caption" id="caption" class="form-control" value="{{ old('caption') }}">
            @error('caption')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Upload Image</button>
        <a href="{{ route('gallery-images.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 