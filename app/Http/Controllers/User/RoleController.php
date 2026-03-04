<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('user.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('label')->get();
        $permissionGroups = $permissions->groupBy('group');
        return view('user.roles.create', compact('permissions', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'label' => 'nullable|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'label' => $request->input('label') ?: ucfirst($request->input('name'))
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }

        return redirect()->route('users.roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('group')->orderBy('label')->get();
        $permissionGroups = $permissions->groupBy('group');
        $assignedPermissions = $role->permissions->pluck('id')->toArray();
        
        return view('user.roles.edit', compact('role', 'permissions', 'permissionGroups', 'assignedPermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'label' => 'nullable|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $role->update([
            'name' => $request->input('name'),
            'label' => $request->input('label') ?: ucfirst($request->input('name'))
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('users.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('users.roles.index')->with('success', 'Role deleted successfully');
    }
}
