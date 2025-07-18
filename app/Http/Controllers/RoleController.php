<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function updatePermissions(Request $request, Role $role)
    {
        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);
        return back()->with('success', 'Permissions updated for role.');
    }

    public function index()
    {
        $roles = \App\Models\Role::with('permissions', 'users')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = \App\Models\Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);
        $role = \App\Models\Role::create(['name' => $request->name]);
        $role->permissions()->sync($request->permissions ?? []);
        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    public function show(\App\Models\Role $role)
    {
        $role->load('permissions', 'users');
        return view('roles.show', compact('role'));
    }

    public function edit(\App\Models\Role $role)
    {
        $permissions = \App\Models\Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, \App\Models\Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);
        $role->name = $request->name;
        $role->save();
        $role->permissions()->sync($request->permissions ?? []);
        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    public function destroy(\App\Models\Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}
