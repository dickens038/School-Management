@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Class Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $class->name }}</h5>
            <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('classes.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection 