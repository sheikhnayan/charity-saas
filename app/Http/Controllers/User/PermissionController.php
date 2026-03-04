<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('group')->orderBy('label')->get();
        $permissionGroups = $permissions->groupBy('group');
        $roles = Role::with('permissions')->orderBy('name')->get();
        
        return view('user.permissions.index', compact('permissions', 'permissionGroups', 'roles'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array'
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permissions);

        return back()->with('success', 'Permissions assigned successfully');
    }
}
