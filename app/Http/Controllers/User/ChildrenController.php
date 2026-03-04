<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Website;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChildrenController extends Controller
{
    /**
     * Display a listing of children/individuals managed by parent.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is a parent
        if ($user->role !== 'parents') {
            return redirect()->back()->with('error', 'Only parents can access this page');
        }

        $children = User::where('parent_id', $user->id)
            ->with('teacher')
            ->latest()
            ->get();

        return view('users.children.index', compact('children'));
    }

    /**
     * Show the form for creating a new individual.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user is a parent
        if ($user->role !== 'parents') {
            return redirect()->back()->with('error', 'Only parents can access this page');
        }

        $website = Website::where('user_id', $user->website_id)->first();
        $teachers = Teacher::where('website_id', $user->website_id)
            ->where('is_active', true)
            ->get();

        return view('users.children.create', compact('teachers'));
    }

    /**
     * Store a newly created individual.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is a parent
        if ($user->role !== 'parents') {
            return redirect()->back()->with('error', 'Only parents can access this page');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'individual',
            'teacher_id' => $request->teacher_id,
            'parent_id' => $user->id,
            'website_id' => $user->website_id,
        ]);

        return redirect()->route('users.children.index')->with('success', 'Individual created successfully');
    }

    /**
     * Show the form for editing an individual.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $child = User::findOrFail($id);

        // Check if user is a parent and owns this child
        if ($user->role !== 'parents' || $child->parent_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $teachers = Teacher::where('website_id', $user->website_id)
            ->where('is_active', true)
            ->get();

        return view('users.children.edit', compact('child', 'teachers'));
    }

    /**
     * Update the specified individual.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $child = User::findOrFail($id);

        // Check if user is a parent and owns this child
        if ($user->role !== 'parents' || $child->parent_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $data = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'teacher_id' => $request->teacher_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $child->update($data);

        return redirect()->route('users.children.index')->with('success', 'Individual updated successfully');
    }

    /**
     * Remove the specified individual.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $child = User::findOrFail($id);

        // Check if user is a parent and owns this child
        if ($user->role !== 'parents' || $child->parent_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $child->delete();

        return redirect()->route('users.children.index')->with('success', 'Individual deleted successfully');
    }
}
