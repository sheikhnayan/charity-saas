<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageTemplateController extends Controller
{
    /**
     * Display list of templates
     */
    public function index(Request $request)
    {
        $category = $request->get('category');
        $templates = PageTemplate::getByCategory($category);
        
        $categories = PageTemplate::distinct()->pluck('category')->filter()->sort();
        
        return view('admin.templates.index', compact('templates', 'categories', 'category'));
    }

    /**
     * Show template creation form
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store new template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'state' => 'required|json',
            'category' => 'nullable|string|max:100',
            'is_public' => 'boolean',
        ]);

        $template = PageTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'state' => json_decode($request->state, true),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'background_color' => $request->background_color,
            'category' => $request->category ?? 'general',
            'is_public' => $request->has('is_public'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template created successfully!');
    }

    /**
     * Save existing page as template
     */
    public function saveFromPage(Request $request, Page $page)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'template_description' => 'nullable|string',
            'template_category' => 'nullable|string|max:100',
            'is_public' => 'boolean',
        ]);

        $template = $page->saveAsTemplate([
            'name' => $request->template_name,
            'description' => $request->template_description,
            'category' => $request->template_category ?? 'general',
            'is_public' => $request->has('is_public'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page saved as template successfully!',
            'template_id' => $template->id,
        ]);
    }

    /**
     * Apply template to page
     */
    public function applyToPage(Request $request, PageTemplate $template, Page $page)
    {
        $page->applyTemplate($template);

        return response()->json([
            'success' => true,
            'message' => 'Template applied successfully!',
            'redirect' => route('admin.page.show', $page->id),
        ]);
    }

    /**
     * Show template details
     */
    public function show(PageTemplate $template)
    {
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show template edit form
     */
    public function edit(PageTemplate $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update template
     */
    public function update(Request $request, PageTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_public' => 'boolean',
        ]);

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category ?? 'general',
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template updated successfully!');
    }

    /**
     * Delete template
     */
    public function destroy(PageTemplate $template)
    {
        // Update pages that use this template
        Page::where('template_id', $template->id)->update(['template_id' => null]);
        
        $template->delete();

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template deleted successfully!');
    }

    /**
     * Get templates for AJAX requests
     */
    public function getTemplates(Request $request)
    {
        $category = $request->get('category');
        $templates = PageTemplate::getByCategory($category);
        
        return response()->json($templates);
    }

    /**
     * Preview template
     */
    public function preview(PageTemplate $template)
    {
        return view('admin.templates.preview', compact('template'));
    }
}
