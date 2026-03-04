@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .form-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section-header {
        background: #007bff;
        color: white;
        padding: 10px 15px;
        margin: -20px -20px 20px -20px;
        border-radius: 8px 8px 0 0;
        font-weight: bold;
    }
    .logo-item {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    .btn-toggle {
        min-width: 120px;
    }
    .preview-link {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    .json-editor {
        font-family: 'Courier New', monospace;
        min-height: 100px;
    }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Preview Link -->
        <a href="/dealmaker-demo" target="_blank" class="btn btn-primary preview-link">
            <i class="fas fa-external-link-alt me-2"></i>Preview Homepage
        </a>

        <div class="row">
            <div class="col-12">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fas fa-home icon-gradient bg-arielle-smile"></i>
                                </div>
                                <div>
                                    <span class="text-capitalize">DealMaker Homepage Settings</span>
                                    <div class="page-title-subheading">
                                        Manage all dynamic content for the DealMaker homepage from this admin panel.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('dealmaker.admin.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Meta Tags Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-tags me-2"></i>SEO & Meta Tags
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" class="form-control" name="meta_title" 
                                           value="{{ $setting->meta_title ?? '' }}" 
                                           placeholder="DealMaker | Raise Capital Online">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">OG Image</label>
                                    <input type="file" class="form-control" name="uploaded_og_image" accept="image/*">
                                    @if($setting->uploaded_og_image)
                                        <div class="mt-2">
                                            <img src="{{ asset($setting->uploaded_og_image) }}" alt="Current OG image" style="max-width: 200px; max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="2" 
                                              placeholder="Brief description for search engines">{{ $setting->meta_description ?? '' }}</textarea>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" 
                                           value="{{ $setting->meta_keywords ?? '' }}" 
                                           placeholder="capital raising, investment, funding">
                                </div>
                            </div>
                        </div>

                        <!-- Announcement Bar Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-bullhorn me-2"></i>Announcement Bar
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_announcement" id="show_announcement" 
                                               {{ ($setting->show_announcement ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_announcement">Show Announcement Bar</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Announcement Badge Text</label>
                                    <input type="text" class="form-control" name="announcement_badge" 
                                           value="{{ $setting->announcement_badge ?? 'GET READY' }}" 
                                           placeholder="GET READY">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Announcement Text</label>
                                    <input type="text" class="form-control" name="announcement_text" 
                                           value="{{ $setting->announcement_text ?? 'Announcing Sports As An Asset Class Summit, October 16th. Learn More' }}" 
                                           placeholder="Announcement message">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Announcement URL</label>
                                    <input type="text" class="form-control" name="announcement_url" 
                                           value="{{ $setting->announcement_url ?? '/assetclassconference' }}" 
                                           placeholder="/announcement-link">
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-bars me-2"></i>Navigation Settings
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Sign In Text</label>
                                    <input type="text" class="form-control" name="signin_text" 
                                           value="{{ $setting->signin_text ?? 'Sign In' }}" 
                                           placeholder="Sign In">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sign In URL</label>
                                    <input type="text" class="form-control" name="signin_url" 
                                           value="{{ $setting->signin_url ?? '/signin' }}" 
                                           placeholder="/signin">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Main CTA Text</label>
                                    <input type="text" class="form-control" name="main_cta_text" 
                                           value="{{ $setting->main_cta_text ?? 'Get Started' }}" 
                                           placeholder="Get Started">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Main CTA URL</label>
                                    <input type="text" class="form-control" name="main_cta_url" 
                                           value="{{ $setting->main_cta_url ?? '/connect' }}" 
                                           placeholder="/connect">
                                </div>
                            </div>
                        </div>

                        <!-- Video Settings Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-video me-2"></i>Video Settings
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Background Video URL</label>
                                    <input type="url" class="form-control" name="bg_video_url" 
                                           value="{{ $setting->bg_video_url ?? $setting->hero_background_video }}" 
                                           placeholder="https://www.youtube.com/watch?v=... or https://example.com/video.mp4">
                                    <small class="text-muted">YouTube, Vimeo, or direct video URL supported</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Video Poster Image URL</label>
                                    <input type="url" class="form-control" name="bg_video_poster_url" 
                                           value="{{ $setting->bg_video_poster_url ?? '' }}" 
                                           placeholder="https://example.com/poster.jpg">
                                    <small class="text-muted">Poster image for video (optional)</small>
                                </div>
                                {{-- <div class="col-md-6 mt-3">
                                    <label class="form-label">Modal Video URL (Desktop)</label>
                                    <input type="text" class="form-control" name="modal_video_desktop" 
                                           value="{{ $setting->modal_video_desktop ?? 'https://player.vimeo.com/video/927222983' }}" 
                                           placeholder="https://player.vimeo.com/video/...">
                                    <small class="text-muted">For Vimeo/YouTube embeds, use URL format</small>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Modal Video URL (Mobile)</label>
                                    <input type="text" class="form-control" name="modal_video_mobile" 
                                           value="{{ $setting->modal_video_mobile ?? 'https://player.vimeo.com/video/927222983' }}" 
                                           placeholder="https://player.vimeo.com/video/...">
                                    <small class="text-muted">For Vimeo/YouTube embeds, use URL format</small>
                                </div> --}}
                            </div>
                        </div>

                        <!-- Hero Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-star me-2"></i>Hero Section
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Hero Title</label>
                                    <input type="text" class="form-control" name="hero_title" 
                                           value="{{ $setting->hero_title ?? '' }}" 
                                           placeholder="The Future Of Retail Capital">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hero Subtitle</label>
                                    <input type="text" class="form-control" name="hero_subtitle" 
                                           value="{{ $setting->hero_subtitle ?? '' }}" 
                                           placeholder="Raise Boldly">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">CTA Button Text</label>
                                    <input type="text" class="form-control" name="hero_cta_text" 
                                           value="{{ $setting->hero_cta_text ?? '' }}" 
                                           placeholder="Get Started">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">CTA Button URL</label>
                                    <input type="text" class="form-control" name="hero_cta_url" 
                                           value="{{ $setting->hero_cta_url ?? '' }}" 
                                           placeholder="/connect">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Background Video URL</label>
                                    <input type="text" class="form-control" name="hero_background_video" 
                                           value="{{ $setting->hero_background_video ?? '' }}" 
                                           placeholder="https://example.com/video.mp4">
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Section -->
                        {{-- <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-chart-bar me-2"></i>Statistics Section
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Statistic 1 Number</label>
                                    <input type="text" class="form-control" name="stat_1_number" 
                                           value="{{ $setting->stat_1_number ?? '' }}" 
                                           placeholder="$2B+">
                                    <label class="form-label mt-2">Statistic 1 Text</label>
                                    <input type="text" class="form-control" name="stat_1_text" 
                                           value="{{ $setting->stat_1_text ?? '' }}" 
                                           placeholder="Raised by customers">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Statistic 2 Number</label>
                                    <input type="text" class="form-control" name="stat_2_number" 
                                           value="{{ $setting->stat_2_number ?? '' }}" 
                                           placeholder="1.5B+">
                                    <label class="form-label mt-2">Statistic 2 Text</label>
                                    <input type="text" class="form-control" name="stat_2_text" 
                                           value="{{ $setting->stat_2_text ?? '' }}" 
                                           placeholder="Investments processed">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Statistic 3 Number</label>
                                    <input type="text" class="form-control" name="stat_3_number" 
                                           value="{{ $setting->stat_3_number ?? '' }}" 
                                           placeholder="900+">
                                    <label class="form-label mt-2">Statistic 3 Text</label>
                                    <input type="text" class="form-control" name="stat_3_text" 
                                           value="{{ $setting->stat_3_text ?? '' }}" 
                                           placeholder="Offerings">
                                </div>
                            </div>
                        </div> --}}

                        <!-- Capital Raising Steps Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-tasks me-2"></i>Capital Raising Steps (Plan, Raise, Engage, Repeat)
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_difference_section" id="show_difference_section" 
                                               {{ ($setting->show_difference_section ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_difference_section">Show Capital Raising Steps Section</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Section Eyebrow Text</label>
                                    <input type="text" class="form-control" name="difference_eyebrow_text" 
                                           value="{{ $setting->difference_eyebrow_text ?? 'DealMaker Difference' }}" 
                                           placeholder="DealMaker Difference">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control" name="difference_section_title" 
                                           value="{{ $setting->difference_section_title ?? '#1 in capital raising' }}" 
                                           placeholder="#1 in capital raising">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6>Plan Tab Content</h6>
                                    <label class="form-label">Plan - Title</label>
                                    <input type="text" class="form-control" name="plan_title" 
                                           value="{{ $setting->plan_title ?? 'Personalized Raise Strategy' }}" 
                                           placeholder="Personalized Raise Strategy">
                                    <label class="form-label mt-2">Plan - Description</label>
                                    <textarea class="form-control" name="plan_description" rows="3" 
                                              placeholder="Successful capital raises start with the right strategy...">{{ $setting->plan_description ?? 'Successful capital raises start with the right strategy. DealMaker works with you to plan every aspect of your raise strategy - whether it\'s your first retail raise or you\'re a multiple raise professional.' }}</textarea>
                                    <label class="form-label mt-2">Plan - Button Text</label>
                                    <input type="text" class="form-control" name="plan_button_text" 
                                           value="{{ $setting->plan_button_text ?? 'Learn More' }}" 
                                           placeholder="Learn More">
                                    <label class="form-label mt-2">Plan - Button URL</label>
                                    <input type="text" class="form-control" name="plan_button_url" 
                                           value="{{ $setting->plan_button_url ?? '/connect' }}" 
                                           placeholder="/connect">
                                </div>
                                <div class="col-md-6">
                                    <h6>Raise Tab Content</h6>
                                    <label class="form-label">Raise - Title</label>
                                    <input type="text" class="form-control" name="raise_title" 
                                           value="{{ $setting->raise_title ?? 'Execute Your Campaign' }}" 
                                           placeholder="Execute Your Campaign">
                                    <label class="form-label mt-2">Raise - Description</label>
                                    <textarea class="form-control" name="raise_description" rows="3" 
                                              placeholder="Launch your capital raising campaign...">{{ $setting->raise_description ?? 'Launch your capital raising campaign with confidence. Our platform provides all the tools you need to attract investors, manage compliance, and process investments seamlessly.' }}</textarea>
                                    <label class="form-label mt-2">Raise - Button Text</label>
                                    <input type="text" class="form-control" name="raise_button_text" 
                                           value="{{ $setting->raise_button_text ?? 'Learn More' }}" 
                                           placeholder="Learn More">
                                    <label class="form-label mt-2">Raise - Button URL</label>
                                    <input type="text" class="form-control" name="raise_button_url" 
                                           value="{{ $setting->raise_button_url ?? '/connect' }}" 
                                           placeholder="/connect">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6>Engage Tab Content</h6>
                                    <label class="form-label">Engage - Title</label>
                                    <input type="text" class="form-control" name="engage_title" 
                                           value="{{ $setting->engage_title ?? 'Build Your Community' }}" 
                                           placeholder="Build Your Community">
                                    <label class="form-label mt-2">Engage - Description</label>
                                    <textarea class="form-control" name="engage_description" rows="3" 
                                              placeholder="Engage with your investor community...">{{ $setting->engage_description ?? 'Engage with your investor community through our comprehensive investor relations tools. Keep your investors informed, engaged, and excited about your company\'s growth.' }}</textarea>
                                    <label class="form-label mt-2">Engage - Button Text</label>
                                    <input type="text" class="form-control" name="engage_button_text" 
                                           value="{{ $setting->engage_button_text ?? 'Learn More' }}" 
                                           placeholder="Learn More">
                                    <label class="form-label mt-2">Engage - Button URL</label>
                                    <input type="text" class="form-control" name="engage_button_url" 
                                           value="{{ $setting->engage_button_url ?? '/connect' }}" 
                                           placeholder="/connect">
                                </div>
                                <div class="col-md-6">
                                    <h6>Repeat Tab Content</h6>
                                    <label class="form-label">Repeat - Title</label>
                                    <input type="text" class="form-control" name="repeat_title" 
                                           value="{{ $setting->repeat_title ?? 'Capitalize On Multiple Raises' }}" 
                                           placeholder="Capitalize On Multiple Raises">
                                    <label class="form-label mt-2">Repeat - Description</label>
                                    <textarea class="form-control" name="repeat_description" rows="3" 
                                              placeholder="Over 80% of DealMaker's customers do multiple raises...">{{ $setting->repeat_description ?? 'Over 80% of DealMaker\'s customers do multiple raises. Create and execute a multi-raise strategy aligned to your growth trajectory - from seed and growth to IPO and beyond.' }}</textarea>
                                    <label class="form-label mt-2">Repeat - Button Text</label>
                                    <input type="text" class="form-control" name="repeat_button_text" 
                                           value="{{ $setting->repeat_button_text ?? 'Learn More' }}" 
                                           placeholder="Learn More">
                                    <label class="form-label mt-2">Repeat - Button URL</label>
                                    <input type="text" class="form-control" name="repeat_button_url" 
                                           value="{{ $setting->repeat_button_url ?? '/connect' }}" 
                                           placeholder="/connect">
                                </div>
                            </div>
                        </div>

                        <!-- Branding Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-image me-2"></i>Branding & Logo
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Site Logo</label>
                                    <input type="file" class="form-control" name="uploaded_logo" accept="image/*">
                                    @if($setting->uploaded_logo)
                                        <div class="mt-2">
                                            <img src="{{ asset($setting->uploaded_logo) }}" alt="Current logo" style="max-width: 150px; max-height: 80px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Site Tagline</label>
                                    <input type="text" class="form-control" name="site_tagline" 
                                           value="{{ $setting->site_tagline ?? '' }}" 
                                           placeholder="DealMaker Logo">
                                </div>
                            </div>
                        </div>

                        <!-- Client Logos Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-building me-2"></i>Client Logos
                            </div>
                            <div id="client-logos-container">
                                @if($setting && $setting->client_logos)
                                    @php $logos = $setting->client_logos ?? []; @endphp
                                    @foreach($logos as $index => $logo)
                                        <div class="logo-item" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="client_logos[{{ $index }}][name]" 
                                                           value="{{ $logo['name'] }}" placeholder="Company Name">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="client_logos[{{ $index }}][image]" 
                                                           value="{{ $logo['image'] }}" placeholder="Logo Image URL">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="client_logos[{{ $index }}][url]" 
                                                           value="{{ $logo['url'] ?? '' }}" placeholder="Company Website URL (optional)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-logo">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-logo" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Logo
                            </button>
                        </div>

                        <!-- Phone Slider Section -->
                        {{-- <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-mobile-alt me-2"></i>Phone Slider Content
                            </div>
                            <div id="slider-images-container">
                                @if($setting && $setting->slider_images)
                                    @php $slides = $setting->slider_images ?? []; @endphp
                                    @foreach($slides as $index => $slide)
                                        <div class="logo-item" data-index="{{ $index }}">
                                            <h6>Slide {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone Image URL</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][image]" 
                                                           value="{{ $slide['image'] }}" placeholder="Phone mockup image URL">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Slide Title</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][title]" 
                                                           value="{{ $slide['title'] }}" placeholder="Slide title">
                                                </div>
                                                <div class="col-md-8 mt-2">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="slider_images[{{ $index }}][description]" rows="2" 
                                                              placeholder="Slide description">{{ $slide['description'] }}</textarea>
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">CTA Text</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][cta_text]" 
                                                           value="{{ $slide['cta_text'] ?? 'Start Now' }}" placeholder="Button text">
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">CTA URL</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][cta_url]" 
                                                           value="{{ $slide['cta_url'] ?? '/connect' }}" placeholder="Button link">
                                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-slide">
                                                        <i class="fas fa-trash"></i> Remove Slide
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-slide" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Slide
                            </button>
                        </div> --}}

                        <!-- Case Studies Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-chart-line me-2"></i>Case Studies
                            </div>
                            <div id="case-studies-container">
                                @if($setting && $setting->case_studies)
                                    @php $caseStudies = $setting->case_studies ?? []; @endphp
                                    @foreach($caseStudies as $index => $study)
                                        <div class="logo-item" data-index="{{ $index }}">
                                            <h6>Case Study {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Company Name</label>
                                                    <input type="text" class="form-control" name="case_studies[{{ $index }}][name]" 
                                                           value="{{ $study['name'] }}" placeholder="Company Name">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Company Logo URL</label>
                                                    <input type="text" class="form-control" name="case_studies[{{ $index }}][logo]" 
                                                           value="{{ $study['logo'] }}" placeholder="Logo Image URL">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Featured Image URL</label>
                                                    <input type="text" class="form-control" name="case_studies[{{ $index }}][image]" 
                                                           value="{{ $study['image'] }}" placeholder="Featured Image URL">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Learn More URL</label>
                                                    <input type="text" class="form-control" name="case_studies[{{ $index }}][learn_more_url]" 
                                                           value="{{ $study['learn_more_url'] ?? '#' }}" placeholder="Case study URL">
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="case_studies[{{ $index }}][description]" rows="2" 
                                                              placeholder="Company description">{{ $study['description'] }}</textarea>
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">Capital Raised (M)</label>
                                                    <input type="number" class="form-control" name="case_studies[{{ $index }}][capital_raised]" 
                                                           value="{{ $study['capital_raised'] }}" placeholder="31" step="0.1">
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">Investors (K)</label>
                                                    <input type="number" class="form-control" name="case_studies[{{ $index }}][investors]" 
                                                           value="{{ $study['investors'] }}" placeholder="13" step="0.1">
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm remove-case-study">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-case-study" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Case Study
                            </button>
                        </div>

                        <!-- Service Tabs Section -->
                        {{-- <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-th-large me-2"></i>Service Tabs Content
                            </div>
                            <div id="service-tabs-container">
                                @if($setting && $setting->difference_tabs)
                                    @php $serviceTabs = $setting->difference_tabs ?? []; @endphp
                                    @foreach($serviceTabs as $index => $tab)
                                        <div class="logo-item" data-index="{{ $index }}">
                                            <h6>Service Tab {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Tab Title</label>
                                                    <input type="text" class="form-control" name="difference_tabs[{{ $index }}][title]" 
                                                           value="{{ $tab['title'] }}" placeholder="Personalized Raise Strategy">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Tab Icon/Image URL</label>
                                                    <input type="text" class="form-control" name="difference_tabs[{{ $index }}][icon]" 
                                                           value="{{ $tab['icon'] ?? '' }}" placeholder="Icon/Image URL">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Learn More URL</label>
                                                    <input type="text" class="form-control" name="difference_tabs[{{ $index }}][url]" 
                                                           value="{{ $tab['url'] ?? '/connect' }}" placeholder="/connect">
                                                </div>
                                                <div class="col-md-10 mt-2">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="difference_tabs[{{ $index }}][description]" rows="3" 
                                                              placeholder="Service description">{{ $tab['description'] }}</textarea>
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm remove-service-tab mt-4">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-service-tab" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Service Tab
                            </button>
                        </div> --}}

                        <!-- Section Visibility Controls -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-eye me-2"></i>Section Visibility Controls
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_announcement" id="show_announcement" 
                                               {{ ($setting->show_announcement ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_announcement">Announcement Bar</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_hero" id="show_hero" 
                                               {{ ($setting->show_hero ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_hero">Hero Section</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_stats" id="show_stats" 
                                               {{ ($setting->show_stats ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_stats">Statistics</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_slider" id="show_slider" 
                                               {{ ($setting->show_slider ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_slider">Phone Slider</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_client_logos" id="show_client_logos" 
                                               {{ ($setting->show_client_logos ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_client_logos">Client Logos</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_case_studies" id="show_case_studies" 
                                               {{ ($setting->show_case_studies ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_case_studies">Case Studies</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_service_tabs" id="show_service_tabs" 
                                               {{ ($setting->show_service_tabs ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_service_tabs">Service Tabs</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_about" id="show_about" 
                                               {{ ($setting->show_about ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_about">About Section</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_services" id="show_services" 
                                               {{ ($setting->show_services ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_services">Services</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_testimonials" id="show_testimonials" 
                                               {{ ($setting->show_testimonials ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_testimonials">Testimonials</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_contact" id="show_contact" 
                                               {{ ($setting->show_contact ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_contact">Contact</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Code Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-code me-2"></i>Custom Code Injection
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Custom CSS</label>
                                    <textarea class="form-control json-editor" name="custom_css" rows="8" 
                                              placeholder="/* Custom CSS styles */">{{ $setting->custom_css ?? '' }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Custom JavaScript</label>
                                    <textarea class="form-control json-editor" name="custom_js" rows="8" 
                                              placeholder="/* Custom JavaScript code */">{{ $setting->custom_js ?? '' }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Custom Head Code</label>
                                    <textarea class="form-control json-editor" name="custom_head_code" rows="8" 
                                              placeholder="<!-- Custom HTML for <head> section -->">{{ $setting->custom_head_code ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section Background Colors -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-palette me-2"></i>Section Background Colors
                            </div>
                            @php
                                $backgroundColors = $setting->getAllSectionBackgroundColors() ?? [];
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Hero Section Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[hero]" 
                                           value="{{ $backgroundColors['hero'] ?? '#000000' }}">
                                    <small class="text-muted">Background color for the main hero section</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Announcement Bar Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[announcement]" 
                                           value="{{ $backgroundColors['announcement'] ?? '#f8f9fa' }}">
                                    <small class="text-muted">Background color for the announcement bar</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                {{-- <div class="col-md-6">
                                    <label class="form-label">Statistics Section Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[stats]" 
                                           value="{{ $backgroundColors['stats'] ?? '#ffffff' }}">
                                    <small class="text-muted">Background color for the statistics section</small>
                                </div> --}}
                                <div class="col-md-6">
                                    <label class="form-label">Client Logos Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[client_logos]" 
                                           value="{{ $backgroundColors['client_logos'] ?? '#ffffff' }}">
                                    <small class="text-muted">Background color for the client logos section</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Case Studies Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[case_studies]" 
                                           value="{{ $backgroundColors['case_studies'] ?? '#f8f9fa' }}">
                                    <small class="text-muted">Background color for the case studies section</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Capital Raising Steps Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[difference_section]" 
                                           value="{{ $backgroundColors['difference_section'] ?? '#ffffff' }}">
                                    <small class="text-muted">Background color for the capital raising steps section</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Testimonials Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[testimonials]" 
                                           value="{{ $backgroundColors['testimonials'] ?? '#f8f9fa' }}">
                                    <small class="text-muted">Background color for the testimonials section</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Capital Revolutionized Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[capital_revolutionized]" 
                                           value="{{ $backgroundColors['capital_revolutionized'] ?? '#ffffff' }}">
                                    <small class="text-muted">Background color for the capital revolutionized section</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Final CTA Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[final_cta]" 
                                           value="{{ $backgroundColors['final_cta'] ?? '#000000' }}">
                                    <small class="text-muted">Background color for the final call-to-action section</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Footer Background</label>
                                    <input type="color" class="form-control" name="section_background_colors[footer]" 
                                           value="{{ $backgroundColors['footer'] ?? '#000000' }}">
                                    <small class="text-muted">Background color for the footer section</small>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="mb-3"><i class="fas fa-mouse-pointer me-2"></i>Button Colors</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Primary Button Color</label>
                                    <input type="color" class="form-control" name="button_primary_color" 
                                           value="{{ $setting->button_primary_color ?? '#f31cb6' }}">
                                    <small class="text-muted">Default color for buttons (excluding transparent buttons)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Button Hover/Active Color</label>
                                    <input type="color" class="form-control" name="button_hover_color" 
                                           value="{{ $setting->button_hover_color ?? '#d1179a' }}">
                                    <small class="text-muted">Color when hovering or clicking buttons</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Button Text Color</label>
                                    <input type="color" class="form-control" name="button_text_color" 
                                           value="{{ $setting->button_text_color ?? '#ffffff' }}">
                                    <small class="text-muted">Text color for all buttons (excluding transparent buttons)</small>
                                </div>
                            </div>
                            
                            <!-- Social Icon Colors Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="mb-3"><i class="fas fa-share-alt me-2"></i>Social Icon Colors</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Social Icon Background Color</label>
                                    <input type="color" class="form-control" name="social_icon_bg_color" 
                                           value="{{ $setting->social_icon_bg_color ?? '#f31cb6' }}">
                                    <small class="text-muted">Background color for social media icons</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Social Icon Hover Color</label>
                                    <input type="color" class="form-control" name="social_icon_hover_color" 
                                           value="{{ $setting->social_icon_hover_color ?? '#d1179a' }}">
                                    <small class="text-muted">Background color when hovering social icons</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Social Icon Color</label>
                                    <input type="color" class="form-control" name="social_icon_color" 
                                           value="{{ $setting->social_icon_color ?? '#ffffff' }}">
                                    <small class="text-muted">Color of the actual social media icons</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section Menu Toggles -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-list me-2"></i>Section Menu Toggles
                                <small class="text-muted d-block mt-1">Enable sections to appear in the navigation menu with smooth scroll links</small>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_hero" 
                                               {{ $setting->menu_hero ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-home me-2"></i>Hero Section (Home)
                                        </label>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_about" 
                                               {{ $setting->menu_about ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-info-circle me-2"></i>About Section
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_services" 
                                               {{ $setting->menu_services ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-cogs me-2"></i>Services Section
                                        </label>
                                    </div>
                                </div> --}}
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_logos" 
                                               {{ $setting->menu_logos ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-handshake me-2"></i>Partners Section
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_cases" 
                                               {{ $setting->menu_cases ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-briefcase me-2"></i>Case Studies
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_difference" 
                                               {{ $setting->menu_difference ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-star me-2"></i>Why Us Section
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_testimonials" 
                                               {{ $setting->menu_testimonials ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-quote-right me-2"></i>Testimonials
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_solutions" 
                                               {{ $setting->menu_solutions ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-lightbulb me-2"></i>Solutions Section
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="menu_cta" 
                                               {{ $setting->menu_cta ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-rocket me-2"></i>Get Started Section
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Menu Section -->
                        {{-- <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-bars me-2"></i>Navigation Menu
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Raise Capital Menu Title</label>
                                    <input type="text" class="form-control" name="nav_raise_capital_title" 
                                           value="{{ $setting->nav_raise_capital_title ?? 'Raise Capital' }}"
                                           placeholder="Raise Capital">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Products Menu Title</label>
                                    <input type="text" class="form-control" name="nav_products_title" 
                                           value="{{ $setting->nav_products_title ?? 'Products' }}"
                                           placeholder="Products">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Resources Menu Title</label>
                                    <input type="text" class="form-control" name="nav_resources_title" 
                                           value="{{ $setting->nav_resources_title ?? 'Resources' }}"
                                           placeholder="Resources">
                                </div>
                            </div>
                        </div> --}}

                        <!-- Platform Section Content -->
                        {{-- <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-chart-line me-2"></i>Platform Section (Capital Redefined)
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control" name="platform_section_title" 
                                           value="{{ $setting->platform_section_title ?? 'Capital Redefined' }}"
                                           placeholder="Capital Redefined">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CTA Text</label>
                                    <input type="text" class="form-control" name="platform_cta_text" 
                                           value="{{ $setting->platform_cta_text ?? 'Download Now' }}"
                                           placeholder="Download Now">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Section Description</label>
                                    <textarea class="form-control" name="platform_section_description" rows="3" 
                                              placeholder="Capture the power of individual investors...">{{ $setting->platform_section_description ?? 'Capture the power of individual investors with our guide to the new capital stack.' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CTA URL</label>
                                    <input type="text" class="form-control" name="platform_cta_url" 
                                           value="{{ $setting->platform_cta_url ?? '/new-capital-stack' }}"
                                           placeholder="/new-capital-stack">
                                </div>
                            </div>
                        </div> --}}

                        <!-- Slider Content Section -->
                        {{-- <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-images me-2"></i>Slider Content
                            </div>
                            
                            <!-- Slide 2 -->
                            <h5 class="mb-3">Slide 2 - "Raise Boldly" Content</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Slide 2 Title</label>
                                    <input type="text" class="form-control" name="slide_2_title" 
                                           value="{{ $setting->slide_2_title ?? 'Raise Boldly.<br />Own Your Future.' }}"
                                           placeholder="Raise Boldly. Own Your Future.">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slide 2 CTA Text</label>
                                    <input type="text" class="form-control" name="slide_2_cta_text" 
                                           value="{{ $setting->slide_2_cta_text ?? 'Start Now' }}"
                                           placeholder="Start Now">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Slide 2 Description</label>
                                    <textarea class="form-control" name="slide_2_description" rows="3" 
                                              placeholder="Unlock the power of retail capital...">{{ $setting->slide_2_description ?? 'Unlock the power of retail capital. Raise the capital you need to drive growth while building your brand and community. And unlike venture capital or private equity - you control the terms.' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slide 2 CTA URL</label>
                                    <input type="text" class="form-control" name="slide_2_cta_url" 
                                           value="{{ $setting->slide_2_cta_url ?? '/connect' }}"
                                           placeholder="/connect">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Slide 3 -->
                            <h5 class="mb-3">Slide 3 - "Real Capital" Content</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Slide 3 Title</label>
                                    <input type="text" class="form-control" name="slide_3_title" 
                                           value="{{ $setting->slide_3_title ?? 'Real Capital.<br />Retail Experience.' }}"
                                           placeholder="Real Capital. Retail Experience.">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slide 3 CTA Text</label>
                                    <input type="text" class="form-control" name="slide_3_cta_text" 
                                           value="{{ $setting->slide_3_cta_text ?? 'Start Now' }}"
                                           placeholder="Start Now">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Slide 3 Description</label>
                                    <textarea class="form-control" name="slide_3_description" rows="3" 
                                              placeholder="Raise up to $75M annually...">{{ $setting->slide_3_description ?? 'Raise up to $75M annually with Reg A offerings. The capital you need - no road shows, no trips to Sand Hill Road, no waiting for a term sheet. Digital capital raising is changing the game.' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slide 3 CTA URL</label>
                                    <input type="text" class="form-control" name="slide_3_cta_url" 
                                           value="{{ $setting->slide_3_cta_url ?? '/connect' }}"
                                           placeholder="/connect">
                                </div>
                            </div>
                        </div> --}}

                        <!-- Case Study Labels Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-chart-bar me-2"></i>Case Study Labels
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Capital Raised Label</label>
                                    <input type="text" class="form-control" name="case_study_capital_raised_label" 
                                           value="{{ $setting->case_study_capital_raised_label ?? 'Capital Raised' }}"
                                           placeholder="Capital Raised">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Investors Label</label>
                                    <input type="text" class="form-control" name="case_study_investors_label" 
                                           value="{{ $setting->case_study_investors_label ?? 'Investors' }}"
                                           placeholder="Investors">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Learn More Button Text</label>
                                    <input type="text" class="form-control" name="case_study_learn_more_text" 
                                           value="{{ $setting->case_study_learn_more_text ?? 'Learn More' }}"
                                           placeholder="Learn More">
                                </div>
                            </div>
                        </div>

                        <!-- Capital Revolutionized Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-coins me-2"></i>Capital Revolutionized Section
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control" name="capital_revolutionized_title" 
                                           value="{{ $setting->capital_revolutionized_title ?? 'Capital raising, revolutionized' }}"
                                           placeholder="Capital raising, revolutionized">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Section Description</label>
                                    <textarea class="form-control" name="capital_revolutionized_description" rows="3" 
                                              placeholder="Craft the perfect offering with control over raise amount...">{{ $setting->capital_revolutionized_description ?? 'Craft the perfect offering with control over raise amount, valuation, voting rights, and beyond. With us, your strategy takes center stage.' }}</textarea>
                                </div>
                            </div>
                            
                            <!-- Regulation Circles -->
                            <h5 class="mt-4 mb-3">Regulation Circles</h5>
                            
                            <!-- Reg CF Circle -->
                            <div class="row mb-3">
                                <div class="col-md-12"><h6>Reg CF Circle</h6></div>
                                <div class="col-md-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="reg_cf_title" 
                                           value="{{ $setting->reg_cf_title ?? 'Via Reg CF' }}"
                                           placeholder="Via Reg CF">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" class="form-control" name="reg_cf_subtitle" 
                                           value="{{ $setting->reg_cf_subtitle ?? 'Raise up to' }}"
                                           placeholder="Raise up to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color" name="section_background_colors[reg_cf_bg_color]" 
                                           value="{{ $setting->getRegulationColor('reg_cf', 'bg_color', '#1F2937') }}"
                                           title="Choose background color">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bold Text Color</label>
                                    <input type="color" class="form-control form-control-color" name="section_background_colors[reg_cf_bold_text_color]" 
                                           value="{{ $setting->getRegulationColor('reg_cf', 'bold_text_color', '#14B8A6') }}"
                                           title="Choose bold text color">
                                </div>
                            </div>
                            
                            <!-- Reg A Circle -->
                            <div class="row mb-3">
                                <div class="col-md-12"><h6>Reg A Circle</h6></div>
                                <div class="col-md-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="reg_a_title" 
                                           value="{{ $setting->reg_a_title ?? 'Via Reg A' }}"
                                           placeholder="Via Reg A">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" class="form-control" name="reg_a_subtitle" 
                                           value="{{ $setting->reg_a_subtitle ?? 'Raise up to' }}"
                                           placeholder="Raise up to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color" name="section_background_colors[reg_a_bg_color]" 
                                           value="{{ $setting->getRegulationColor('reg_a', 'bg_color', '#1F2937') }}"
                                           title="Choose background color">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bold Text Color</label>
                                    <input type="color" class="form-control form-control-color" name="section_background_colors[reg_a_bold_text_color]" 
                                           value="{{ $setting->getRegulationColor('reg_a', 'bold_text_color', '#14B8A6') }}"
                                           title="Choose bold text color">
                                </div>
                            </div>
                            
                            <!-- Reg D Circle -->
                            <div class="row mb-3">
                                <div class="col-md-12"><h6>Reg D Circle</h6></div>
                                <div class="col-md-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="reg_d_title" 
                                           value="{{ $setting->reg_d_title ?? 'Via Reg D' }}"
                                           placeholder="Via Reg D">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" class="form-control" name="reg_d_subtitle" 
                                           value="{{ $setting->reg_d_subtitle ?? 'Raise up to' }}"
                                           placeholder="Raise up to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color" name="section_background_colors[reg_d_bg_color]" 
                                           value="{{ $setting->getRegulationColor('reg_d', 'bg_color', '#1F2937') }}"
                                           title="Choose background color">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bold Text Color</label>
                                    <input type="color" class="form-control form-control-color" name="section_background_colors[reg_d_bold_text_color]" 
                                           value="{{ $setting->getRegulationColor('reg_d', 'bold_text_color', '#14B8A6') }}"
                                           title="Choose bold text color">
                                </div>
                            </div>
                        </div>

                        <!-- Final CTA Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-bullhorn me-2"></i>Final CTA Section
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Main Title</label>
                                    <input type="text" class="form-control" name="final_cta_main_title" 
                                           value="{{ $setting->final_cta_main_title ?? 'Your vision. Your terms.' }}"
                                           placeholder="Your vision. Your terms.">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Main Description</label>
                                    <textarea class="form-control" name="final_cta_main_description" rows="3" 
                                              placeholder="Craft the perfect offering with control over raise amount...">{{ $setting->final_cta_main_description ?? 'Craft the perfect offering with control over raise amount, valuation, voting rights, and beyond. With us, your strategy takes center stage.' }}</textarea>
                                </div>
                            </div>
                            
                            <!-- CTA Buttons -->
                            <h5 class="mt-4 mb-3">Call-to-Action Buttons</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Primary Button Text</label>
                                    <input type="text" class="form-control" name="final_cta_primary_button_text" 
                                           value="{{ $setting->final_cta_primary_button_text ?? 'Book a Call' }}"
                                           placeholder="Book a Call">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Primary Button URL</label>
                                    <input type="text" class="form-control" name="final_cta_primary_button_url" 
                                           value="{{ $setting->final_cta_primary_button_url ?? '/connect' }}"
                                           placeholder="/connect">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Secondary Button Text</label>
                                    <input type="text" class="form-control" name="final_cta_secondary_button_text" 
                                           value="{{ $setting->final_cta_secondary_button_text ?? 'View Case Studies' }}"
                                           placeholder="View Case Studies">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Secondary Button URL</label>
                                    <input type="text" class="form-control" name="final_cta_secondary_button_url" 
                                           value="{{ $setting->final_cta_secondary_button_url ?? '/category/case-studies' }}"
                                           placeholder="/category/case-studies">
                                </div>
                            </div>
                            
                            <!-- Background Images -->
                            <h5 class="mt-4 mb-3">Background Images</h5>
                            <div class="alert alert-info">
                                <small><i class="fas fa-info-circle"></i> <strong>Upload Requirements:</strong> Images must be under 2MB. Supported formats: JPEG, PNG, JPG, GIF, WEBP. Please compress large images before uploading.</small>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Growth Image</label>
                                    <input type="file" class="form-control" name="final_cta_growth_image" accept="image/*">
                                    @if($setting && $setting->final_cta_growth_image)
                                        <small class="text-muted">Current: {{ basename($setting->final_cta_growth_image) }}</small>
                                    @endif
                                    <small class="text-muted">Max size: 2MB</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sky Image</label>
                                    <input type="file" class="form-control" name="final_cta_sky_image" accept="image/*">
                                    @if($setting && $setting->final_cta_sky_image)
                                        <small class="text-muted">Current: {{ basename($setting->final_cta_sky_image) }}</small>
                                    @endif
                                    <small class="text-muted">Max size: 2MB</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City Image</label>
                                    <input type="file" class="form-control" name="final_cta_city_image" accept="image/*">
                                    @if($setting && $setting->final_cta_city_image)
                                        <small class="text-muted">Current: {{ basename($setting->final_cta_city_image) }}</small>
                                    @endif
                                    <small class="text-muted">Max size: 2MB</small>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonials Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-quote-left me-2"></i>Testimonials Section
                            </div>
                            
                            <div id="testimonials-container">
                                @if($setting && $setting->testimonials)
                                    @foreach($setting->testimonials as $index => $testimonial)
                                        <div class="logo-item testimonial-item">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Testimonial Quote</label>
                                                    <textarea class="form-control" name="testimonials[{{$index}}][quote]" rows="3" 
                                                              placeholder="Enter testimonial quote">{{ $testimonial['quote'] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Client Name</label>
                                                    <input type="text" class="form-control" name="testimonials[{{$index}}][name]" 
                                                           value="{{ $testimonial['name'] ?? '' }}" placeholder="Client Name">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Company</label>
                                                    <input type="text" class="form-control" name="testimonials[{{$index}}][company]" 
                                                           value="{{ $testimonial['company'] ?? '' }}" placeholder="Company Name">
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <label class="form-label">Client Image</label>
                                                    <input type="file" class="form-control" name="testimonials[{{$index}}][image]" 
                                                           accept="image/*">
                                                    @if(isset($testimonial['image']) && $testimonial['image'])
                                                        <small class="text-muted">Current: {{ basename($testimonial['image']) }}</small>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-testimonial">
                                                        <i class="fas fa-trash"></i> Remove Testimonial
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <button type="button" id="add-testimonial" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add Testimonial
                            </button>
                        </div>

                        <!-- Footer Content Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-info-circle me-2"></i>Footer Content
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Company Description</label>
                                    <textarea class="form-control" name="footer_company_description" rows="4" 
                                              placeholder="DealMaker provides comprehensive capital raising technology...">{{ $setting->footer_company_description ?? 'DealMaker provides comprehensive capital raising technology that transforms how companies raise funds, engage investors, and build community.' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Address</label>
                                    <textarea class="form-control" name="footer_company_address" rows="4" 
                                              placeholder="30 East 23rd St. Fl. 2, New York, NY 10010">{{ $setting->footer_company_address ?? '30 East 23rd St. Fl. 2 New York, NY 10010' }}</textarea>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Award/Certificate Image URL</label>
                                    <input type="text" class="form-control" name="footer_award_image" 
                                           value="{{ $setting->footer_award_image ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/65784614f244b62e543d68de_Deloitte%20Companies%20to%20watch%20award%20(Facebook%20Cover)%20(4)%201.png' }}" 
                                           placeholder="Award image URL">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Legal Links Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-link me-2"></i>Footer Legal Links
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Terms of Service URL</label>
                                    <input type="text" class="form-control" name="footer_terms_url" 
                                           value="{{ $setting->footer_terms_url ?? '/terms' }}" placeholder="/terms">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Privacy Policy URL</label>
                                    <input type="text" class="form-control" name="footer_privacy_url" 
                                           value="{{ $setting->footer_privacy_url ?? '/privacy' }}" placeholder="/privacy">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Cookies URL</label>
                                    <input type="text" class="form-control" name="footer_cookies_url" 
                                           value="{{ $setting->footer_cookies_url ?? '/cookies' }}" placeholder="/cookies">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Security URL</label>
                                    <input type="text" class="form-control" name="footer_security_url" 
                                           value="{{ $setting->footer_security_url ?? '/security' }}" placeholder="/security">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Accessibility URL</label>
                                    <input type="text" class="form-control" name="footer_accessibility_url" 
                                           value="{{ $setting->footer_accessibility_url ?? '/accessibility' }}" placeholder="/accessibility">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Copyright Text</label>
                                    <input type="text" class="form-control" name="footer_copyright_text" 
                                           value="{{ $setting->footer_copyright_text ?? '© 2025 DealMaker. All rights reserved.' }}" 
                                           placeholder="© 2025 DealMaker. All rights reserved.">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fab fa-facebook me-2"></i>Social Media Links
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">LinkedIn</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="show_linkedin" value="1" 
                                                   {{ ($setting->show_linkedin ?? true) ? 'checked' : '' }}>
                                        </div>
                                        <input type="text" class="form-control" name="linkedin_url" 
                                               value="{{ $setting->linkedin_url ?? '' }}" 
                                               placeholder="https://linkedin.com/company/dealmaker">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Twitter/X</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="show_twitter" value="1" 
                                                   {{ ($setting->show_twitter ?? true) ? 'checked' : '' }}>
                                        </div>
                                        <input type="text" class="form-control" name="twitter_url" 
                                               value="{{ $setting->twitter_url ?? '' }}" 
                                               placeholder="https://twitter.com/dealmaker">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Facebook</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="show_facebook" value="1" 
                                                   {{ ($setting->show_facebook ?? true) ? 'checked' : '' }}>
                                        </div>
                                        <input type="text" class="form-control" name="facebook_url" 
                                               value="{{ $setting->facebook_url ?? '' }}" 
                                               placeholder="https://facebook.com/dealmaker">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Instagram</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="show_instagram" value="1" 
                                                   {{ ($setting->show_instagram ?? true) ? 'checked' : '' }}>
                                        </div>
                                        <input type="text" class="form-control" name="instagram_url" 
                                               value="{{ $setting->instagram_url ?? '' }}" 
                                               placeholder="https://instagram.com/dealmaker">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    Check the box to show the social media link in the footer, and enter the URL for your profile.
                                </small>
                            </div>
                        </div>

                        <!-- Dynamic Footer Columns Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-columns me-2"></i>Dynamic Footer Columns
                            </div>
                            
                            <div id="footer-columns-container">
                                @if($setting && $setting->footer_menu_columns)
                                    @foreach($setting->footer_menu_columns as $columnIndex => $column)
                                        <div class="logo-item footer-column-item">
                                            <h6>Column {{ $columnIndex + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Column Title</label>
                                                    <input type="text" class="form-control" name="footer_menu_columns[{{ $columnIndex }}][title]" 
                                                           value="{{ $column['title'] ?? '' }}" placeholder="RAISE CAPITAL">
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-footer-column">
                                                        <i class="fas fa-trash"></i> Remove Column
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <label class="form-label">Column Links</label>
                                                <div class="footer-links-container" data-column="{{ $columnIndex }}">
                                                    @if(isset($column['links']) && is_array($column['links']))
                                                        @foreach($column['links'] as $linkIndex => $link)
                                                            <div class="row footer-link-item mb-2">
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control" name="footer_menu_columns[{{ $columnIndex }}][links][{{ $linkIndex }}][title]" 
                                                                           value="{{ $link['title'] ?? '' }}" placeholder="Link Title">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control" name="footer_menu_columns[{{ $columnIndex }}][links][{{ $linkIndex }}][url]" 
                                                                           value="{{ $link['url'] ?? '' }}" placeholder="Link URL">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button" class="btn btn-danger btn-sm remove-footer-link">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <button type="button" class="btn btn-success btn-sm add-footer-link" data-column="{{ $columnIndex }}">
                                                    <i class="fas fa-plus"></i> Add Link
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <button type="button" id="add-footer-column" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> Add Footer Column
                            </button>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    Create custom footer columns with links. Each column can have multiple links.
                                </small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save All Settings
                            </button>
                            <a href="/dealmaker-demo" target="_blank" class="btn btn-success btn-lg ms-3">
                                <i class="fas fa-external-link-alt me-2"></i>Preview Changes
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for dynamic content management -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let logoIndex = {{ $setting && $setting->client_logos ? count($setting->client_logos ?? []) : 0 }};
    let slideIndex = {{ $setting && $setting->slider_images ? count($setting->slider_images ?? []) : 0 }};
    let caseStudyIndex = {{ $setting && $setting->case_studies ? count($setting->case_studies ?? []) : 0 }};
    let serviceTabIndex = {{ $setting && $setting->difference_tabs ? count($setting->difference_tabs ?? []) : 0 }};

    // Add new logo
    document.getElementById('add-logo').addEventListener('click', function() {
        const container = document.getElementById('client-logos-container');
        const logoHtml = `
            <div class="logo-item" data-index="${logoIndex}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="client_logos[${logoIndex}][name]" 
                               placeholder="Company Name">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="client_logos[${logoIndex}][image]" 
                               placeholder="Logo Image URL">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="client_logos[${logoIndex}][url]" 
                               placeholder="Company Website URL (optional)">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-logo">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', logoHtml);
        logoIndex++;
    });

    // Add new slide
    document.getElementById('add-slide').addEventListener('click', function() {
        const container = document.getElementById('slider-images-container');
        const slideHtml = `
            <div class="logo-item" data-index="${slideIndex}">
                <h6>Slide ${slideIndex + 1}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Phone Image URL</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][image]" 
                               placeholder="Phone mockup image URL">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slide Title</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][title]" 
                               placeholder="Slide title">
                    </div>
                    <div class="col-md-8 mt-2">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="slider_images[${slideIndex}][description]" rows="2" 
                                  placeholder="Slide description"></textarea>
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">CTA Text</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][cta_text]" 
                               value="Start Now" placeholder="Button text">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">CTA URL</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][cta_url]" 
                               value="/connect" placeholder="Button link">
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-slide">
                            <i class="fas fa-trash"></i> Remove Slide
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', slideHtml);
        slideIndex++;
    });

    // Add new case study
    document.getElementById('add-case-study').addEventListener('click', function() {
        const container = document.getElementById('case-studies-container');
        const caseStudyHtml = `
            <div class="logo-item" data-index="${caseStudyIndex}">
                <h6>Case Study ${caseStudyIndex + 1}</h6>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="case_studies[${caseStudyIndex}][name]" 
                               placeholder="Company Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Company Logo URL</label>
                        <input type="text" class="form-control" name="case_studies[${caseStudyIndex}][logo]" 
                               placeholder="Logo Image URL">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Featured Image URL</label>
                        <input type="text" class="form-control" name="case_studies[${caseStudyIndex}][image]" 
                               placeholder="Featured Image URL">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Learn More URL</label>
                        <input type="text" class="form-control" name="case_studies[${caseStudyIndex}][learn_more_url]" 
                               placeholder="Case study URL">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="case_studies[${caseStudyIndex}][description]" rows="2" 
                                  placeholder="Company description"></textarea>
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">Capital Raised (M)</label>
                        <input type="number" class="form-control" name="case_studies[${caseStudyIndex}][capital_raised]" 
                               placeholder="31" step="0.1">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">Investors (K)</label>
                        <input type="number" class="form-control" name="case_studies[${caseStudyIndex}][investors]" 
                               placeholder="13" step="0.1">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm remove-case-study">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', caseStudyHtml);
        caseStudyIndex++;
    });

    // Add new service tab
    document.getElementById('add-service-tab').addEventListener('click', function() {
        const container = document.getElementById('service-tabs-container');
        const serviceTabHtml = `
            <div class="logo-item" data-index="${serviceTabIndex}">
                <h6>Service Tab ${serviceTabIndex + 1}</h6>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Tab Title</label>
                        <input type="text" class="form-control" name="difference_tabs[${serviceTabIndex}][title]" 
                               placeholder="Service Title">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tab Icon/Image URL</label>
                        <input type="text" class="form-control" name="difference_tabs[${serviceTabIndex}][icon]" 
                               placeholder="Icon/Image URL">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Learn More URL</label>
                        <input type="text" class="form-control" name="difference_tabs[${serviceTabIndex}][url]" 
                               placeholder="/connect">
                    </div>
                    <div class="col-md-10 mt-2">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="difference_tabs[${serviceTabIndex}][description]" rows="3" 
                                  placeholder="Service description"></textarea>
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm remove-service-tab mt-4">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', serviceTabHtml);
        serviceTabIndex++;
    });

    // Add new testimonial
    let testimonialIndex = {{ $setting && $setting->testimonials ? count($setting->testimonials ?? []) : 0 }};
    document.getElementById('add-testimonial').addEventListener('click', function() {
        const container = document.getElementById('testimonials-container');
        const testimonialHtml = `
            <div class="logo-item testimonial-item" data-index="${testimonialIndex}">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Testimonial Quote</label>
                        <textarea class="form-control" name="testimonials[${testimonialIndex}][quote]" rows="3" 
                                  placeholder="Enter testimonial quote"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Client Name</label>
                        <input type="text" class="form-control" name="testimonials[${testimonialIndex}][name]" 
                               placeholder="Client Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" name="testimonials[${testimonialIndex}][company]" 
                               placeholder="Company Name">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Client Image</label>
                        <input type="file" class="form-control" name="testimonials[${testimonialIndex}][image]" 
                               accept="image/*">
                    </div>
                    <div class="col-md-6 mt-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-testimonial">
                            <i class="fas fa-trash"></i> Remove Testimonial
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', testimonialHtml);
        testimonialIndex++;
    });

    // Footer Columns Management
    let footerColumnIndex = {{ $setting && $setting->footer_menu_columns ? count($setting->footer_menu_columns ?? []) : 0 }};
    let footerLinkIndexes = {};

    // Initialize link indexes for existing columns
    @if($setting && $setting->footer_menu_columns)
        @foreach($setting->footer_menu_columns as $columnIndex => $column)
            footerLinkIndexes[{{ $columnIndex }}] = {{ isset($column['links']) && is_array($column['links']) ? count($column['links']) : 0 }};
        @endforeach
    @endif

    // Add new footer column
    document.getElementById('add-footer-column').addEventListener('click', function() {
        const container = document.getElementById('footer-columns-container');
        footerLinkIndexes[footerColumnIndex] = 0;
        const columnHtml = `
            <div class="logo-item footer-column-item">
                <h6>Column ${footerColumnIndex + 1}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Column Title</label>
                        <input type="text" class="form-control" name="footer_menu_columns[${footerColumnIndex}][title]" 
                               placeholder="RAISE CAPITAL">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-footer-column">
                            <i class="fas fa-trash"></i> Remove Column
                        </button>
                    </div>
                </div>
                
                <div class="mt-3">
                    <label class="form-label">Column Links</label>
                    <div class="footer-links-container" data-column="${footerColumnIndex}">
                    </div>
                    <button type="button" class="btn btn-success btn-sm add-footer-link" data-column="${footerColumnIndex}">
                        <i class="fas fa-plus"></i> Add Link
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', columnHtml);
        footerColumnIndex++;
    });

    // Add footer link to column
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-footer-link')) {
            const columnIndex = e.target.getAttribute('data-column');
            const container = e.target.previousElementSibling;
            const linkIndex = footerLinkIndexes[columnIndex] || 0;
            
            const linkHtml = `
                <div class="row footer-link-item mb-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="footer_menu_columns[${columnIndex}][links][${linkIndex}][title]" 
                               placeholder="Link Title">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="footer_menu_columns[${columnIndex}][links][${linkIndex}][url]" 
                               placeholder="Link URL">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-footer-link">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', linkHtml);
            footerLinkIndexes[columnIndex] = linkIndex + 1;
        }
    });

    // Remove handlers
    document.addEventListener('click', function(e) {
        // Remove logo
        if (e.target.classList.contains('remove-logo') || e.target.parentElement.classList.contains('remove-logo')) {
            const logoItem = e.target.closest('.logo-item');
            if (logoItem) logoItem.remove();
        }
        
        // Remove slide
        if (e.target.classList.contains('remove-slide') || e.target.parentElement.classList.contains('remove-slide')) {
            const slideItem = e.target.closest('.logo-item');
            if (slideItem) slideItem.remove();
        }
        
        // Remove case study
        if (e.target.classList.contains('remove-case-study') || e.target.parentElement.classList.contains('remove-case-study')) {
            const caseStudyItem = e.target.closest('.logo-item');
            if (caseStudyItem) caseStudyItem.remove();
        }
        
        // Remove service tab
        if (e.target.classList.contains('remove-service-tab') || e.target.parentElement.classList.contains('remove-service-tab')) {
            const serviceTabItem = e.target.closest('.logo-item');
            if (serviceTabItem) serviceTabItem.remove();
        }
        
        // Remove testimonial
        if (e.target.classList.contains('remove-testimonial') || e.target.parentElement.classList.contains('remove-testimonial')) {
            const testimonialItem = e.target.closest('.testimonial-item');
            if (testimonialItem) testimonialItem.remove();
        }
        
        // Remove footer column
        if (e.target.classList.contains('remove-footer-column') || e.target.parentElement.classList.contains('remove-footer-column')) {
            const columnItem = e.target.closest('.footer-column-item');
            if (columnItem) columnItem.remove();
        }
        
        // Remove footer link
        if (e.target.classList.contains('remove-footer-link') || e.target.parentElement.classList.contains('remove-footer-link')) {
            const linkItem = e.target.closest('.footer-link-item');
            if (linkItem) linkItem.remove();
        }
    });
});
</script>
@endsection