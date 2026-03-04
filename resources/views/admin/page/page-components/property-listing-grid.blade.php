<div class="component-properties">
    <div class="component-settings">
        <h6 class="mb-3">Property Listing Grid Settings</h6>
        
        <!-- Layout Settings -->
        <div class="mb-3">
            <label class="form-label">Grid Columns</label>
            <select class="form-control component-property" data-property="columns">
                <option value="2">2 Columns</option>
                <option value="3" selected>3 Columns</option>
                <option value="4">4 Columns</option>
            </select>
            <small class="text-muted">Number of property cards per row</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Properties Per Page</label>
            <input type="number" class="form-control component-property" data-property="perPage" value="9" min="3" max="50">
            <small class="text-muted">Number of properties to display</small>
        </div>

        <!-- Filter Settings -->
        <div class="mb-3">
            <label class="form-label">Show Category Filter</label>
            <select class="form-control component-property" data-property="showFilter">
                <option value="true" selected>Yes</option>
                <option value="false">No</option>
            </select>
            <small class="text-muted">Display category tabs at the top</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Default Category</label>
            <select class="form-control component-property" data-property="defaultCategory">
                <option value="all" selected>All Categories</option>
            </select>
            <small class="text-muted">Category to show on page load</small>
        </div>

        <!-- Card Display Options -->
        <div class="mb-3">
            <label class="form-label">Show Image Carousel</label>
            <select class="form-control component-property" data-property="showCarousel">
                <option value="true" selected>Yes</option>
                <option value="false">No</option>
            </select>
            <small class="text-muted">Enable image navigation arrows on cards</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Description Length</label>
            <input type="number" class="form-control component-property" data-property="descriptionLength" value="100" min="50" max="500">
            <small class="text-muted">Maximum characters to show in description</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Show Share Info</label>
            <select class="form-control component-property" data-property="showShares">
                <option value="true" selected>Yes</option>
                <option value="false">No</option>
            </select>
            <small class="text-muted">Display price per share and available shares</small>
        </div>

        <!-- Sorting Options -->
        <div class="mb-3">
            <label class="form-label">Default Sort</label>
            <select class="form-control component-property" data-property="sort">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="shares_available">Most Shares Available</option>
            </select>
        </div>

        <!-- Styling Options -->
        <div class="mb-3">
            <label class="form-label">Card Style</label>
            <select class="form-control component-property" data-property="cardStyle">
                <option value="shadow">Shadow</option>
                <option value="border" selected>Border</option>
                <option value="minimal">Minimal</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Primary Color</label>
            <input type="color" class="form-control component-property" data-property="primaryColor" value="#667eea">
            <small class="text-muted">Color for buttons and highlights</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Section Background Color</label>
            <input type="color" class="form-control component-property" data-property="bgColor" value="#ffffff">
        </div>

        <!-- Section Spacing -->
        <div class="mb-3">
            <label class="form-label">Section Padding (px)</label>
            <input type="number" class="form-control component-property" data-property="padding" value="60" min="0" max="200">
        </div>

        <!-- Custom Title -->
        <div class="mb-3">
            <label class="form-label">Section Title (Optional)</label>
            <input type="text" class="form-control component-property" data-property="title" placeholder="e.g., Featured Properties">
        </div>

        <div class="mb-3">
            <label class="form-label">Section Subtitle (Optional)</label>
            <textarea class="form-control component-property" data-property="subtitle" rows="2" placeholder="Explore our investment opportunities"></textarea>
        </div>
    </div>
</div>
