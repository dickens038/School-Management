@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Department Management</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Add Department</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Users</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
                <tr>
                    <td>{{ $department->name }}</td>
                    <td>
                        @foreach($department->users as $user)
                            <span class="badge bg-secondary">{{ $user->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('departments.assign-users', $department) }}" class="btn btn-sm btn-info">Assign Users</a>
                        <form action="{{ route('departments.destroy', $department) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $departments->links() }}
</div>
@endsection 