<div class="alert alert-info">Role and permission management tools will be available here. (Feature coming soon)</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Role</th>
            <th>Permissions</th>
            <th>Users</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{ ucfirst($role->name) }}</td>
                <td>
                    @foreach($role->permissions as $perm)
                        <span class="badge bg-info">{{ $perm->name }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach($role->users as $user)
                        <span class="badge bg-secondary">{{ $user->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('roles.create') }}" class="btn btn-success mt-2">Add New Role</a> 