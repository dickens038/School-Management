@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage News</h1>
    <a href="{{ route('news.create') }}" class="btn btn-primary mb-3">Add News</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Published At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->published_at ? $item->published_at->format('Y-m-d') : '-' }}</td>
                    <td>
                        <a href="{{ route('news.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('news.destroy', $item) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $news->links() }}
</div>
@endsection 