<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomFont;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FontController extends Controller
{
    /**
     * Display font management page
     */
    public function index()
    {
        $fonts = CustomFont::orderBy('created_at', 'desc')->get();
        return view('admin.fonts.index', compact('fonts'));
    }

    /**
     * Upload a new font
     */
    public function store(Request $request)
    {
        try {
            // Validate font name
            $request->validate([
                'font_name' => 'required|string|max:255',
                'font_file' => 'required|file|max:10240', // Max 10MB
            ]);

            $file = $request->file('font_file');
            
            // Manual extension validation (more reliable than MIME type check)
            $allowedExtensions = ['ttf', 'otf', 'woff', 'woff2'];
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($extension, $allowedExtensions)) {
                return back()->with('error', 'Invalid font file format. Only TTF, OTF, WOFF, and WOFF2 files are allowed.');
            }
            
            if (!$file) {
                return back()->with('error', 'No file uploaded. Please select a font file.');
            }
            
            $extension = $file->getClientOriginalExtension();
            
            // Generate safe font family name
            $fontFamily = Str::slug($request->font_name, '-');
            
            // Check if font family already exists
            if (CustomFont::where('font_family', $fontFamily)->exists()) {
                return back()->with('error', 'A font with this name already exists. Please choose a different name.');
            }
            
            // Ensure directory exists
            $fontDir = storage_path('app/public/fonts');
            if (!file_exists($fontDir)) {
                mkdir($fontDir, 0755, true);
            }
            
            // Store font file
            $filename = $fontFamily . '-' . time() . '.' . $extension;
            $path = $file->storeAs('fonts', $filename, 'public');
            
            if (!$path) {
                return back()->with('error', 'Failed to save font file to storage.');
            }
            
            // Create font record
            $font = CustomFont::create([
                'font_name' => $request->font_name,
                'font_family' => $fontFamily,
                'file_path' => $path,
                'file_format' => $extension,
                'file_size' => $file->getSize(),
                'is_active' => true,
            ]);

            return back()->with('success', 'Font "' . $font->font_name . '" uploaded successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload font: ' . $e->getMessage());
        }
    }

    /**
     * Toggle font active status
     */
    public function toggle($id)
    {
        try {
            $font = CustomFont::findOrFail($id);
            $font->is_active = !$font->is_active;
            $font->save();

            return back()->with('success', 'Font status updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update font status.');
        }
    }

    /**
     * Delete a font
     */
    public function destroy($id)
    {
        try {
            $font = CustomFont::findOrFail($id);
            
            // Delete the font file
            if (Storage::disk('public')->exists($font->file_path)) {
                Storage::disk('public')->delete($font->file_path);
            }
            
            $font->delete();

            return back()->with('success', 'Font deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Font deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete font.');
        }
    }

    /**
     * Generate CSS for all active fonts
     */
    public function css()
    {
        $fonts = CustomFont::active()->get();
        
        $css = "/* Custom Fonts - Generated on " . now() . " */\n\n";
        
        foreach ($fonts as $font) {
            $fontUrl = asset('storage/' . $font->file_path);
            $format = $this->getFontFormat($font->file_format);
            
            $css .= "@font-face {\n";
            $css .= "    font-family: '{$font->font_family}';\n";
            $css .= "    src: url('{$fontUrl}') format('{$format}');\n";
            $css .= "    font-weight: normal;\n";
            $css .= "    font-style: normal;\n";
            $css .= "    font-display: swap;\n";
            $css .= "}\n\n";
        }
        
        return response($css)
            ->header('Content-Type', 'text/css')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Get CSS font format from file extension
     */
    private function getFontFormat($extension)
    {
        $formats = [
            'woff2' => 'woff2',
            'woff' => 'woff',
            'ttf' => 'truetype',
            'otf' => 'opentype',
        ];
        
        return $formats[$extension] ?? 'truetype';
    }
}
