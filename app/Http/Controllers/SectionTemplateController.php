<?php

namespace App\Http\Controllers;

use App\Models\SectionTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SectionTemplateController extends Controller
{
    /**
     * Save a section as a template
     */
    public function save(Request $request)
    {
        try {
            $request->validate([
                'template_name' => 'required|string|max:255',
                'template_description' => 'nullable|string',
                'template_category' => 'required|string|max:100',
                'template_data' => 'required|string',
                'is_public' => 'nullable|boolean',
            ]);

            $template = new SectionTemplate();
            $template->name = $request->template_name;
            $template->description = $request->template_description;
            $template->category = $request->template_category;
            $template->template_data = $request->template_data;
            $template->is_public = $request->has('is_public') ? true : false;
            $template->user_id = Auth::id();
            
            $template->save();

            return response()->json([
                'success' => true,
                'message' => 'Section template saved successfully!',
                'template_id' => $template->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving section template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save section template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all section templates
     */
    public function list(Request $request)
    {
        try {
            $query = SectionTemplate::where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('is_public', true);
            });

            // Filter by category if provided
            if ($request->has('category') && $request->category !== '') {
                $query->where('category', $request->category);
            }

            $templates = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'templates' => $templates
            ]);

        } catch (\Exception $e) {
            Log::error('Error listing section templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load section templates'
            ], 500);
        }
    }

    /**
     * Get a specific section template
     */
    public function get($id)
    {
        try {
            $template = SectionTemplate::where(function($q) use ($id) {
                $q->where('id', $id)
                  ->where(function($q2) {
                      $q2->where('user_id', Auth::id())
                         ->orWhere('is_public', true);
                  });
            })->firstOrFail();

            return response()->json([
                'success' => true,
                'template_data' => $template->template_data,
                'template_name' => $template->name
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting section template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Section template not found'
            ], 404);
        }
    }

    /**
     * Delete a section template
     */
    public function delete($id)
    {
        try {
            $template = SectionTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Section template deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting section template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete section template'
            ], 500);
        }
    }
}
