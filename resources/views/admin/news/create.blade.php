@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add News</h1>
    <form action="{{ route('news.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            <textarea name="body" id="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
            @error('body')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Publish Date</label>
            <input type="date" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
            @error('published_at')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Create News</button>
        <a href="{{ route('news.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 