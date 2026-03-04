<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\Website;

class TicketCategoryController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::with('website')->ordered()->get();
        return view('admin.ticket-category.index', compact('categories'));
    }

    public function create()
    {
        $websites = Website::all();
        return view('admin.ticket-category.create', compact('websites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'website_id' => 'required|exists:websites,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        TicketCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'website_id' => $request->website_id,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.ticket-category.index')
            ->with('success', 'Ticket category created successfully.');
    }

    public function edit($id)
    {
        $category = TicketCategory::findOrFail($id);
        $websites = Website::all();
        return view('admin.ticket-category.edit', compact('category', 'websites'));
    }

    public function update(Request $request, $id)
    {
        $category = TicketCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'website_id' => 'required|exists:websites,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'website_id' => $request->website_id,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.ticket-category.index')
            ->with('success', 'Ticket category updated successfully.');
    }

    public function destroy($id)
    {
        $category = TicketCategory::findOrFail($id);
        
        // Check if category has tickets
        if ($category->tickets()->count() > 0) {
            return redirect()->route('admin.ticket-category.index')
                ->with('error', 'Cannot delete category that has tickets assigned to it.');
        }

        $category->delete();
        
        return redirect()->route('admin.ticket-category.index')
            ->with('success', 'Ticket category deleted successfully.');
    }
}