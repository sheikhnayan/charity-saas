@extends('admin.main')

@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
    /* Custom font size labels for Quill editor */
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

    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <form action="/admins/store" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data->id ?? null }}">

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Logo
                    </label>
                    <br>

                    <img src="{{ asset('uploads/'.$data->logo) ?? null}}" alt="" width="200px">

                    <br>

                    <input type="file" class="form-control" id="last_name" name="logo">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Banner
                    </label>
                    <br>
                    <img src="{{ asset('uploads/'.$data->banner) ?? null}}" alt="" width="200px">
                    <br>
                    <input type="file" class="form-control" id="last_name" name="banner">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Title
                    </label>

                    <input type="text" class="form-control" id="last_name" name="title" value="{{ $data->title ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Title 2
                    </label>

                    <input type="text" class="form-control" id="last_name" name="title2" value="{{ $data->title2 ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Sub Title
                    </label>

                    <input type="text" class="form-control" id="last_name" name="sub_title" value="{{ $data->sub_title ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Date
                    </label>

                    <input type="date" class="form-control" id="last_name" name="date" value="{{ $data->date ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Goal
                    </label>

                    <input type="number" class="form-control" id="last_name" name="goal" value="{{ $data->goal ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Payout Method
                    </label>

                    <select class="form-select" name="payout_method">
                        <option value="direct_deposits" {{ ($data->payout_method ?? null) == 'direct_deposits' ? 'selected' : '' }}>Direct Deposits</option>
                        <option value="mailed_checks" {{ ($data->payout_method ?? null) == 'mailed_checks' ? 'selected' : '' }}>Mailed Checks</option>
                        <option value="wire_transfers" {{ ($data->payout_method ?? null) == 'wire_transfers' ? 'selected' : '' }}>Wire Transfers</option>
                    </select>
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Location
                    </label>

                    <input type="text" class="form-control" id="last_name" name="location" value="{{ $data->location ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Time
                    </label>

                    <input type="time" class="form-control" id="last_name" name="time" value="{{ $data->time ?? null}}">
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Description
                    </label>

                    <textarea name="description" id="description" cols="30" rows="10" class="form-control">
                        {{ $data->description ?? null}}
                    </textarea>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Participant Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="participant_name" value="{{ $data->participant_name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Team Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="team_name" value="{{ $data->team_name ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Organization Name
                    </label>

                    <input type="text" class="form-control" id="last_name" name="organization" value="{{ $data->organization ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Phone
                    </label>

                    <input type="text" class="form-control" id="last_name" name="phone" value="{{ $data->phone ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Charitable ID
                    </label>

                    <input type="text" class="form-control" id="last_name" name="charitable_id" value="{{ $data->charitable_id ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Address
                    </label>

                    <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        ZIP / Postal Code
                    </label>

                    <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        City
                    </label>

                    <input type="text" class="form-control" id="last_name" name="city" value="{{ $data->city ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Country
                    </label>

                    <input type="text" class="form-control" id="last_name" name="country" value="{{ $data->country ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        State / Province
                    </label>

                    <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Site Status
                    </label>

                    <select class="form-select" name="site_status">
                        <option value="1" {{ ($data->site_status ?? null) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($data->site_status ?? null) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Payment Method
                    </label>

                    <select class="form-select" name="payment_method">
                        <option value="authorize" {{ ($data->payment_method ?? null) == 'authorize' ? 'selected' : '' }}>Authorize.net</option>
                        <option value="stripe" {{ ($data->payment_method ?? null) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Stripe api key
                    </label>

                    <input type="text" class="form-control" id="last_name" name="api_key" value="{{ $data->api_key ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Stripe api secret
                    </label>

                    <input type="text" class="form-control" id="last_name" name="api_secret" value="{{ $data->api_secret ?? null}}">
                </div>

                @php
                    $pages = \App\Models\Page::where('website_id',$data->user->website_id)->where('status',1)->get();
                @endphp

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Privacy Policy Page
                    </label>

                    <select class="form-select" name="privacy">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->privacy == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Terms & Condition Page
                    </label>

                    <select class="form-select" name="terms">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->terms == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Refund Policy
                    </label>

                    <select class="form-select" name="refund">
                        <option value="null" disabled selected>Select Page</option>
                        @foreach ($pages as $item)
                            <option {{ $data->refund == $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                @php
                    $website = \App\Models\Website::where('user_id', $data->user_id)->first();
                @endphp

                @if($website && $website->type === 'investment')
                <!-- Investment-specific settings -->
                <div class="col-12 mt-4">
                    <h5 class="text-primary">Investment Settings</h5>
                    <small class="text-muted">These settings apply to investment websites as fallback values.</small>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="asset_type" class="form-label">
                        Asset Type (Default)
                    </label>
                    <input type="text" class="form-control" name="asset_type" placeholder="e.g., Common Stock, Preferred Stock, SAFE" value="{{ $data->asset_type ?? 'Common Stock' }}">
                    <small class="form-text text-muted">Default asset type displayed when website-specific value is not set.</small>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="offering_type" class="form-label">
                        Offering Type (Default)
                    </label>
                    <input type="text" class="form-control" name="offering_type" placeholder="e.g., Equity, Debt, Hybrid" value="{{ $data->offering_type ?? 'Equity' }}">
                    <small class="form-text text-muted">Default offering type displayed when website-specific value is not set.</small>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="asset_type_label" class="form-label">
                        Asset Type Label (Default)
                    </label>
                    <input type="text" class="form-control" name="asset_type_label" placeholder="e.g., ASSET TYPE, SECURITY TYPE" value="{{ $data->asset_type_label ?? 'ASSET TYPE' }}">
                    <small class="form-text text-muted">Default label text for asset type displayed when website-specific value is not set.</small>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="offering_type_label" class="form-label">
                        Offering Type Label (Default)
                    </label>
                    <input type="text" class="form-control" name="offering_type_label" placeholder="e.g., OFFERING TYPE, INVESTMENT TYPE" value="{{ $data->offering_type_label ?? 'OFFERING TYPE' }}">
                    <small class="form-text text-muted">Default label text for offering type displayed when website-specific value is not set.</small>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="investment_title" class="form-label">
                        Investment Title (Default)
                    </label>
                    <div id="investment_title_editor_settings" style="height: 150px;" data-content="{{ htmlspecialchars($data->investment_title ?? 'Investment Investment Opportunity', ENT_QUOTES, 'UTF-8') }}"></div>
                    <input type="hidden" name="investment_title" id="investment_title_settings" value="{{ htmlspecialchars($data->investment_title ?? 'Investment Investment Opportunity', ENT_QUOTES, 'UTF-8') }}">
                    <small class="form-text text-muted">Default investment title with rich formatting used when website-specific value is not set.</small>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="additional_information" class="form-label">
                        Additional Information (Default)
                    </label>
                    <div id="additional_information_editor_settings" style="height: 200px;" data-content="{{ htmlspecialchars($data->additional_information ?? '', ENT_QUOTES, 'UTF-8') }}"></div>
                    <input type="hidden" name="additional_information" id="additional_information_settings" value="{{ htmlspecialchars($data->additional_information ?? '', ENT_QUOTES, 'UTF-8') }}">
                    <small class="form-text text-muted">Default additional information content with rich formatting displayed in the "Additional Information" section when website-specific value is not set.</small>
                </div>
                @endif

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <script>
        ClassicEditor
          .create(document.querySelector('#description'))
          .catch(error => {
              console.error(error);
          });

        // Initialize Quill editor for investment title in settings
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize if the investment title editor exists
            if (document.getElementById('investment_title_editor_settings')) {
                // Function to decode HTML entities
                function decodeHtml(html) {
                    var txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                }

                // Register custom font sizes using class attributor
                var SizeClass = Quill.import('attributors/class/size');
                SizeClass.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px'];
                Quill.register(SizeClass, true);

                // Initialize Quill editor for investment title
                var investmentTitleSettingsQuill = new Quill('#investment_title_editor_settings', {
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
                var investmentTitleContent = document.getElementById('investment_title_settings').value;
                console.log('Initial investment title content (settings):', investmentTitleContent);
                
                if (investmentTitleContent && investmentTitleContent.trim() !== '') {
                    try {
                        // First try direct assignment, then decoded if needed
                        if (investmentTitleContent.includes('&')) {
                            var decodedTitleContent = decodeHtml(investmentTitleContent);
                            investmentTitleSettingsQuill.root.innerHTML = decodedTitleContent;
                        } else {
                            investmentTitleSettingsQuill.root.innerHTML = investmentTitleContent;
                        }
                        console.log('Loaded investment title content into Quill editor (settings)');
                    } catch (error) {
                        console.error('Error loading investment title content into Quill editor (settings):', error);
                        // Fallback: try setting as plain text
                        investmentTitleSettingsQuill.setText(investmentTitleContent);
                    }
                }

                // Update hidden input when investment title content changes
                investmentTitleSettingsQuill.on('text-change', function() {
                    var titleContent = investmentTitleSettingsQuill.root.innerHTML;
                    document.getElementById('investment_title_settings').value = titleContent;
                    console.log('Investment title content updated (settings):', titleContent);
                });

                // Initialize Quill editor for additional information
                var additionalInformationSettingsQuill = new Quill('#additional_information_editor_settings', {
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
                var additionalInformationContent = document.getElementById('additional_information_settings').value;
                console.log('Initial additional information content (settings):', additionalInformationContent);
                
                if (additionalInformationContent && additionalInformationContent.trim() !== '') {
                    try {
                        // First try direct assignment, then decoded if needed
                        if (additionalInformationContent.includes('&')) {
                            var decodedContent = decodeHtml(additionalInformationContent);
                            additionalInformationSettingsQuill.root.innerHTML = decodedContent;
                        } else {
                            additionalInformationSettingsQuill.root.innerHTML = additionalInformationContent;
                        }
                        console.log('Loaded additional information content into Quill editor (settings)');
                    } catch (error) {
                        console.error('Error loading additional information content into Quill editor (settings):', error);
                        // Fallback: try setting as plain text
                        additionalInformationSettingsQuill.setText(additionalInformationContent);
                    }
                }

                // Update hidden input when additional information content changes
                additionalInformationSettingsQuill.on('text-change', function() {
                    var additionalContent = additionalInformationSettingsQuill.root.innerHTML;
                    document.getElementById('additional_information_settings').value = additionalContent;
                    console.log('Additional information content updated (settings):', additionalContent);
                });

                // Ensure content is saved before form submission
                document.querySelector('form').addEventListener('submit', function(e) {
                    var titleContent = investmentTitleSettingsQuill.root.innerHTML;
                    document.getElementById('investment_title_settings').value = titleContent;
                    console.log('Form submission - saving investment title content (settings):', titleContent);
                    
                    var additionalContent = additionalInformationSettingsQuill.root.innerHTML;
                    document.getElementById('additional_information_settings').value = additionalContent;
                    console.log('Form submission - saving additional information content (settings):', additionalContent);
                });
            }
        });
      </script>

@endsection


