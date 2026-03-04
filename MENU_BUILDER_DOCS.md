# Menu Builder System - Documentation

## Overview
The Menu Builder system allows you to create advanced navigation menus for fundraiser-type websites with support for:
- Multi-level dropdown menus (up to 3 levels deep)
- Drag-and-drop interface for easy menu organization
- Custom links and page links
- Dynamic styling (colors, fonts from header settings)
- Link target options (same window/_blank)
- Custom CSS classes for styling

## Features Implemented

### Database Structure
- **menus table**: Stores menu configurations
  - `website_id`: Links to specific website
  - `name`: Menu name (for admin reference)
  - `location`: Menu placement (primary, footer, mobile)
  - `status`: Active/inactive toggle

- **menu_items table**: Stores individual menu items
  - `menu_id`: Parent menu
  - `parent_id`: For nested/dropdown items
  - `title`: Display text
  - `url`: Custom URL
  - `page_id`: Link to page (auto-generates URL)
  - `target`: _self or _blank
  - `css_classes`: Optional styling classes
  - `order`: Display order
  - `status`: Active/inactive toggle

### Admin Interface

#### Location
Navigate to: **Admin Panel → Website → Menu Builder**

#### Creating a Menu
1. Click "Create New Menu"
2. Enter menu name (e.g., "Main Menu")
3. Select location: Primary Navigation, Footer Menu, or Mobile Menu
4. Set status to Active
5. Click "Create Menu"

#### Building Menu Structure
1. Edit a menu to access the builder
2. Add items using the left panel:
   - **Select from Pages**: Choose existing pages
   - **Custom Links**: Add external URLs or special links
3. Drag items to reorder
4. Drag items to the right to create dropdowns (parent-child relationships)
5. Configure each item:
   - Title
   - URL or Page selection
   - Open in new window option
   - CSS classes for custom styling
6. Click "Save Menu Structure" when done

### Frontend Display

#### For Fundraiser Websites
The system automatically:
1. Checks for an active "Primary Navigation" menu
2. If found, renders the menu with dropdowns
3. If not found, falls back to displaying all pages (legacy behavior)

#### Dropdown Styling
- Inherits colors from Header settings (`background`, `color`)
- Inherits font family from Header's `menu_font_family`
- Responsive design: Desktop dropdowns, mobile stacked
- Smooth hover transitions
- Bootstrap 5 dropdown components

### Dynamic Features

#### Colors
- Menu links use `$header->color`
- Dropdown background uses `$header->background`
- Hover states with opacity adjustments

#### Fonts
- All menu items inherit `$header->menu_font_family` if set
- Applies to both top-level and dropdown items

#### Responsive Design
- Desktop: Horizontal menu with dropdowns
- Mobile: Collapsible menu with nested items indented

## Technical Details

### Routes
```php
Route::prefix('admins/menus')->name('admin.menus.')->group(function () {
    Route::get('/', 'index');                           // List all menus
    Route::get('/create', 'create');                    // Create form
    Route::post('/store', 'store');                     // Save new menu
    Route::get('/{id}/edit', 'edit');                   // Edit/Builder
    Route::put('/{id}', 'update');                      // Update menu settings
    Route::delete('/{id}', 'destroy');                  // Delete menu
    Route::post('/{id}/update-items', 'updateMenuItems'); // Save structure
});
```

### Models

#### Menu Model
```php
- belongsTo(Website::class)
- hasMany(MenuItem::class) // Only top-level items
- hasMany(MenuItem::class) as allItems // All items
```

#### MenuItem Model
```php
- belongsTo(Menu::class)
- belongsTo(MenuItem::class) as parent
- hasMany(MenuItem::class) as children
- belongsTo(Page::class)
- Accessor: getUrlAttribute() // Auto-generates page URLs
```

### Frontend Rendering
File: `resources/views/layouts/nav.blade.php`

The menu system checks:
1. Is website type = 'fundraiser'?
2. Does an active primary menu exist?
3. Render menu or fallback to pages

Dropdown detection:
```blade
@if($menuItem->children->count() > 0)
    {{-- Render dropdown --}}
@else
    {{-- Render regular link --}}
@endif
```

## Usage Examples

### Example 1: Simple Navigation
```
Home
About Us
Services
Contact
```

### Example 2: Dropdown Menu
```
Home
Services
  ├─ Web Design
  ├─ SEO
  └─ Marketing
About
  ├─ Our Team
  └─ Our History
Contact
```

### Example 3: Multi-level Dropdown
```
Products
  ├─ Software
  │   ├─ Web Apps
  │   └─ Mobile Apps
  ├─ Hardware
  └─ Services
```

## Styling Examples

### Custom CSS Classes
Add classes like:
- `btn btn-primary` for button-style links
- `highlight` for highlighted items
- `badge` for badge-style items

### Header Settings Impact
The menu automatically inherits from Header settings:
- Background color → Dropdown background
- Text color → All link colors
- Font family → All menu text
- Font size (if implemented in future)

## Backward Compatibility

The system maintains full backward compatibility:
- Old websites without menus continue to work
- Automatic fallback to page-based navigation
- No changes required for existing implementations
- Menu builder is opt-in

## Migration Path

For existing websites:
1. Go to Menu Builder
2. Create a new "Primary Navigation" menu
3. Add all pages you want in the menu
4. Organize with drag-and-drop
5. Activate the menu
6. Frontend automatically switches to menu system

## SEO Considerations

- All links use proper `<a>` tags
- `target` attributes for external links
- Proper HTML5 semantic structure
- Accessible navigation with ARIA labels
- Schema.org compatible structure

## Performance

- Efficient queries with eager loading
- Minimal database hits (single query for menu + items)
- Cached relationships
- Optimized for rendering

## Future Enhancements

Potential improvements:
- Menu icons/SVG support
- Mega menu layouts
- Conditional display rules
- Mobile-specific menus
- Menu item visibility by user role
- A/B testing integration

## Troubleshooting

### Menu not appearing
- Check menu status is Active
- Verify website type is 'fundraiser'
- Ensure menu location is 'primary'
- Check that menu has active items

### Dropdown not working
- Verify Bootstrap 5 is loaded
- Check JavaScript console for errors
- Ensure parent item has children with status=1

### Styling issues
- Check Header settings for colors/fonts
- Verify CSS is loading properly
- Check for theme conflicts
- Inspect browser console for CSS errors

## Support

For issues or questions, check:
1. This documentation
2. Code comments in files
3. Laravel logs (storage/logs)
4. Browser console for frontend errors
