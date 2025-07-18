<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$this->isHeadmasterOrIT()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $departments = Department::with('users')->paginate(15);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);
        Department::create(['name' => $request->name]);
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);
        $department->name = $request->name;
        $department->save();
        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function assignUsers(Department $department)
    {
        $users = User::all();
        $assigned = $department->users->pluck('id')->toArray();
        return view('departments.assign-users', compact('department', 'users', 'assigned'));
    }

    public function storeAssignedUsers(Request $request, Department $department)
    {
        $userIds = $request->input('user_ids', []);
        $department->users()->sync($userIds);
        return redirect()->route('departments.index')->with('success', 'Users assigned to department successfully.');
    }
} 