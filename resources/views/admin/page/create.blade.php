@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

<style>
    .forms-wizard li.done em::before, .lnr-checkmark-circle::before {
  content: "\e87f";
}

.forms-wizard li.done em::before {
  display: block;
  font-size: 1.2rem;
  height: 42px;
  line-height: 40px;
  text-align: center;
  width: 42px;
}

.forms-wizard li.done em {
  font-family: Linearicons-Free;
}

label{
    color: #000 !important;
}
</style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="app-main__inner">
                        <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">

                                    <div class="page-title-icon">
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            Page
                                        </span>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb" style="float: left">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/admins">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Setting
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Page
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                    <form action="{{ route('admin.page.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="card-body">
                                            {{-- Main Site Page Toggle --}}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Page Type</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="page_type" id="website_page" value="website" {{ request('main_site') != '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="website_page">
                                                                <strong>Website Page</strong> - Belongs to a specific website
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="page_type" id="main_site_page" value="main_site" {{ request('main_site') == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="main_site_page">
                                                                <strong>Main Site Page</strong> - Accessible only on fundconnects.com
                                                            </label>
                                                        </div>
                                                        <input type="hidden" name="is_main_site" id="is_main_site" value="{{ request('main_site') == '1' ? '1' : '0' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row" id="website_selection">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Website</label>
                                                        <select name="website_id" class="form-control" id="website_select" {{ request('main_site') == '1' ? '' : 'required' }}>
                                                            <option value="">Select Website</option>
                                                            @foreach ($data as $item)
                                                                <option value="{{ $item->id }}" data-type="{{ $item->type }}">{{ $item->name }} ({{ ucfirst($item->type) }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Website Type Information --}}
                                            <div id="website-type-info" class="alert alert-info" style="display: none;">
                                                <strong>Website Type:</strong> <span id="selected-website-type"></span>
                                                <br>
                                                <small id="website-type-description"></small>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Page Name</label>
                                                        <input type="text" name="name" class="form-control" id="name" placeholder="Page Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="default" class="form-label">
                                                            <i class="fas fa-home me-1"></i>Make Homepage
                                                        </label>
                                                        <select name="default" id="homepage_select" class="form-control" required>
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            If set to "Yes", this page will be accessible via the domain itself (e.g., domain.com) and displayed as "Home"
                                                        </small>
                                                        <div id="homepage_warning" class="alert alert-warning mt-2" style="display: none;">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            <strong>Note:</strong> Setting this as homepage will automatically remove homepage status from other pages of this website.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="show_in_menu" class="form-label">
                                                            <i class="fas fa-bars me-1"></i>Show in Menu
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" checked>
                                                            <label class="form-check-label" for="show_in_menu">
                                                                Display this page in the website navigation menu
                                                            </label>
                                                        </div>
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Toggle this to control whether the page appears in the menu. Useful for hidden pages like thank you pages or private pages.
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Meta Title</label>
                                                        <input type="text" name="meta_title" class="form-control" id="meta_title" placeholder="Meta Title">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Meta Description</label>
                                                        <textarea name="meta_description" class="form-control" id="meta_description" placeholder="Meta Description"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="meta_image" class="form-label">Meta Image (Open Graph)</label>
                                                        <input type="file" name="meta_image" class="form-control" id="meta_image" accept="image/*">
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Upload an image for social media sharing (recommended: 1200x630px)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4" data-step="3" data-title="Header text color"
                                                data-intro="Choose a color for the header text." bis_skin_checked="1">
                                                <label for="text_color" class="form-label">
                                                    Page Background
                                                </label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="text_color_picker"
                                                        value="#000000" title="Choose your color"
                                                        style="max-width: 3rem;">
                                                    <input type="text" class="form-control" id="text_color" name="background_color"
                                                        value="#000000" placeholder="#000000 or color name">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const colorInput = document.getElementById('text_color_picker');
                                                        const textInput = document.getElementById('text_color');
                                                        // Sync color picker to text
                                                        colorInput.addEventListener('input', function() {
                                                            textInput.value = colorInput.value;
                                                        });
                                                        // Sync text to color picker if valid hex
                                                        textInput.addEventListener('input', function() {
                                                            const val = textInput.value.trim();
                                                            if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                                colorInput.value = val;
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.page.websites') }}" class="btn btn-danger">Cancel</a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- / Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const websiteSelect = document.getElementById('website_select');
            const websiteTypeInfo = document.getElementById('website-type-info');
            const selectedWebsiteType = document.getElementById('selected-website-type');
            const websiteTypeDescription = document.getElementById('website-type-description');
            const websitePageRadio = document.getElementById('website_page');
            const mainSitePageRadio = document.getElementById('main_site_page');
            const isMainSiteHidden = document.getElementById('is_main_site');
            const websiteSelection = document.getElementById('website_selection');
            const homepageSelect = document.getElementById('homepage_select');
            const homepageWarning = document.getElementById('homepage_warning');
            
            // Handle page type change
            function handlePageTypeChange() {
                if (mainSitePageRadio.checked) {
                    isMainSiteHidden.value = '1';
                    websiteSelection.style.display = 'none';
                    websiteSelect.removeAttribute('required');
                    websiteTypeInfo.style.display = 'block';
                    selectedWebsiteType.textContent = 'Main Site';
                    websiteTypeDescription.innerHTML = `
                        <strong>Main Site Page:</strong> This page will only be accessible when visiting fundconnects.com domain.
                        <br><strong>URL:</strong> fundconnects.com/page/your-page-name
                        <br><strong>Note:</strong> Main site pages are independent of individual websites.
                    `;
                } else {
                    isMainSiteHidden.value = '0';
                    websiteSelection.style.display = 'block';
                    websiteSelect.setAttribute('required', 'required');
                    if (!websiteSelect.value) {
                        websiteTypeInfo.style.display = 'none';
                    }
                }
            }
            
            // Handle website selection change
            websiteSelect.addEventListener('change', function() {
                if (websitePageRadio.checked) {
                    const selectedOption = this.options[this.selectedIndex];
                    const websiteType = selectedOption.getAttribute('data-type');
                    
                    if (websiteType) {
                        selectedWebsiteType.textContent = websiteType.charAt(0).toUpperCase() + websiteType.slice(1);
                        
                        if (websiteType === 'investment') {
                            websiteTypeDescription.innerHTML = `
                                <strong>Investment Website:</strong> Pages will appear as sections on a single page with smooth scrolling navigation.
                                <br><strong>Note:</strong> Create pages normally - they will automatically be converted to sections on the frontend.
                            `;
                        } else {
                            websiteTypeDescription.innerHTML = `
                                <strong>Fundraiser Website:</strong> Each page will be a separate page with traditional multi-page navigation.
                            `;
                        }
                        
                        websiteTypeInfo.style.display = 'block';
                    } else {
                        websiteTypeInfo.style.display = 'none';
                    }
                }
            });
            
            // Handle homepage selection change
            homepageSelect.addEventListener('change', function() {
                if (this.value === '1') {
                    homepageWarning.style.display = 'block';
                } else {
                    homepageWarning.style.display = 'none';
                }
            });
            
            // Add event listeners for radio buttons
            websitePageRadio.addEventListener('change', handlePageTypeChange);
            mainSitePageRadio.addEventListener('change', handlePageTypeChange);
            
            // Initialize on page load
            handlePageTypeChange();
        });
    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
