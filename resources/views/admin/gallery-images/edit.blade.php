@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Gallery Image</h1>
    <form action="{{ route('gallery-images.update', $galleryImage) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="image_path" class="form-label">Image</label><br>
            @if($galleryImage->image_path)
                <img src="{{ asset('storage/gallery/' . $galleryImage->image_path) }}" alt="Current Image" class="rounded mb-2" style="width: 120px; height: 120px; object-fit: cover;">
            @endif
            <input type="file" name="image_path" id="image_path" class="form-control" accept="image/*">
            @error('image_path')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <input type="text" name="caption" id="caption" class="form-control" value="{{ old('caption', $galleryImage->caption) }}">
            @error('caption')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Image</button>
        <a href="{{ route('gallery-images.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 