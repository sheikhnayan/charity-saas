<div class="component-properties">
    <div class="component-settings">
        <h6 class="mb-3">Product Listing Grid Settings</h6>
        
        <!-- Layout Settings -->
        <div class="mb-3">
            <label class="form-label">Grid Columns</label>
            <select class="form-control component-property" data-property="columns">
                <option value="2">2 Columns</option>
                <option value="3" selected>3 Columns</option>
                <option value="4">4 Columns</option>
            </select>
            <small class="text-muted">Number of product cards per row</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Products Per Page</label>
            <input type="number" class="form-control component-property" data-property="perPage" value="12" min="3" max="50">
            <small class="text-muted">Number of products to display</small>
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
            <label class="form-label">Show Price</label>
            <select class="form-control component-property" data-property="showPrice">
                <option value="true" selected>Yes</option>
                <option value="false">No</option>
            </select>
            <small class="text-muted">Display product price</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Show Stock Status</label>
            <select class="form-control component-property" data-property="showStock">
                <option value="true" selected>Yes</option>
                <option value="false">No</option>
            </select>
            <small class="text-muted">Display "In Stock" or "Out of Stock"</small>
        </div>

        <!-- Sorting Options -->
        <div class="mb-3">
            <label class="form-label">Default Sort</label>
            <select class="form-control component-property" data-property="sort">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="name_az">Name: A-Z</option>
                <option value="name_za">Name: Z-A</option>
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
            <input type="color" class="form-control component-property" data-property="primaryColor" value="#3b82f6">
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
            <input type="text" class="form-control component-property" data-property="title" placeholder="e.g., Shop Our Products">
        </div>

        <div class="mb-3">
            <label class="form-label">Section Subtitle (Optional)</label>
            <textarea class="form-control component-property" data-property="subtitle" rows="2" placeholder="Browse our collection"></textarea>
        </div>
    </div>
</div>
