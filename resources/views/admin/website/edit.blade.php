@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

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
                                            Website
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
                                            Website
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                    <form action="{{ route('admin.website.update',[$data->id]) }}" method="post">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="first_name" class="form-label">First Name</label>
                                                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" value="{{ $data->user->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="last_name" class="form-label">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name" value="{{ $data->user->last_name ?? '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="type" class="form-label">Website Type</label>
                                                        <select name="type" class="form-control" id="type" required>
                                                            <option value="">Select Website Type</option>
                                                            <option value="fundraiser" {{ $data->type == 'fundraiser' ? 'selected' : '' }}>Fundraiser</option>
                                                            <option value="investment" {{ $data->type == 'investment' ? 'selected' : '' }}>Investment</option>
                                                        </select>
                                                        <small class="form-text text-muted">Choose whether this website is for fundraising or investment purposes.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ $data->user->email }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                                        <small class="form-text text-muted">Leave blank to keep current password.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Website Name</label>
                                                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" id="name" placeholder="Website Name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Domain</label>
                                                        <input type="text" name="domain" value="{{ $data->domain }}" class="form-control" id="name" placeholder="Website Name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="google_analytics_id" class="form-label">
                                                            <i class="bx bxl-google me-1"></i>Google Analytics Tracking ID
                                                        </label>
                                                        <input type="text" name="google_analytics_id" value="{{ $data->google_analytics_id ?? '' }}" class="form-control" id="google_analytics_id" placeholder="e.g., G-XXXXXXXXXX">
                                                        <small class="form-text text-muted">Enter your Google Analytics tracking ID (e.g., G-XXXXXXXXXX). Leave blank to disable analytics for this website.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="contact_emails" class="form-label">
                                                            <i class="bx bx-envelope me-1"></i>Contact Form Notification Emails
                                                        </label>
                                                        <div id="contact_emails_container">
                                                            @php
                                                                $emails = $data->contact_emails ?? [];
                                                                $emailsList = is_array($emails) ? $emails : (is_string($emails) ? json_decode($emails, true) ?? [] : []);
                                                            @endphp
                                                            @foreach($emailsList as $index => $emailItem)
                                                                @php
                                                                    // Handle both old format (string) and new format (array with email + preferences)
                                                                    $email = is_array($emailItem) ? $emailItem['email'] : $emailItem;
                                                                    $receiveContact = is_array($emailItem) ? ($emailItem['receive_contact_form'] ?? true) : true;
                                                                    $receiveTransaction = is_array($emailItem) ? ($emailItem['receive_transaction_emails'] ?? true) : true;
                                                                @endphp
                                                                <div class="email-item mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                                                    <div class="input-group mb-2">
                                                                        <input type="email" name="contact_emails[{{ $index }}][email]" class="form-control" value="{{ $email }}" placeholder="Enter email address" required>
                                                                        <button type="button" class="btn btn-outline-danger btn-remove-email" onclick="removeEmailField(this)">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="ms-2">
                                                                        <div class="form-check form-check-sm">
                                                                            <input type="checkbox" class="form-check-input" 
                                                                                   id="receive_contact_{{ $index }}" 
                                                                                   name="contact_emails[{{ $index }}][receive_contact_form]" 
                                                                                   value="1" 
                                                                                   {{ $receiveContact ? 'checked' : '' }}>
                                                                            <label class="form-check-label small" for="receive_contact_{{ $index }}">
                                                                                <i class="bx bx-envelope-open me-1"></i>Receive Contact Form Submissions
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check form-check-sm">
                                                                            <input type="checkbox" class="form-check-input" 
                                                                                   id="receive_transaction_{{ $index }}" 
                                                                                   name="contact_emails[{{ $index }}][receive_transaction_emails]" 
                                                                                   value="1" 
                                                                                   {{ $receiveTransaction ? 'checked' : '' }}>
                                                                            <label class="form-check-label small" for="receive_transaction_{{ $index }}">
                                                                                <i class="bx bx-receipt me-1"></i>Receive Transaction & Payment Confirmations
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @if(empty($emailsList))
                                                                <div class="email-item mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                                                    <div class="input-group mb-2">
                                                                        <input type="email" name="contact_emails[0][email]" class="form-control" placeholder="Enter email address" required>
                                                                        <button type="button" class="btn btn-outline-danger btn-remove-email" onclick="removeEmailField(this)">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="ms-2">
                                                                        <div class="form-check form-check-sm">
                                                                            <input type="checkbox" class="form-check-input" 
                                                                                   id="receive_contact_0" 
                                                                                   name="contact_emails[0][receive_contact_form]" 
                                                                                   value="1" 
                                                                                   checked>
                                                                            <label class="form-check-label small" for="receive_contact_0">
                                                                                <i class="bx bx-envelope-open me-1"></i>Receive Contact Form Submissions
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check form-check-sm">
                                                                            <input type="checkbox" class="form-check-input" 
                                                                                   id="receive_transaction_0" 
                                                                                   name="contact_emails[0][receive_transaction_emails]" 
                                                                                   value="1" 
                                                                                   checked>
                                                                            <label class="form-check-label small" for="receive_transaction_0">
                                                                                <i class="bx bx-receipt me-1"></i>Receive Transaction & Payment Confirmations
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addEmailField()">
                                                            <i class="bx bx-plus me-1"></i>Add Another Email
                                                        </button>
                                                        <small class="form-text text-muted d-block mt-2">Contact form submissions and transaction emails will be sent to all configured emails based on their individual preferences. Each email can receive contact form submissions, transaction confirmations, both, or neither.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option {{ $data->status == 0 ? 'selected' : '' }} value="0">Deactive</option>
                                                            <option {{ $data->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Investment-specific fields -->
                                            <div class="row" id="investment-fields" style="display: {{ $data->type == 'investment' ? 'block' : 'none' }};">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="share_price" class="form-label">Share Price ($)</label>
                                                        <input type="number" name="share_price" class="form-control" id="share_price" placeholder="2.13" step="0.01" min="0.01" value="{{ $data->share_price ?? '' }}">
                                                        <small class="form-text text-muted">Price per share for investment calculations.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="min_investment" class="form-label">Minimum Investment ($)</label>
                                                        <input type="number" name="min_investment" class="form-control" id="min_investment" placeholder="1000" step="1" min="1" value="{{ $data->min_investment ?? '' }}">
                                                        <small class="form-text text-muted">Minimum amount required to invest.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="asset_type" class="form-label">Asset Type</label>
                                                        <input type="text" name="asset_type" class="form-control" id="asset_type" placeholder="e.g., Common Stock, Preferred Stock, SAFE" value="{{ $data->asset_type ?? 'Common Stock' }}">
                                                        <small class="form-text text-muted">Type of security or asset being offered to investors.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="offering_type" class="form-label">Offering Type</label>
                                                        <input type="text" name="offering_type" class="form-control" id="offering_type" placeholder="e.g., Equity, Debt, Hybrid" value="{{ $data->offering_type ?? 'Equity' }}">
                                                        <small class="form-text text-muted">Category of investment offering structure.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="asset_type_label" class="form-label">Asset Type Label</label>
                                                        <input type="text" name="asset_type_label" class="form-control" id="asset_type_label" placeholder="e.g., ASSET TYPE, SECURITY TYPE" value="{{ $data->asset_type_label ?? 'ASSET TYPE' }}">
                                                        <small class="form-text text-muted">The label text displayed above the asset type value on the investment page.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="offering_type_label" class="form-label">Offering Type Label</label>
                                                        <input type="text" name="offering_type_label" class="form-control" id="offering_type_label" placeholder="e.g., OFFERING TYPE, INVESTMENT TYPE" value="{{ $data->offering_type_label ?? 'OFFERING TYPE' }}">
                                                        <small class="form-text text-muted">The label text displayed above the offering type value on the investment page.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="investment_tiers" class="form-label">Investment Tiers</label>
                                                        <input type="text" name="investment_tiers" class="form-control" id="investment_tiers" placeholder="1000,2500,5000,10000" value="{{ $data->investment_tiers ?? '' }}">
                                                        <small class="form-text text-muted">Comma-separated list of investment amounts to display as quick options.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="investment_title" class="form-label">Investment Title</label>
                                                        <div id="investment_title_editor" style="height: 150px;" data-content="{{ htmlspecialchars($data->investment_title ?? 'Investment Opportunity', ENT_QUOTES, 'UTF-8') }}"></div>
                                                        <input type="hidden" name="investment_title" id="investment_title" value="{{ htmlspecialchars($data->investment_title ?? 'Investment Opportunity', ENT_QUOTES, 'UTF-8') }}">
                                                        <small class="form-text text-muted">Custom title for the investment opportunity with rich text formatting (color, font size, etc.).</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="additional_information" class="form-label">Additional Information</label>
                                                        <div id="additional_information_editor" style="height: 200px;" data-content="{{ htmlspecialchars($data->additional_information ?? '', ENT_QUOTES, 'UTF-8') }}"></div>
                                                        <input type="hidden" name="additional_information" id="additional_information" value="{{ htmlspecialchars($data->additional_information ?? '', ENT_QUOTES, 'UTF-8') }}">
                                                        <small class="form-text text-muted">Additional information about the investment that will be displayed in the "Additional Information" section on the invest page (separate from footer disclaimer).</small>
                                                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="debugAdditionalInformation()">Debug Content</button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Investment Page Text Settings -->
                                                <div class="col-md-12">
                                                    <h5 class="mt-4 mb-3">Investment Page Text Labels</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="invest_page_title" class="form-label">Investment Page Title</label>
                                                        <input type="text" name="invest_page_title" class="form-control" id="invest_page_title" placeholder="Complete Your Investment" value="{{ $data->invest_page_title ?? 'Complete Your Investment' }}">
                                                        <small class="form-text text-muted">The main title displayed on the investment form page.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="invest_amount_title" class="form-label">Investment Amount Section Title</label>
                                                        <input type="text" name="invest_amount_title" class="form-control" id="invest_amount_title" placeholder="Select Investment Amount" value="{{ $data->invest_amount_title ?? 'Select Investment Amount' }}">
                                                        <small class="form-text text-muted">The title for the investment amount selection section.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="share_price_label" class="form-label">Share Price Label</label>
                                                        <input type="text" name="share_price_label" class="form-control" id="share_price_label" placeholder="SHARE PRICE" value="{{ $data->share_price_label ?? 'SHARE PRICE' }}">
                                                        <small class="form-text text-muted">The label displayed above the share price value.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="minimum_investment_label" class="form-label">Minimum Investment Label</label>
                                                        <input type="text" name="minimum_investment_label" class="form-control" id="minimum_investment_label" placeholder="MINIMUM INVESTMENT" value="{{ $data->minimum_investment_label ?? 'MINIMUM INVESTMENT' }}">
                                                        <small class="form-text text-muted">The label displayed above the minimum investment value.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="custom_sticky_button_text" class="form-label">Custom Sticky Button Verbiage</label>
                                                        <input type="text" name="custom_sticky_button_text" class="form-control" value="{{ $data->custom_sticky_button_text }}" id="custom_sticky_button_text" placeholder="Custom verbiage for sticky invest now button" value="Custom verbiage for sticky invest now button">
                                                        <small class="form-text text-muted">Custom Verbiage for Sticky Invest Now Button.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Sticky Footer Color Settings -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="mt-4 mb-3">Sticky Footer Button Colors</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_button_bg" class="form-label">Button Background Color</label>
                                                        <input type="color" name="sticky_footer_button_bg" class="form-control" id="sticky_footer_button_bg" value="{{ $data->sticky_footer_button_bg ?? '#007bff' }}">
                                                        <small class="form-text text-muted">Background color for the sticky Invest Now button.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_button_text" class="form-label">Button Text Color</label>
                                                        <input type="color" name="sticky_footer_button_text" class="form-control" id="sticky_footer_button_text" value="{{ $data->sticky_footer_button_text ?? '#ffffff' }}">
                                                        <small class="form-text text-muted">Text color for the sticky Invest Now button.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_text_color" class="form-label">Footer Text Color</label>
                                                        <input type="color" name="sticky_footer_text_color" class="form-control" id="sticky_footer_text_color" value="{{ $data->sticky_footer_text_color ?? '#333333' }}">
                                                        <small class="form-text text-muted">Color for text outside the button in the sticky footer.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_bg_color" class="form-label">Footer Background Color</label>
                                                        <input type="color" name="sticky_footer_bg_color" class="form-control" id="sticky_footer_bg_color" value="{{ $data->sticky_footer_bg_color ?? '#f8f9fa' }}">
                                                        <small class="form-text text-muted">Background color for the entire sticky footer section.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="preset_amounts" class="form-label">Preset Amounts</label>
                                                        <input type="text" name="preset_amounts" value="{{ $data->preset_amounts ?? '' }}" class="form-control" id="preset_amounts" placeholder="e.g. 100,500,1000">
                                                        <small class="form-text text-muted">Enter preset amounts separated by commas. These will be available for quick selection in auction/investment components.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="shares" class="form-label">Shares</label>
                                                        <input type="number" name="shares" value="{{ $data->shares ?? '' }}" class="form-control" id="shares" placeholder="Number of shares">
                                                        <small class="form-text text-muted">Specify the number of shares available for investment/auction.</small>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <!-- Property Details Colors -->
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <h5 class="mt-3 mb-3">Property Details Colors</h5>
                                                    <small class="text-muted d-block mb-2">Customize the background and all text colors used on the property details page.</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="property_details_bg_color" class="form-label">Page Background</label>
                                                        <input type="color" name="property_details_bg_color" id="property_details_bg_color" class="form-control" value="{{ $data->property_details_bg_color ?? '#ffffff' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="property_details_text_color" class="form-label">Primary Text Color</label>
                                                        <input type="color" name="property_details_text_color" id="property_details_text_color" class="form-control" value="{{ $data->property_details_text_color ?? '#111827' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="property_details_muted_color" class="form-label">Muted/Secondary Text</label>
                                                        <input type="color" name="property_details_muted_color" id="property_details_muted_color" class="form-control" value="{{ $data->property_details_muted_color ?? '#6b7280' }}">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="property_details_heading_color" class="form-label">Headings Color</label>
                                                        <input type="color" name="property_details_heading_color" id="property_details_heading_color" class="form-control" value="{{ $data->property_details_heading_color ?? '#1e293b' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="property_details_price_color" class="form-label">Price/Emphasis Color</label>
                                                        <input type="color" name="property_details_price_color" id="property_details_price_color" class="form-control" value="{{ $data->property_details_price_color ?? '#111827' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="property_details_accent_color" class="form-label">Links/Accent Color</label>
                                                        <input type="color" name="property_details_accent_color" id="property_details_accent_color" class="form-control" value="{{ $data->property_details_accent_color ?? '#667eea' }}">
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.website.index') }}" class="btn btn-danger">Cancel</a>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Quill CSS Styles -->
            <style>
            /* Custom Quill styles for better font size support */
            .ql-snow .ql-picker.ql-size .ql-picker-label::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item::before {
              content: '14px';
            }
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
            </style>

            <!-- Quill Editor Initialization for Investment Disclaimer -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Function to decode HTML entities
                    function decodeHtml(html) {
                        var txt = document.createElement("textarea");
                        txt.innerHTML = html;
                        return txt.value;
                    }

                // Register custom font sizes using class attributor like page-builder
                var SizeClass = Quill.import('attributors/class/size');
                SizeClass.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px'];
                Quill.register(SizeClass, true);

                // Note: investment_disclaimer editor removed - now managed in Footer Settings

                // Initialize Quill editor for additional information
                var additionalInformationQuill = new Quill('#additional_information_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'size': SizeClass.whitelist }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'align': [] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            ['blockquote', 'code-block'],
                            ['link'],
                            ['clean']
                        ]
                    }
                });

                // Set initial content for additional information
                var additionalInformationContent = document.getElementById('additional_information').value;
                console.log('Initial additional information content:', additionalInformationContent);
                console.log('Raw data attribute:', document.getElementById('additional_information_editor').dataset.content);
                
                if (additionalInformationContent && additionalInformationContent.trim() !== '') {
                    try {
                        // First try direct assignment, then decoded if needed
                        if (additionalInformationContent.includes('&')) {
                            var decodedContent = decodeHtml(additionalInformationContent);
                            additionalInformationQuill.root.innerHTML = decodedContent;
                        } else {
                            additionalInformationQuill.root.innerHTML = additionalInformationContent;
                        }
                        console.log('Loaded additional information content into Quill editor');
                    } catch (error) {
                        console.error('Error loading additional information content into Quill editor:', error);
                        // Fallback: try setting as plain text
                        additionalInformationQuill.setText(additionalInformationContent);
                    }
                }

                // Update hidden input when content changes
                additionalInformationQuill.on('text-change', function() {
                    var content = additionalInformationQuill.root.innerHTML;
                    document.getElementById('additional_information').value = content;
                    console.log('Additional information content updated:', content);
                });

                // Debug function
                // Debug function for additional information
                window.debugAdditionalInformation = function() {
                    console.log('=== ADDITIONAL INFORMATION DEBUG ===');
                    console.log('Hidden input value:', document.getElementById('additional_information').value);
                    console.log('Quill content HTML:', additionalInformationQuill.root.innerHTML);
                    console.log('Quill content text:', additionalInformationQuill.getText());
                    console.log('Data attribute:', document.getElementById('additional_information_editor').dataset.content);
                    alert('Check browser console for debug information');
                };

                // Initialize Quill editor for investment title
                var investmentTitleQuill = new Quill('#investment_title_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'size': SizeClass.whitelist }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'align': [] }],
                            ['clean']
                        ]
                    }
                });

                // Set initial content for investment title
                var investmentTitleContent = document.getElementById('investment_title').value;
                console.log('Initial investment title content:', investmentTitleContent);
                
                if (investmentTitleContent && investmentTitleContent.trim() !== '') {
                    try {
                        // First try direct assignment, then decoded if needed
                        if (investmentTitleContent.includes('&')) {
                            var decodedTitleContent = decodeHtml(investmentTitleContent);
                            investmentTitleQuill.root.innerHTML = decodedTitleContent;
                        } else {
                            investmentTitleQuill.root.innerHTML = investmentTitleContent;
                        }
                        console.log('Loaded investment title content into Quill editor');
                    } catch (error) {
                        console.error('Error loading investment title content into Quill editor:', error);
                        // Fallback: try setting as plain text
                        investmentTitleQuill.setText(investmentTitleContent);
                    }
                }

                // Update hidden input when investment title content changes
                investmentTitleQuill.on('text-change', function() {
                    var titleContent = investmentTitleQuill.root.innerHTML;
                    document.getElementById('investment_title').value = titleContent;
                    console.log('Investment title content updated:', titleContent);
                });

                // Ensure all Quill content is saved before form submission
                document.querySelector('form').addEventListener('submit', function(e) {
                    // Save additional information content
                    var additionalContent = additionalInformationQuill.root.innerHTML;
                    document.getElementById('additional_information').value = additionalContent;
                    console.log('Form submission - saving additional information content:', additionalContent);
                    
                    // Save investment title content
                    var titleContent = investmentTitleQuill.root.innerHTML;
                    document.getElementById('investment_title').value = titleContent;
                    console.log('Form submission - saving investment title content:', titleContent);
                });

            });

            // Handle website type change to show/hide investment fields
            function toggleInvestmentFields() {
                const websiteType = document.getElementById('type').value;
                const investmentFields = document.getElementById('investment-fields');
                
                if (websiteType === 'investment') {
                    investmentFields.style.display = 'block';
                } else {
                    investmentFields.style.display = 'none';
                }
            }

            // Email management functions
            function addEmailField() {
                const container = document.getElementById('contact_emails_container');
                const existingItems = container.querySelectorAll('.email-item');
                const nextIndex = existingItems.length;
                
                const newEmailItem = document.createElement('div');
                newEmailItem.className = 'email-item mb-3 p-3 border rounded';
                newEmailItem.style.backgroundColor = '#f8f9fa';
                newEmailItem.innerHTML = `
                    <div class="input-group mb-2">
                        <input type="email" name="contact_emails[${nextIndex}][email]" class="form-control" placeholder="Enter email address" required>
                        <button type="button" class="btn btn-outline-danger btn-remove-email" onclick="removeEmailField(this)">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <div class="form-check form-check-sm">
                            <input type="checkbox" class="form-check-input" 
                                   id="receive_contact_${nextIndex}" 
                                   name="contact_emails[${nextIndex}][receive_contact_form]" 
                                   value="1" 
                                   checked>
                            <label class="form-check-label small" for="receive_contact_${nextIndex}">
                                <i class="bx bx-envelope-open me-1"></i>Receive Contact Form Submissions
                            </label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input type="checkbox" class="form-check-input" 
                                   id="receive_transaction_${nextIndex}" 
                                   name="contact_emails[${nextIndex}][receive_transaction_emails]" 
                                   value="1" 
                                   checked>
                            <label class="form-check-label small" for="receive_transaction_${nextIndex}">
                                <i class="bx bx-receipt me-1"></i>Receive Transaction & Payment Confirmations
                            </label>
                        </div>
                    </div>
                `;
                container.appendChild(newEmailItem);
            }

            function removeEmailField(button) {
                const container = document.getElementById('contact_emails_container');
                const items = container.querySelectorAll('.email-item');
                
                // Only allow removal if there's more than one email field
                if (items.length > 1) {
                    button.closest('.email-item').remove();
                } else {
                    alert('You must keep at least one contact email.');
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                toggleInvestmentFields();
                
                // Add event listener to type dropdown
                document.getElementById('type').addEventListener('change', toggleInvestmentFields);
            });
            </script>
@endsection
