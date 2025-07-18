@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Gallery Images</h1>
    <a href="{{ route('gallery-images.create') }}" class="btn btn-primary mb-3">Add Image</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row g-3">
        @foreach($galleryImages as $image)
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/gallery/' . $image->image_path) }}" class="card-img-top" alt="{{ $image->caption }}">
                    <div class="card-body">
                        <p class="card-text">{{ $image->caption }}</p>
                        <a href="{{ route('gallery-images.edit', $image) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('gallery-images.destroy', $image) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $galleryImages->links() }}</div>
</div>
@endsection 