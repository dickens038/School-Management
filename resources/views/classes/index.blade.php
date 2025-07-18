@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Classes</h1>
    <a href="{{ route('classes.create') }}" class="btn btn-primary mb-3">Add Class</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $class)
                <tr>
                    <td>{{ $class->id }}</td>
                    <td>{{ $class->name }}</td>
                    <td>
                        <a href="{{ route('classes.show', $class) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('classes.destroy', $class) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $classes->links() }}
</div>
@endsection 