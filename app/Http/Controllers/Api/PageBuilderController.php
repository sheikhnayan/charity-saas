<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CapturePageScreenshot;

class PageBuilderController extends Controller
{
    // Save builder state
    public function save(Request $request, $id)
    {
        $userId = Auth::id();
        $pageId = $id;

        $page = Page::find($pageId);
        $state = $request->input('state');
        
        // Get show_in_menu value from request (sent as metadata)
        $showInMenu = $request->input('show_in_menu', $page->show_in_menu);
        
        // Get enable_confetti value from request
        $enableConfetti = $request->input('enable_confetti', $page->enable_confetti ?? 0);
        
        // Get background_color value from request
        $backgroundColor = $request->input('background_color', $page->background_color ?? '#ffffff');

        
        if ($page->is_main_site) {
            // Main site page - update directly
            $page->update([
                'state' => $state,
                'show_in_menu' => $showInMenu,
                'enable_confetti' => $enableConfetti,
                'background_color' => $backgroundColor
            ]);
            // dd($page);
        } else {
            // Regular website page (existing logic)
            $websiteId = $page->website_id;
            
            $builderState = Page::updateOrCreate(
                [
                    'website_id' => $websiteId,
                    'id' => $pageId,
                ],
                [
                    'state' => $state,
                    'show_in_menu' => $showInMenu,
                    'enable_confetti' => $enableConfetti,
                    'background_color' => $backgroundColor
                ]
            );
        }
        
        // Dispatch screenshot capture job (async) - captures full page with header/footer
        CapturePageScreenshot::dispatch($pageId)->delay(now()->addSeconds(5));
        
        return response()->json(['success' => true]);
    }

    // Load builder state
    public function load(Request $request, $id)
    {
        $userId = Auth::id();
        $pageId = $id;

        $page = Page::find($pageId);

        if ($page->is_main_site) {
            // Main site page
            $builderState = $page;
        } else {
            // Regular website page (existing logic)
            $websiteId = $page->website_id;
            $builderState = Page::where('website_id', $websiteId)
                ->where('id', $pageId)
                ->first();
        }
        
        if ($builderState) {
            // Parse the state JSON if it's a string
            $state = is_string($builderState->state) ? json_decode($builderState->state, true) : $builderState->state;
            
            // Ensure state is an array with components and pageSettings
            if (!is_array($state)) {
                $state = [];
            }
            
            // Ensure pageSettings exists and include background_color
            if (!isset($state['pageSettings'])) {
                $state['pageSettings'] = [];
            }
            
            $state['pageSettings']['backgroundColor'] = $builderState->background_color ?? '#ffffff';
            
            return response()->json(['state' => $state]);
        } else {
            return response()->json(['state' => null]);
        }
    }

    public function websites()
    {
        $data = Website::all();
        return view('admin.page.websites', compact('data'));
    }
    
    public function mainSitePages()
    {
        $data = Page::mainSite()->orderBy('position')->get();
        $website = null; // No website for main site pages
        $isMainSite = true;
        return view('admin.page.index', compact('data', 'website', 'isMainSite'));
    }

    public function index($websiteId = null)
    {
        if ($websiteId) {
            $website = Website::findOrFail($websiteId);
            $data = Page::with(['website'])
                ->where('website_id', $websiteId)
                ->orderBy('position')
                ->get();
            $isMainSite = false;
            return view('admin.page.index', compact('data', 'website', 'isMainSite'));
        }
        
        // Fallback to all pages if no website specified
        $data = Page::with(['website'])->orderBy('position')->get();
        $website = null;
        $isMainSite = false;
        return view('admin.page.index', compact('data', 'website', 'isMainSite'));
    }

