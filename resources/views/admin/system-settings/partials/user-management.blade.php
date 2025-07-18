<div class="alert alert-info">User account management tools (reset password, lock/unlock, deactivate, etc.) will be available here. (Feature coming soon)</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role->name ?? '') }}</td>
                <td>
                    @if($user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('users.reset-password', $user->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-warning">Reset Password</button></form>
                    <form action="{{ route('users.toggle-lock', $user->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-info">{{ $user->is_locked ? 'Unlock' : 'Lock' }}</button></form>
                    <form action="{{ route('users.toggle-active', $user->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-secondary">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table> 