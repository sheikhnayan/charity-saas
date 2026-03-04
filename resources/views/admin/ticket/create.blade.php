{{-- filepath: resources/views/admin/ticket/create.blade.php --}}
@extends('admin.main')

@section('content')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
    /* Custom Font Styles for Quill Editor */
    @php
        $customFonts = \App\Models\CustomFont::all();
    @endphp
    
    @foreach($customFonts as $font)
    /* Custom font: {{ $font->font_family }} */
    @font-face {
        font-family: '{{ $font->font_family }}';
        src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="{{ $font->font_family }}"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="{{ $font->font_family }}"]::before {
        content: '{{ $font->font_family }}';
        font-family: '{{ $font->font_family }}', sans-serif !important;
    }
    
    .ql-font-{{ $font->font_family }} {
        font-family: '{{ $font->font_family }}', sans-serif !important;
    }
    @endforeach
    
    /* System Fonts */
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Arial"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Arial"]::before {
        content: 'Arial';
        font-family: Arial, sans-serif !important;
    }
    .ql-font-Arial {
        font-family: Arial, sans-serif !important;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Helvetica"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Helvetica"]::before {
        content: 'Helvetica';
        font-family: Helvetica, sans-serif !important;
    }
    .ql-font-Helvetica {
        font-family: Helvetica, sans-serif !important;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Times New Roman"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Times New Roman"]::before {
        content: 'Times New Roman';
        font-family: 'Times New Roman', serif !important;
    }
    .ql-font-Times_New_Roman {
        font-family: 'Times New Roman', serif !important;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Georgia"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Georgia"]::before {
        content: 'Georgia';
        font-family: Georgia, serif !important;
    }
    .ql-font-Georgia {
        font-family: Georgia, serif !important;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Verdana"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Verdana"]::before {
        content: 'Verdana';
        font-family: Verdana, sans-serif !important;
    }
    .ql-font-Verdana {
        font-family: Verdana, sans-serif !important;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Courier New"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Courier New"]::before {
        content: 'Courier New';
        font-family: 'Courier New', monospace !important;
    }
    .ql-font-Courier_New {
        font-family: 'Courier New', monospace !important;
    }
    
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Outfit"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Outfit"]::before {
        content: 'Outfit';
        font-family: 'Outfit', sans-serif !important;
    }
    .ql-font-Outfit {
        font-family: 'Outfit', sans-serif !important;
    }
    
    /* Size picker styles */
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="10px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="10px"]::before {
        content: '10px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="12px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="12px"]::before {
        content: '12px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="14px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="14px"]::before {
        content: '14px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="16px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="16px"]::before {
        content: '16px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="18px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="18px"]::before {
        content: '18px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="20px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="20px"]::before {
        content: '20px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="24px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="24px"]::before {
        content: '24px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="28px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="28px"]::before {
        content: '28px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="32px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="32px"]::before {
        content: '32px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="36px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="36px"]::before {
        content: '36px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="48px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="48px"]::before {
        content: '48px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="64px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="64px"]::before {
        content: '64px';
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <h4>Add Ticket</h4>
                <form action="{{ route('admin.ticket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <select name="website_id" id="website" class="form-select" onchange="filterCategories()">
                            <option value="">Select Website</option>
                            @foreach ($data as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category_id" id="category" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" data-website="{{ $category->website_id }}">{{ $category->name }} ({{ $category->website->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="ticket">Ticket</option>
                            <option value="product">Product</option>
                            <option value="property">Property</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Ticket Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    
                    <!-- Regular Price (for ticket and product) -->
                    <div class="mb-3 regular-price-field">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" id="price">
                    </div>
                    
                    <!-- Property Share Fields (only for property type) -->
                    <div class="property-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="price_per_share" class="form-label">Price Per Share <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price_per_share" class="form-control" id="price_per_share" 
                                   placeholder="e.g., 100.00">
                        </div>
                        <div class="mb-3">
                            <label for="price_per_share_label" class="form-label">Custom Label (Price Per Share)</label>
                            <input type="text" name="price_per_share_label" class="form-control" id="price_per_share_label" placeholder="e.g., Share Price" />
                            <small class="text-muted">Overrides default 'Price Per Share' wording across property details page.</small>
                        </div>
                        <div class="mb-3">
                            <label for="total_shares" class="form-label">Total Shares Available <span class="text-danger">*</span></label>
                            <input type="number" name="total_shares" class="form-control" id="total_shares" 
                                   placeholder="e.g., 1000">
                            <small class="text-muted">Total number of shares for this property</small>
                        </div>
                    </div>
                    
                    <!-- Regular Quantity (for ticket and product) -->
                    <div class="mb-3 regular-quantity-field">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity">
                    </div>
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">hide Until</label>
                        <input type="date" name="hide_until" class="form-control" id="valid_from">
                    </div>
                    <div class="mb-3">
                        <label for="valid_to" class="form-label">Hide After</label>
                        <input type="date" name="hide_after" class="form-control" id="valid_to">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image[]" class="form-control" id="image" multiple>
                    </div>
                    
                    <!-- Property Documents (only for property type) -->
                    <div class="property-documents-field" style="display: none;">
                        <div class="mb-3">
                            <label for="documents" class="form-label">Property Documents</label>
                            <input type="file" name="documents[]" class="form-control" id="documents" multiple 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx">
                            <small class="text-muted">Upload legal documents, prospectus, financial reports, etc.</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <div id="description_editor" style="height: 200px;"></div>
                        <input type="hidden" name="description" id="description">
                        <small class="text-muted">Use rich text formatting with custom fonts</small>
                    </div>
                    
                    <!-- Market Field (only for property type) -->
                    <div class="property-market-field" style="display: none;">
                        <div class="mb-3">
                            <label for="market" class="form-label">Market Analysis</label>
                            <div id="market_editor" style="height: 200px;"></div>
                            <input type="hidden" name="market" id="market">
                            <small class="text-muted">Market overview, trends, and analysis for this property</small>
                        </div>
                    </div>
                    
                    {{-- Replace the is_active checkbox with this select in your create/edit forms --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="page_bg_color" class="form-label">Page Background Color</label>
                        <div class="input-group">
                            <input type="color" name="page_bg_color" id="page_bg_color" class="form-control form-control-color" value="#ffffff" style="max-width: 80px;">
                            <input type="text" class="form-control" id="page_bg_color_text" value="#ffffff" readonly>
                        </div>
                        <small class="text-muted">This color will be applied to the background of the item detail page. Defaults to white (#ffffff).</small>
                    </div>

                    <div class="product">
                        <div class="mb-3">
                            <label for="size" class="form-label">Size</label>
                            <input type="text" name="size" class="form-control" id="size">
                        </div>
                    </div>

                    <!-- Features section - available for both products and properties -->
                    <div class="features-section mt-4" style="display: none;">
                        <div class="mb-4">
                            <label for="features_heading" class="block mb-2 font-semibold">Features Section Heading</label>
                            <input type="text" id="features_heading" name="features_heading" value="Investment Features" placeholder="e.g., Investment Features, Property Features" class="border p-2 rounded w-full">
                        </div>

                        <h4 class="mb-2">Features</h4>

                        <div id="features-container">
                            <div class="feature-row flex items-center gap-2 mb-2">
                            <input type="text" name="features[0][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2">
                            <input type="text" name="features[0][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2">
                            </div>
                        </div>

                        <button type="button" id="add-feature-btn" class="add-feature-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            + Add Feature
                        </button>
                    </div>

                    <script>
                    let featureIndex = 1;

                    document.getElementById('add-feature-btn').addEventListener('click', function() {
                        const container = document.getElementById('features-container');

                        const newRow = document.createElement('div');
                        newRow.classList.add('feature-row', 'flex', 'items-center', 'gap-2', 'mb-2');
                        newRow.innerHTML = `
                        <input type="text" name="features[${featureIndex}][name]" placeholder="Feature Name" class="feature-name border p-2 rounded w-1/2">
                        <input type="text" name="features[${featureIndex}][value]" placeholder="Feature Value" class="feature-value border p-2 rounded w-1/2">
                        <button type="button" class="remove-feature text-red-500 hover:text-red-700">✕</button>
                        `;

                        container.appendChild(newRow);

                        // Remove feature row
                        newRow.querySelector('.remove-feature').addEventListener('click', () => newRow.remove());

                        featureIndex++;
                    });
                    </script>

                    <script>
                    function addExtraItem(containerId, fieldBase){
                        const container = document.getElementById(containerId);
                        if(!container) return;
                        const index = container.querySelectorAll('.extra-item').length;
                        const row = document.createElement('div');
                        row.className = 'row g-2 align-items-end mb-2 extra-item';
                        row.innerHTML = `
                            <div class="col-md-3"><input type="text" name="${fieldBase}[${index}][label]" class="form-control" placeholder="Label"></div>
                            <div class="col-md-2"><input type="text" name="${fieldBase}[${index}][value]" class="form-control" placeholder="Value (text)"></div>
                            <div class="col-md-5"><input type="text" name="${fieldBase}[${index}][tooltip]" class="form-control" placeholder="Tooltip (optional)"></div>
                            <div class="col-md-2"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.extra-item').remove()">Remove</button></div>
                        `;
                        container.appendChild(row);
                    }
                    </script>

                    <!-- Property Financials section - only for property type -->
                    <div class="financials-section mt-4" style="display: none;">
                        <h4 class="mb-3">Property Financials</h4>
                        
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Total Investment Value Section</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Additional Items (Label / Value / Tooltip)</label>
                                    <div id="totalInvestmentExtras"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addExtraItem('totalInvestmentExtras','financials[custom_total_investment_items]')">Add Item</button>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Field Label</label>
                                        <input type="text" name="financials[total_investment_label]" class="form-control" placeholder="Total Investment Value">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[total_investment_value]" class="form-control" placeholder="Enter value exactly as you want shown">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_total_investment]" value="1">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Underlying Asset Label</label>
                                        <input type="text" name="financials[underlying_asset_label]" class="form-control" placeholder="Underlying asset price">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[underlying_asset_price]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_underlying_asset]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_underlying_asset_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[underlying_asset_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Closing Costs Label</label>
                                        <input type="text" name="financials[closing_costs_label]" class="form-control" placeholder="Closing costs">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[closing_costs]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_closing_costs]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_closing_costs_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[closing_costs_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Upfront Fees Label</label>
                                        <input type="text" name="financials[upfront_fees_label]" class="form-control" placeholder="Upfront DAO LLC fees">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[upfront_fees]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_upfront_fees]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_upfront_fees_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[upfront_fees_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Operating Reserve Label</label>
                                        <input type="text" name="financials[operating_reserve_label]" class="form-control" placeholder="Operating reserve">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[operating_reserve_value]" class="form-control" placeholder="$343.44 / $0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_operating_reserve]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_operating_reserve_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[operating_reserve_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Projected Annual Return Section</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Additional Items (Label / Value / Tooltip)</label>
                                    <div id="projectedAnnualReturnExtras"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addExtraItem('projectedAnnualReturnExtras','financials[custom_projected_annual_return_items]')">Add Item</button>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Section Title</label>
                                        <input type="text" name="financials[projected_annual_return_label]" class="form-control" placeholder="Projected Annual Return">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[projected_annual_return]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_annual_return]" value="1">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Projected Rental Yield Label</label>
                                        <input type="text" name="financials[projected_rental_yield_label]" class="form-control" placeholder="Projected Rental Yield">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[projected_rental_yield]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_rental_yield]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_rental_yield_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[projected_rental_yield_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Projected Appreciation Label</label>
                                        <input type="text" name="financials[projected_appreciation_label]" class="form-control" placeholder="Projected Appreciation">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[projected_appreciation]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_appreciation]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_appreciation_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[projected_appreciation_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Rental Yield Label</label>
                                        <input type="text" name="financials[rental_yield_label]" class="form-control" placeholder="Rental Yield">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[rental_yield]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_rental_yield]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_rental_yield_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[rental_yield_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Annual Details Section</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Additional Items (Label / Value / Tooltip)</label>
                                    <div id="annualGrossRentsExtras"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addExtraItem('annualGrossRentsExtras','financials[custom_annual_gross_rents_items]')">Add Item</button>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Annual Gross Rents Label</label>
                                        <input type="text" name="financials[annual_gross_rents_label]" class="form-control" placeholder="Annual gross rents">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[annual_gross_rents]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_annual_gross_rents]" value="1">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Property Taxes Label</label>
                                        <input type="text" name="financials[property_taxes_label]" class="form-control" placeholder="Property taxes">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[property_taxes]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_property_taxes]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_property_taxes_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[property_taxes_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Homeowners Insurance Label</label>
                                        <input type="text" name="financials[homeowners_insurance_label]" class="form-control" placeholder="Homeowners insurance">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[homeowners_insurance]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_homeowners_insurance]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_homeowners_insurance_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[homeowners_insurance_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Property Management Label</label>
                                        <input type="text" name="financials[property_management_label]" class="form-control" placeholder="Property management">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[property_management]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_property_management]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_property_management_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[property_management_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Annual LLC Fees Label</label>
                                        <input type="text" name="financials[annual_llc_fees_label]" class="form-control" placeholder="Annual DAO LLC fees">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[annual_llc_fees]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_annual_llc_fees]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_annual_llc_fees_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[annual_llc_fees_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Annual Cash Flow Label</label>
                                        <input type="text" name="financials[annual_cash_flow_label]" class="form-control" placeholder="Annual cash flow">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[annual_cash_flow]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_annual_cash_flow]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_annual_cash_flow_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[annual_cash_flow_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Cap Rate Label</label>
                                        <input type="text" name="financials[cap_rate_label]" class="form-control" placeholder="Cap rate">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[cap_rate]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_cap_rate]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_cap_rate_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[cap_rate_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Monthly Cash Flow Label</label>
                                        <input type="text" name="financials[monthly_cash_flow_label]" class="form-control" placeholder="Monthly cash flow">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[monthly_cash_flow]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_monthly_cash_flow]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_monthly_cash_flow_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[monthly_cash_flow_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Projected Annual Cash Flow Label</label>
                                        <input type="text" name="financials[projected_annual_cash_flow_label]" class="form-control" placeholder="Projected Annual Cash Flow">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[projected_annual_cash_flow]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_annual_cash_flow]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_projected_annual_cash_flow_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[projected_annual_cash_flow_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Current Loan Label</label>
                                        <input type="text" name="financials[current_loan_label]" class="form-control" placeholder="Current loan">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Value (text)</label>
                                        <input type="text" name="financials[current_loan]" class="form-control" placeholder="Enter value">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Show Field</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_current_loan]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Show Tooltip</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="financials[show_current_loan_tooltip]" value="1">
                                        </div>
                                        <textarea name="financials[current_loan_tooltip]" class="form-control mt-2" rows="2" placeholder="Tooltip text"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.ticket.websites') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
    $('#type').on('change', function() {
        var selectedType = $(this).val();
        var productDiv = $('.product');
        var propertyFields = $('.property-fields');
        var propertyDocuments = $('.property-documents-field');
        var propertyMarketField = $('.property-market-field');
        var featuresSection = $('.features-section');
        var financialsSection = $('.financials-section');
        var regularPriceField = $('.regular-price-field');
        var regularQuantityField = $('.regular-quantity-field');
        var categoryField = $('#category').closest('.mb-3');

        // Hide all conditional sections first
        productDiv.hide();
        propertyFields.hide();
        propertyDocuments.hide();
        propertyMarketField.hide();
        featuresSection.hide();
        financialsSection.hide();
        regularPriceField.show();
        regularQuantityField.show();

        if (selectedType === 'product') {
            productDiv.show();
            featuresSection.show();
        } else if (selectedType === 'property') {
            featuresSection.show();
            financialsSection.show();
            propertyFields.show();
            propertyDocuments.show();
            propertyMarketField.show();
            regularPriceField.hide();
            regularQuantityField.hide();
            categoryField.show(); // Show category for properties
            // Make property fields required
            $('#category').prop('required', true);
            $('#price_per_share').prop('required', true);
            $('#total_shares').prop('required', true);
        } else {
            // Remove required from property fields for non-property types
            $('#price_per_share').prop('required', false);
            $('#total_shares').prop('required', false);
        }
    });

    // Filter categories by selected website
    function filterCategories() {
        const websiteId = document.getElementById('website').value;
        const categorySelect = document.getElementById('category');
        const categoryOptions = categorySelect.querySelectorAll('option');
        
        // Reset category selection
        categorySelect.value = '';
        
        categoryOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Show "Select Category" option
            } else {
                const optionWebsiteId = option.getAttribute('data-website');
                if (websiteId === '' || optionWebsiteId === websiteId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
        });
    }
    
    // Initialize Quill editors with custom fonts
    document.addEventListener('DOMContentLoaded', function() {
        // Get custom fonts from the database
        @php
            $customFonts = \App\Models\CustomFont::all();
            $fontNames = $customFonts->pluck('font_family')->toArray();
            $systemFonts = ['Arial', 'Helvetica', 'Times New Roman', 'Georgia', 'Verdana', 'Courier New', 'Outfit'];
            $allFonts = array_merge($systemFonts, $fontNames);
        @endphp
        
        // Register custom fonts with Quill
        var Font = Quill.import('formats/font');
        Font.whitelist = @json($allFonts);
        Quill.register(Font, true);
        
        // Register custom size options
        var Size = Quill.import('formats/size');
        Size.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px', '64px'];
        Quill.register(Size, true);
        
        // Initialize Description Editor
        var descriptionQuill = new Quill('#description_editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': Font.whitelist }],
                    [{ 'size': Size.whitelist }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        
        // Initialize Market Editor
        var marketQuill = new Quill('#market_editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': Font.whitelist }],
                    [{ 'size': Size.whitelist }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        
        // Update hidden inputs when content changes
        descriptionQuill.on('text-change', function() {
            document.getElementById('description').value = descriptionQuill.root.innerHTML;
        });
        
        marketQuill.on('text-change', function() {
            document.getElementById('market').value = marketQuill.root.innerHTML;
        });
        
        // Ensure content is saved before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            // Save description content
            const descField = document.getElementById('description');
            if (descField && descriptionQuill) {
                descField.value = descriptionQuill.root.innerHTML;
            }
            
            // Save market content (only if it exists)
            const marketField = document.getElementById('market');
            if (marketField && marketQuill) {
                marketField.value = marketQuill.root.innerHTML;
            }
        });

        // Sync color picker with text display
        const colorInput = document.getElementById('page_bg_color');
        const colorText = document.getElementById('page_bg_color_text');
        if (colorInput && colorText) {
            colorInput.addEventListener('change', function() {
                colorText.value = this.value;
            });
        }
    });
</script>
@endsection
