<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of websites to select from
     */
    public function websites()
    {
        $data = Website::all();
        return view('admin.teachers.websites', compact('data'));
    }

    /**
     * Display a listing of teachers for a specific website
     */
    public function index($websiteId = null)
    {
        if ($websiteId) {
            $website = Website::findOrFail($websiteId);
            $teachers = Teacher::where('website_id', $websiteId)
                ->withCount('students')
                ->latest()
                ->get();
            return view('admin.teachers.index', compact('teachers', 'website'));
        }
        
        // Fallback to all teachers if no website specified
        $teachers = Teacher::with('website')->withCount('students')->latest()->get();
        $website = null;
        return view('admin.teachers.index', compact('teachers', 'website'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($websiteId)
    {
        $website = Website::findOrFail($websiteId);
        return view('admin.teachers.create', compact('website'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'website_id' => 'required|exists:websites,id',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'website_id' => $request->website_id,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('teachers', $filename, 'public');
            $data['photo'] = $path;
        }

        Teacher::create($data);

        return redirect()->route('admin.teachers.index', $request->website_id)->with('success', 'Teacher created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::with('website')->findOrFail($id);
        $website = $teacher->website;
        
        return view('admin.teachers.edit', compact('teacher', 'website'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('teachers', $filename, 'public');
            $data['photo'] = $path;
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index', $teacher->website_id)->with('success', 'Teacher updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $websiteId = $teacher->website_id;
        
        // Delete photo if exists
        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index', $websiteId)->with('success', 'Teacher deleted successfully');
    }

    /**
     * Get teachers for a specific website (AJAX endpoint)
     */
    public function getTeachers(Request $request)
    {
        $url = $request->input('url', url()->current());
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            return response()->json(['teachers' => []]);
        }

        $teachers = Teacher::where('website_id', $website->id)
            ->where('is_active', true)
            ->select('id', 'name', 'photo', 'description')
            ->get();

        return response()->json(['teachers' => $teachers]);
    }
}
