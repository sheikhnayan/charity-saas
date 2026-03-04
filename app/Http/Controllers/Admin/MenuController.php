<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Website;

class MenuController extends Controller
{
    public function index()
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->firstOrFail();
        
        $menus = Menu::where('website_id', $website->id)->with('allItems')->get();
        
        return view('admin.menus.index', compact('menus', 'website'));
    }

    public function create()
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->firstOrFail();
        
        return view('admin.menus.create', compact('website'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->firstOrFail();

        $validated['website_id'] = $website->id;
        $validated['status'] = $request->has('status') ? 1 : 0;

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully!');
    }

    public function edit($id)
    {
        $menu = Menu::with(['allItems.page'])->findOrFail($id);
        
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->firstOrFail();
        
        $pages = Page::where('website_id', $website->id)
                     ->where('status', 1)
                     ->orderBy('name')
                     ->get();
        
        return view('admin.menus.edit', compact('menu', 'website', 'pages'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully!');
    }

    public function updateMenuItems(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        
        // Delete all existing items
        $menu->allItems()->delete();
        
        // Recreate items from request
        $items = $request->input('items', []);
        
        foreach ($items as $index => $item) {
            $this->createMenuItem($menu->id, $item, null, $index);
        }
        
        return response()->json(['success' => true, 'message' => 'Menu items updated successfully!']);
    }

    private function createMenuItem($menuId, $itemData, $parentId = null, $order = 0)
    {
        $menuItem = MenuItem::create([
            'menu_id' => $menuId,
            'parent_id' => $parentId,
            'title' => $itemData['title'] ?? '',
            'url' => $itemData['url'] ?? null,
            'page_id' => !empty($itemData['page_id']) ? $itemData['page_id'] : null,
            'target' => $itemData['target'] ?? '_self',
            'css_classes' => $itemData['css_classes'] ?? null,
            'order' => $order,
            'status' => isset($itemData['status']) ? $itemData['status'] : 1,
        ]);

        // Process children if they exist
        if (isset($itemData['children']) && is_array($itemData['children'])) {
            foreach ($itemData['children'] as $childIndex => $child) {
                $this->createMenuItem($menuId, $child, $menuItem->id, $childIndex);
            }
        }
    }

    public function addItem(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'required|in:_self,_blank',
            'css_classes' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $menu = Menu::findOrFail($id);
        
        // Get the highest order value for this menu
        $maxOrder = MenuItem::where('menu_id', $menu->id)
                            ->where('parent_id', $validated['parent_id'] ?? null)
                            ->max('order') ?? -1;

        $validated['menu_id'] = $menu->id;
        $validated['order'] = $maxOrder + 1;
        $validated['status'] = $request->has('status') ? 1 : 0;

        MenuItem::create($validated);

        return redirect()->route('admin.menus.edit', $id)->with('success', 'Menu item added successfully!');
    }

    public function removeItem($id, $itemId)
    {
        $menuItem = MenuItem::where('menu_id', $id)->where('id', $itemId)->firstOrFail();
        $menuItem->delete();

        return redirect()->route('admin.menus.edit', $id)->with('success', 'Menu item removed successfully!');
    }
}
