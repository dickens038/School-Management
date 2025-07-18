@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Role: {{ $role->name }}</h1>
    <h4>Permissions</h4>
    <ul>
        @foreach($role->permissions as $perm)
            <li>{{ $perm->name }}</li>
        @endforeach
    </ul>
    <h4>Users</h4>
    <ul>
        @foreach($role->users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @endforeach
    </ul>
    <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">Edit Role</a>
</div>
@endsection 