    public function create()
    {
        $data = Website::get();

        return view('admin.page.create',compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        
         // Validate input
        if ($request->has('is_main_site') && $request->is_main_site) {
            // Creating a main site page
            $nextPosition = Page::mainSite()->max('position') + 1;
            
            $add = new Page;
            $add->user_id = null; // Main site pages don't belong to specific users
            $add->website_id = null; // Main site pages don't belong to specific websites
            $add->is_main_site = true;
            $add->name = $request->name;
            $add->meta_title = $request->meta_title;
            $add->meta_description = $request->meta_description;
            
            // Handle meta image upload
            if ($request->hasFile('meta_image')) {
                $file = $request->file('meta_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('meta_images', $filename, 'public');
                $add->meta_image = 'storage/' . $path;
            }
            
            $add->background_color = $request->background_color;
            $add->default = $request->default;
            $add->is_homepage = $request->default == 1; // Set homepage status
            $add->show_in_menu = $request->has('show_in_menu') ? 1 : 0;
            $add->position = $nextPosition;
            $add->status = 1;
            $add->save();
            
            // If this page is set as homepage, remove homepage status from other main site pages
            if ($add->is_homepage) {
                $add->setAsHomepage();
            }
        } else {
            // Creating a regular website page (existing logic)
            $website = Website::find($request->website_id);
            
            // Get the next position for this website
            $nextPosition = Page::where('website_id', $request->website_id)->max('position') + 1;
            
            $add = new Page;
            $add->user_id = $website->user_id;
            $add->website_id = $request->website_id;
            $add->is_main_site = false;
            $add->name = $request->name;
            $add->meta_title = $request->meta_title;
            $add->meta_description = $request->meta_description;
            
            // Handle meta image upload
            if ($request->hasFile('meta_image')) {
                $file = $request->file('meta_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('meta_images', $filename, 'public');
                $add->meta_image = 'storage/' . $path;
            }
            
            $add->background_color = $request->background_color;
            $add->default = $request->default;
            $add->is_homepage = $request->default == 1; // Set homepage status
            $add->show_in_menu = $request->has('show_in_menu') ? 1 : 0;
            $add->position = $nextPosition;
            $add->status = 1;
            $add->save();
            
            // If this page is set as homepage, remove homepage status from other pages of this website
            if ($add->is_homepage) {
                $add->setAsHomepage();
            }
        }

        return redirect()->route('admin.page.index',[$add->website_id])->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        $data = Page::find($id);
        $website = Website::where('user_id', Auth::user()->id)->get();
        return view('admin.page.edit', compact('data','website'));
    }

    public function update(Request $request, $id)
    {
        $update = Page::find($id);
        $update->name = $request->name;
        $update->meta_title = $request->meta_title;
        $update->meta_description = $request->meta_description;
        
        // Handle meta image upload
        if ($request->hasFile('meta_image')) {
            // Delete old image if exists
            if ($update->meta_image && file_exists(public_path($update->meta_image))) {
                unlink(public_path($update->meta_image));
            }
            
            $file = $request->file('meta_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('meta_images', $filename, 'public');
            $update->meta_image = 'storage/' . $path;
        }
        
        $update->background_color = $request->background_color;
        $update->default = $request->default;
        $update->status = $request->status;
        $update->show_in_menu = $request->has('show_in_menu') ? 1 : 0;
        
        // Handle homepage status
        $newHomepageStatus = $request->default == 1;
        $oldHomepageStatus = $update->is_homepage;
        
        // Handle main site page updates
        if ($request->has('is_main_site') && $request->is_main_site) {
            $update->is_main_site = true;
            $update->user_id = null;
            $update->website_id = null;
        } else {
            $update->is_main_site = false;
            // For regular pages, keep existing website relationship
        }
        
        $update->is_homepage = $newHomepageStatus;
        $update->update();
        
        // If homepage status changed to true, remove from other pages
        if ($newHomepageStatus && !$oldHomepageStatus) {
            $update->setAsHomepage();
        }
        // If homepage status changed to false, just update
        elseif (!$newHomepageStatus && $oldHomepageStatus) {
            $update->removeHomepageStatus();
        }

        return redirect()->route('admin.page.index',[$update->website_id])->with('success', 'Page updated successfully.');
    }

    public function delete($id)
    {
        $delete = Page::find($id);
        $delete->delete();

        return redirect()->route('admin.page.index',[$delete->website_id])->with('success', 'Page deleted successfully.');
    }

    public function show($id)
    {
        $data = Page::with(['website', 'website.header'])->find($id);
        
        // Get active custom fonts for the editor
        $customFonts = \App\Models\CustomFont::active()->get();
        
        return view('admin.page.page-builder', compact('data', 'customFonts'));
    }

    public function componentProperties(Request $request, $component)
    {
        // Map component names to their Blade template paths
        $componentTemplates = [
            'ticket-carousel' => 'admin.page.page-components.ticket-carousel',
            'ticket-category-carousel' => 'admin.page.page-components.ticket-category-carousel',
            'property-category-carousel' => 'admin.page.page-components.property-category-carousel',
            'property-listing-grid' => 'admin.page.page-components.property-listing-grid',
            'product-listing-grid' => 'admin.page.page-components.product-listing-grid',
        ];

        // Check if the component template exists
        if (!isset($componentTemplates[$component])) {
            return response()->json(['error' => 'Component not found'], 404);
        }

        $templatePath = $componentTemplates[$component];

        // Check if the view exists
        if (!view()->exists($templatePath)) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        // Get website context - try request parameter first, then session
        $websiteId = $request->get('website_id') ?? session('current_website_id', 1);
        
        // Get existing component properties if provided
        $componentProperties = [];
        if ($request->has('properties')) {
            $componentProperties = json_decode($request->get('properties'), true) ?: [];
        }
        
        // Render the component template and return it
        try {
            $html = view($templatePath, [
                'currentWebsiteId' => $websiteId,
                'component' => ['properties' => $componentProperties]
            ])->render();
            return response($html, 200, ['Content-Type' => 'text/html']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to render template: ' . $e->getMessage()], 500);
        }
    }
}
