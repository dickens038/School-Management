@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Assign Users to {{ $department->name }}</h1>
    <form action="{{ route('departments.store-assigned-users', $department) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Select Users</label>
            <div class="row">
                @foreach($users as $user)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="user{{ $user->id }}" {{ in_array($user->id, $assigned) ? 'checked' : '' }}>
                            <label class="form-check-label" for="user{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }})
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Assignments</button>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 