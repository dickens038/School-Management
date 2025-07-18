@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Permissions</label>
            @foreach($permissions as $perm)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                    <label class="form-check-label" for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-success">Create Role</button>
    </form>
</div>
@endsection 