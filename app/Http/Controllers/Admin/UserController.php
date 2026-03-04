<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create()
    {
        $websites = Website::all();
        $roles = \App\Models\Role::orderBy('name')->get();
        return view('admin.users.create', compact('websites', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
            'website_id' => 'nullable|exists:websites,id'
        ]);

        $current = Auth::user();

        // Authorization: allow if current user is superadmin or website_owner for target website
        $targetWebsite = $request->input('website_id');
        $allowed = false;
        if ($current->hasRoleForWebsite('superadmin', null)) {
            $allowed = true;
        } elseif ($current->hasRoleForWebsite('website_owner', $targetWebsite)) {
            $allowed = true;
        }

        if (!$allowed) {
            return back()->withErrors(['unauthorized' => 'You are not authorized to create users for this website'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'website_id' => $request->website_id,
            'status' => 'active',
        ]);

        // Assign roles for website scope (supports multiple roles)
        $selectedRoles = $request->input('roles', []);
        foreach ($selectedRoles as $r) {
            $user->assignRoleForWebsite($r, $request->website_id ?: null);
        }

        return redirect()->route('admin.index')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $websites = Website::all();
        $roles = \App\Models\Role::orderBy('name')->get();

        // collect role names assigned for the user's website scope
        $userRoles = \DB::table('role_user_website')
            ->where('user_id', $user->id)
            ->pluck('role_id', 'website_id');

        // build a set of role names for the specific website (null means global)
        $assigned = \DB::table('role_user_website')
            ->where('user_id', $user->id)
            ->where(function($q) use ($user) {
                $q->whereNull('website_id')->orWhere('website_id', $user->website_id);
            })
            ->pluck('role_id')
            ->toArray();

        $assignedNames = [];
        if (!empty($assigned)) {
            $roleModels = \App\Models\Role::whereIn('id', $assigned)->get();
            $assignedNames = $roleModels->pluck('name')->toArray();
        }

        return view('admin.users.edit', compact('user', 'websites', 'roles', 'assignedNames'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
            'website_id' => 'nullable|exists:websites,id'
        ]);

        $current = Auth::user();
        $targetWebsite = $request->input('website_id');
        $allowed = false;
        if ($current->hasRoleForWebsite('superadmin', null)) {
            $allowed = true;
        } elseif ($current->hasRoleForWebsite('website_owner', $targetWebsite)) {
            $allowed = true;
        }

        if (!$allowed) {
            return back()->withErrors(['unauthorized' => 'You are not authorized to update users for this website'])->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->website_id = $request->website_id;
        $user->save();

        // Sync roles for this website scope
        $selectedRoles = $request->input('roles', []);
        $user->syncRolesForWebsite($selectedRoles, $request->website_id ?: null);

        return redirect()->route('admin.index')->with('success', 'User updated successfully');
    }
}
