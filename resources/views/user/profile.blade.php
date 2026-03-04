@extends('user.main')

@section('content')
    @php
        // Get current website from domain
        $currentDomain = request()->getHost();
        $currentWebsite = \App\Models\Website::where('domain', $currentDomain)->first();
        
        // Fallback to user's website if not found
        if (!$currentWebsite) {
            $currentWebsite = Auth::user()->website;
        }
    @endphp
    
    <link rel="stylesheet" href="{{ asset('user/extra.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <!-- Intro.js for Tutorial -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        .forms-wizard li.done em::before,
        .lnr-checkmark-circle::before {
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
    </style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="app-main__inner">
                        <div class="app-site-information">
                            <div class="main-card card">
                                <div class="card-body">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">

                                                <div class="widget-content-left me-3 d-none d-md-block">
                                                    <div class="widget-content-left">
                                                        <img width="42" class="rounded" alt="{{ $currentWebsite->name }}"
                                                            src="{{ asset('uploads/' . $currentWebsite->setting->logo) }}">
                                                    </div>
                                                </div>

                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        {{ $currentWebsite->name }}
                                                    </div>
                                                    {{-- <div class="widget-subheading">
                                                        Peer to Peer
                                                        (Premium)
                                                    </div> --}}
                                                    @if(Auth::user()->role != 'parents')
                                                    <div class="fs-6 mt-2">
                                                        <i class="fas fa-link link-info me-1 btn-clipboard" role="button"
                                                            data-clipboard-text="http://{{ $currentWebsite->domain }}/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}"></i>
                                                        <a href="http://{{ $currentWebsite->domain }}/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}"
                                                            class="link-info"
                                                            target="_blank">{{ $currentWebsite->domain }}/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}</a>
                                                    </div>
                                                    @endif
                                                </div>

                                                <div class="widget-content-right">
                                                    @if(Auth::user()->role == 'individual' || Auth::user()->role == 'parents')
                                                        <div class="btn-group d-none d-md-inline-flex" role="group">
                                                            @if(Auth::user()->role != 'parents')
                                                            <a href="/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}"
                                                                class="btn btn-info btn-hover-info" target="_blank">
                                                                <i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i>
                                                                <span>View</span>
                                                            </a>

                                                            <button type="button" class="btn btn-success btn-hover-info"
                                                                data-bs-toggle="modal" data-bs-target="#shareModal">
                                                                <i class="fa-solid fa-share-nodes fa-fw" aria-hidden="true"></i>
                                                                <span>Share</span>
                                                            </button>
                                                            
                                                            <button type="button" class="btn btn-warning btn-hover-info" id="generateQRBtn" onclick="generateProfileQR()">
                                                                <i class="fa-solid fa-qrcode fa-fw" aria-hidden="true"></i>
                                                                <span>QR Code</span>
                                                            </button>
                                                            
                                                            <button type="button" class="btn btn-primary btn-hover-info" onclick="copyProfileUrl()">
                                                                <i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i>
                                                                <span>Copy URL</span>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    {{-- @else
                                                        <button type="button" class="btn btn-primary btn-hover-info d-none d-md-inline-flex" onclick="copyProfileUrl()">
                                                            <i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i>
                                                            <span>Copy Profile URL</span>
                                                        </button> --}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">

                                    <div class="page-title-icon">
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            profile
                                        </span>
                                        <div class="page-title-subheading">
                                            Manage your profile information.
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                    @if(Auth::user()->role == 'parents')
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                            <i class="fas fa-plus me-2"></i>Add Participants
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="startParentTutorial()" id="tutorialBtn">
                                            <i class="fas fa-graduation-cap me-2"></i>View Tutorial
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/users">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Information
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            profile
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <ul class="forms-wizard profile-progress-steps">
                            <li class="done">
                                <span>
                                    <em>1</em>
                                    <span>Profile</span>
                                </span>
                            </li>
                            <li class="done">
                                <span>
                                    <em>2</em>
                                    <span>Approved</span>
                                </span>
                            </li>
                        </ul>




                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary">

                                    {{-- <a class="btn-icon btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                        href="https://gmu-events.com/dash/profile?create=profile" role="button"
                                        style="z-index: 7; width: 150px">
                                        <i class="fa-solid fa-plus btn-icon-wrapper"></i>
                                        Create new profile
                                    </a> --}}

                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-primary">
                                            {{-- <div class="menu-header-content">
                                                <div class="avatar-icon-wrapper mb-3 avatar-icon-xl">
                                                    <div class="avatar-icon">
                                                        <div class="rounded-profile-picture fill" role="img"
                                                            aria-label="{{ Auth::user()->name }} {{ Auth::user()->last_name }}"
                                                            style="background-image: url({{ asset(Auth::user()->photo) }})">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div>
                                                <h5 class="menu-header-title">
                                                    <a href="{{ Auth::user()->website->domain }}/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}"
                                                        class="link-light">
                                                        {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                                                    </a>
                                                </h5>
                                                <h6 class="menu-header-subtitle text-capitalize">
                                                    Parent/Guardian
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-center-fixed-width main-card mb-4 card">
                        <div class="card-body">
                            <form action="/users/profile" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-3">
                                    <input type="hidden" name="isNew" value="1">

                                    <input type="hidden" name="site_uri" value="{{ Auth::user()->website->domain }}/profile/">


                                    <input type="hidden" name="participant_type" value="individual">
                                    <div class="col-12 tab-content fundraiser-tab-content">

                                        @if (Auth::user()->role != 'customer')
                                            @if (Auth::user()->role != 'parents' && Auth::user()->role != 'parent' && Auth::user()->role != 'Parents')
                                                <div class="row gy-3 tab-pane profile-tab-individual show active" role="tabpanel">
                                                    <div class="col-12">
                                                        <label for="individual_goal" class="form-label">Your goal</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control" id="individual_goal"
                                                                name="goal" value="{{ Auth::user()->goal }}">
                                                            <span class="input-group-text">.00 USD</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="individual_url" class="form-label">
                                                            Your URL
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                {{ Auth::user()->website->domain }}/profile/
                                                            </span>
                                                            <input type="text" class="form-control" id="individual_url"
                                                                name="individual_url"
                                                                value="{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                        @endif


                                    <div class="col-12" style="order: -2;">
                                        <label for="first_name" class="form-label required">
                                            Full Name
                                        </label>


                                        <input type="text" class="form-control" id="first_name" name="name"
                                            value="{{ Auth::user()->name }}">
                                    </div>


                                    <div class="col-12" style="order: -2;">
                                        <label for="first_name" class="form-label required">
                                            Email
                                        </label>


                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ Auth::user()->email }}" readonly>
                                    </div>











                                    {{-- <div class="col-6" style="order: -1;">
                                        <label for="last_name" class="form-label required">
                                            Last name
                                        </label>


                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="{{ Auth::user()->last_name }}">
                                    </div> --}}


                                    @if (Auth::user()->role != 'customer')
                                        @if (Auth::user()->role != 'parents' && Auth::user()->role != 'parent' && Auth::user()->role != 'Parents')
                                        <div class="col-12">
                                            <label for="description" class="form-label ">
                                                Enter the text that will appear on your personal fundraising page.
                                            </label>


                                            <textarea class="form-control text-editor" id="description" name="description"
                                                rows="3" style="visibility: hidden;">
                                                        {!! Auth::user()->description !!}
                                                    </textarea>
                                        </div>
                                        @endif
                                        
                                    @else
                                        {{-- Investor Profile Section for Customers --}}
                                        @php
                                            $investorProfile = Auth::user()->investorProfile;
                                        @endphp
                                        
                                        <div class="col-12">
                                            <h5 class="text-primary mt-4 mb-3">
                                                <i class="fas fa-user-tie me-2"></i>Investor Information
                                            </h5>
                                            <p class="text-muted small">This information is used for investment and property purchases. Keep it up to date for faster checkout.</p>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="investor_type" class="form-label">Investor Type</label>
                                            <select id="investor_type" name="investor_type" class="form-select">
                                                <option value="">Select investor type</option>
                                                <option value="individual" {{ $investorProfile && $investorProfile->investor_type == 'individual' ? 'selected' : '' }}>Myself/an individual</option>
                                                <option value="joint" {{ $investorProfile && $investorProfile->investor_type == 'joint' ? 'selected' : '' }}>Joint (more than one individual)</option>
                                                <option value="corporation" {{ $investorProfile && $investorProfile->investor_type == 'corporation' ? 'selected' : '' }}>Corporation</option>
                                                <option value="trust" {{ $investorProfile && $investorProfile->investor_type == 'trust' ? 'selected' : '' }}>Trust</option>
                                                <option value="ira" {{ $investorProfile && $investorProfile->investor_type == 'ira' ? 'selected' : '' }}>IRA</option>
                                            </select>
                                        </div>
                                        
                                        {{-- Individual Fields --}}
                                        <div id="profile-individual-fields" class="profile-investor-fields" style="display: {{ $investorProfile && $investorProfile->investor_type == 'individual' ? 'contents' : 'none' }};">
                                            <div class="col-md-6">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" name="investor_data[individual_name]" class="form-control" value="{{ $investorProfile->investor_data['individual_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Date of Birth</label>
                                                <input type="date" name="investor_data[date_of_birth]" class="form-control" value="{{ $investorProfile->investor_data['date_of_birth'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Social Security Number</label>
                                                <input type="text" name="investor_data[ssn]" class="form-control" placeholder="XXX-XX-XXXX" value="{{ $investorProfile->investor_data['ssn'] ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        {{-- Joint Fields --}}
                                        <div id="profile-joint-fields" class="profile-investor-fields" style="display: {{ $investorProfile && $investorProfile->investor_type == 'joint' ? 'contents' : 'none' }};">
                                            <div class="col-md-6">
                                                <label class="form-label">Primary Account Holder Name</label>
                                                <input type="text" name="investor_data[primary_name]" class="form-control" value="{{ $investorProfile->investor_data['primary_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Primary Holder Date of Birth</label>
                                                <input type="date" name="investor_data[primary_dob]" class="form-control" value="{{ $investorProfile->investor_data['primary_dob'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Primary Holder SSN</label>
                                                <input type="text" name="investor_data[primary_ssn]" class="form-control" placeholder="XXX-XX-XXXX" value="{{ $investorProfile->investor_data['primary_ssn'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Secondary Account Holder Name</label>
                                                <input type="text" name="investor_data[secondary_name]" class="form-control" value="{{ $investorProfile->investor_data['secondary_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Secondary Holder Date of Birth</label>
                                                <input type="date" name="investor_data[secondary_dob]" class="form-control" value="{{ $investorProfile->investor_data['secondary_dob'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Secondary Holder SSN</label>
                                                <input type="text" name="investor_data[secondary_ssn]" class="form-control" placeholder="XXX-XX-XXXX" value="{{ $investorProfile->investor_data['secondary_ssn'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Joint Account Type</label>
                                                <select name="investor_data[joint_type]" class="form-select">
                                                    <option value="">Select joint type</option>
                                                    <option value="jtwros" {{ isset($investorProfile->investor_data['joint_type']) && $investorProfile->investor_data['joint_type'] == 'jtwros' ? 'selected' : '' }}>Joint Tenants with Rights of Survivorship</option>
                                                    <option value="tenants_common" {{ isset($investorProfile->investor_data['joint_type']) && $investorProfile->investor_data['joint_type'] == 'tenants_common' ? 'selected' : '' }}>Tenants in Common</option>
                                                    <option value="community_property" {{ isset($investorProfile->investor_data['joint_type']) && $investorProfile->investor_data['joint_type'] == 'community_property' ? 'selected' : '' }}>Community Property</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        {{-- Corporation Fields --}}
                                        <div id="profile-corporation-fields" class="profile-investor-fields" style="display: {{ $investorProfile && $investorProfile->investor_type == 'corporation' ? 'contents' : 'none' }};">
                                            <div class="col-md-6">
                                                <label class="form-label">Corporation Name</label>
                                                <input type="text" name="investor_data[corporation_name]" class="form-control" value="{{ $investorProfile->investor_data['corporation_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Federal Tax ID (EIN)</label>
                                                <input type="text" name="investor_data[ein]" class="form-control" placeholder="XX-XXXXXXX" value="{{ $investorProfile->investor_data['ein'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">State of Incorporation</label>
                                                <input type="text" name="investor_data[incorporation_state]" class="form-control" value="{{ $investorProfile->investor_data['incorporation_state'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Accredited Investor Status</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="investor_data[accredited_investor]" value="yes" {{ isset($investorProfile->investor_data['accredited_investor']) && $investorProfile->investor_data['accredited_investor'] == 'yes' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="investor_data[accredited_investor]" value="no" {{ isset($investorProfile->investor_data['accredited_investor']) && $investorProfile->investor_data['accredited_investor'] == 'no' ? 'checked' : '' }}>
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Trust Fields --}}
                                        <div id="profile-trust-fields" class="profile-investor-fields" style="display: {{ $investorProfile && $investorProfile->investor_type == 'trust' ? 'contents' : 'none' }};">
                                            <div class="col-md-6">
                                                <label class="form-label">Trust Name</label>
                                                <input type="text" name="investor_data[trust_name]" class="form-control" value="{{ $investorProfile->investor_data['trust_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Trust Tax ID (EIN)</label>
                                                <input type="text" name="investor_data[trust_ein]" class="form-control" placeholder="XX-XXXXXXX" value="{{ $investorProfile->investor_data['trust_ein'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Trust Type</label>
                                                <select name="investor_data[trust_type]" class="form-select">
                                                    <option value="">Select trust type</option>
                                                    <option value="revocable" {{ isset($investorProfile->investor_data['trust_type']) && $investorProfile->investor_data['trust_type'] == 'revocable' ? 'selected' : '' }}>Revocable Trust</option>
                                                    <option value="irrevocable" {{ isset($investorProfile->investor_data['trust_type']) && $investorProfile->investor_data['trust_type'] == 'irrevocable' ? 'selected' : '' }}>Irrevocable Trust</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        {{-- IRA Fields --}}
                                        <div id="profile-ira-fields" class="profile-investor-fields" style="display: {{ $investorProfile && $investorProfile->investor_type == 'ira' ? 'contents' : 'none' }};">
                                            <div class="col-md-6">
                                                <label class="form-label">Account Holder Name</label>
                                                <input type="text" name="investor_data[ira_holder_name]" class="form-control" value="{{ $investorProfile->investor_data['ira_holder_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">IRA Type</label>
                                                <select name="investor_data[ira_type]" class="form-select">
                                                    <option value="">Select IRA type</option>
                                                    <option value="traditional" {{ isset($investorProfile->investor_data['ira_type']) && $investorProfile->investor_data['ira_type'] == 'traditional' ? 'selected' : '' }}>Traditional IRA</option>
                                                    <option value="roth" {{ isset($investorProfile->investor_data['ira_type']) && $investorProfile->investor_data['ira_type'] == 'roth' ? 'selected' : '' }}>Roth IRA</option>
                                                    <option value="sep" {{ isset($investorProfile->investor_data['ira_type']) && $investorProfile->investor_data['ira_type'] == 'sep' ? 'selected' : '' }}>SEP IRA</option>
                                                    <option value="simple" {{ isset($investorProfile->investor_data['ira_type']) && $investorProfile->investor_data['ira_type'] == 'simple' ? 'selected' : '' }}>SIMPLE IRA</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">IRA Custodian</label>
                                                <input type="text" name="investor_data[custodian]" class="form-control" value="{{ $investorProfile->investor_data['custodian'] ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const investorTypeSelect = document.getElementById('investor_type');
                                            if (investorTypeSelect) {
                                                investorTypeSelect.addEventListener('change', function() {
                                                    // Hide all investor fields
                                                    document.querySelectorAll('.profile-investor-fields').forEach(el => {
                                                        el.style.display = 'none';
                                                    });
                                                    
                                                    // Show selected type fields
                                                    const selectedType = this.value;
                                                    if (selectedType) {
                                                        const fieldsEl = document.getElementById('profile-' + selectedType + '-fields');
                                                        if (fieldsEl) {
                                                            fieldsEl.style.display = 'contents';
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                        </script>
                                    @endif











                                    {{-- <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="show_send_button" name="show_send_button" value="1" checked="">
                                            <label class="form-check-label " for="show_send_button">
                                                Show send message button
                                            </label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Show send message button" data-description="Checking this box will allow people to send an email message by clicking a button on the profile.
                    Email addresses will not be visible on the website."></i>
                                        </div>
                                    </div> --}}











                                    {{-- <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="show_amount_raised" name="show_amount_raised" value="1" checked="">
                                            <label class="form-check-label " for="show_amount_raised">
                                                Show amount raised
                                            </label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Show amount raised"
                                                data-description="The amount you raise is displayed on your personal fundraising page and on the Leaderboard.
                                                        If you don't want to show the amount you raised, uncheck this box."></i>
                                        </div>
                                    </div> --}}










                                    {{--
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="receive_donation_notification" name="receive_donation_notification"
                                                value="1" checked="">
                                            <label class="form-check-label " for="receive_donation_notification">
                                                Receive notifications of donations
                                            </label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Receive notifications of donations" data-description="If checked, you will receive email notifications of donations made on your personal fundraising
                                                    page."></i>
                                        </div>
                                    </div> --}}









                                    @if (Auth::user()->role != 'customer')
                                        
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                Image(s)
                                            </h5>
                                            <img src="{{ asset(Auth::user()->photo) }}" width="150px">
                                        </div>

                                        <div class="col-12">
                                            <label for="photo" class="form-label ">
                                                Profile Photo
                                            </label>


                                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo-image-file" name="photo"
                                                accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                                            @error('photo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                                        </div>
                                    @endif


                                </div>

                                <div class="sticky-save-button-container">
                                    <div class="sticky-save-button-inner">
                                        <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                            type="submit" id="">
                                            Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
        
        <script>
        function copyProfileUrl() {
            const profileUrl = window.location.origin + '/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}';
            
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = profileUrl;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                // Show success message
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check fa-fw"></i><span>Copied!</span>';
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy URL. Please copy manually: ' + profileUrl);
            }
            
            document.body.removeChild(textarea);
        }
        
        // Profile photo validation
        document.addEventListener('DOMContentLoaded', function() {
            const profilePhotoInput = document.getElementById('photo-image-file');
            const profileForm = profilePhotoInput ? profilePhotoInput.closest('form') : null;
            const submitBtn = profileForm ? profileForm.querySelector('button[type="submit"]') : null;
            let removeFileBtn = document.getElementById('removeProfileFileBtn');
            
            // Create remove file button if not exists
            if (!removeFileBtn && profilePhotoInput) {
                removeFileBtn = document.createElement('button');
                removeFileBtn.id = 'removeProfileFileBtn';
                removeFileBtn.type = 'button';
                removeFileBtn.className = 'btn btn-sm btn-danger ms-2';
                removeFileBtn.innerHTML = '<i class="fas fa-times me-1"></i>Remove File';
                removeFileBtn.style.display = 'none';
                removeFileBtn.addEventListener('click', function() {
                    profilePhotoInput.value = '';
                    profilePhotoInput.classList.remove('is-invalid');
                    const errorDiv = profilePhotoInput.parentNode.querySelector('.invalid-feedback.d-block');
                    if (errorDiv && !errorDiv.classList.contains('permanent-error')) {
                        errorDiv.style.display = 'none';
                        errorDiv.textContent = '';
                    }
                    removeFileBtn.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = false;
                });
                profilePhotoInput.parentNode.appendChild(removeFileBtn);
            }
            
            if (profilePhotoInput && profileForm) {
                profilePhotoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    let errorDiv = profilePhotoInput.parentNode.querySelector('.invalid-feedback.d-block');
                    
                    // Create error div if it doesn't exist
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block';
                        errorDiv.style.display = 'none';
                        profilePhotoInput.parentNode.appendChild(errorDiv);
                    }
                    
                    if (file) {
                        // Clear previous errors only when a new file is selected
                        profilePhotoInput.classList.remove('is-invalid');
                        if (!errorDiv.classList.contains('permanent-error')) {
                            errorDiv.style.display = 'none';
                            errorDiv.textContent = '';
                        }
                        
                        // Check file size (5MB max)
                        const maxSize = 5 * 1024 * 1024;
                        if (file.size > maxSize) {
                            profilePhotoInput.classList.add('is-invalid');
                            errorDiv.style.display = 'block';
                            errorDiv.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Get file extension
                        const fileName = file.name.toLowerCase();
                        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        const fileExtension = fileName.split('.').pop();
                        
                        // Check file extension first (catches HEIC, WEBP, etc.)
                        if (!allowedExtensions.includes(fileExtension)) {
                            profilePhotoInput.classList.add('is-invalid');
                            errorDiv.style.display = 'block';
                            errorDiv.textContent = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Check file type (MIME type)
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            profilePhotoInput.classList.add('is-invalid');
                            errorDiv.style.display = 'block';
                            errorDiv.textContent = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // File is valid - enable submit button and hide remove button
                        if (submitBtn) submitBtn.disabled = false;
                        if (removeFileBtn) removeFileBtn.style.display = 'none';
                    } else {
                        // No file selected - enable submit button (photo is optional)
                        if (submitBtn) submitBtn.disabled = false;
                        if (removeFileBtn) removeFileBtn.style.display = 'none';
                    }
                });
                
                // Prevent form submission if there's a validation error
                profileForm.addEventListener('submit', function(e) {
                    const file = profilePhotoInput.files[0];
                    let hasError = false;
                    let errorMessage = '';
                    
                    if (file) {
                        // Check file size
                        const maxSize = 5 * 1024 * 1024;
                        if (file.size > maxSize) {
                            hasError = true;
                            errorMessage = 'File size exceeds 5MB. Please choose a smaller image.';
                        }
                        
                        // Check file extension
                        const fileName = file.name.toLowerCase();
                        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        const fileExtension = fileName.split('.').pop();
                        if (!hasError && !allowedExtensions.includes(fileExtension)) {
                            hasError = true;
                            errorMessage = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                        }
                        
                        // Check MIME type
                        if (!hasError) {
                            const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                            if (!allowedTypes.includes(file.type)) {
                                hasError = true;
                                errorMessage = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF images only.';
                            }
                        }
                    }
                    
                    // Also check if input already has invalid class
                    if (!hasError && profilePhotoInput.classList.contains('is-invalid')) {
                        hasError = true;
                        errorMessage = 'Please fix the file upload error before submitting.';
                    }
                    
                    if (hasError) {
                        // PREVENT submission completely
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        // Show error message
                        profilePhotoInput.classList.add('is-invalid');
                        let errorDiv = profilePhotoInput.parentNode.querySelector('.invalid-feedback.d-block');
                        if (!errorDiv) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback d-block';
                            profilePhotoInput.parentNode.appendChild(errorDiv);
                        }
                        if (!errorDiv.classList.contains('permanent-error')) {
                            errorDiv.style.display = 'block';
                            errorDiv.textContent = errorMessage;
                        }
                        profilePhotoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        return false;
                    }
                });
            }
        });
        </script>
        
        <!-- Share Modal -->
        <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-share-nodes me-2"></i> Share Your Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">Share your fundraising profile with supporters using:</p>
                        
                        <div class="d-flex gap-3 justify-content-center mb-4">
                            <a id="shareWhatsApp" href="" target="_blank" class="share-btn-circle btn btn-success" title="Share on WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a id="shareTwitter" href="" target="_blank" class="share-btn-circle btn btn-info" title="Share on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a id="shareFacebook" href="" target="_blank" class="share-btn-circle btn btn-primary" title="Share on Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a id="shareEmail" href="" class="share-btn-circle btn btn-secondary" title="Share via Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Or copy your profile URL:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="profileUrl" readonly value="">
                                <button class="btn btn-outline-primary" type="button" onclick="copyProfileUrlFromModal()">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .share-btn-circle {
                width: 60px !important;
                height: 60px !important;
                padding: 0 !important;
                border-radius: 50% !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 24px !important;
            }
            .share-btn-circle i {
                line-height: 1 !important;
            }
        </style>
        
        <!-- QR Code Modal -->
        <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-qrcode me-2"></i> Your Profile QR Code
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="text-muted mb-3">Share this QR code with supporters to visit your profile</p>
                        <div id="qrCodeContainer" style="display: none;">
                            <img id="qrCodeImage" src="" alt="Profile QR Code" style="max-width: 400px; border: 3px solid #ffc107; padding: 15px; border-radius: 10px;">
                            <div id="qrInfo" class="mt-3 text-start">
                                <small class="text-muted"><strong>Profile:</strong> <span id="qrProfileName">-</span></small><br>
                                <small class="text-muted"><strong>URL:</strong> <code id="qrUrl" style="font-size: 0.75rem;">-</code></small>
                            </div>
                        </div>
                        <div id="qrLoading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Generating QR Code...</span>
                            </div>
                            <p class="text-muted mt-2">Generating QR Code...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-warning" onclick="downloadProfileQR()">
                            <i class="fas fa-download me-1"></i> Download QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        let currentProfileQRData = null;
        
        // Initialize share modal with URLs
        document.getElementById('shareModal').addEventListener('show.bs.modal', function () {
            const profileUrl = window.location.origin + '/profile/{{ Auth::user()->id }}-{{ str_replace(' ', '-', Auth::user()->name) }}-{{ str_replace(' ', '-', Auth::user()->last_name) }}';
            
            // Set the URL in the input field
            document.getElementById('profileUrl').value = profileUrl;
            
            // WhatsApp share
            document.getElementById('shareWhatsApp').href = `https://wa.me/?text=Check out my fundraising profile: ${profileUrl}`;
            
            // Twitter share
            document.getElementById('shareTwitter').href = `https://twitter.com/intent/tweet?text=Support my fundraising: &url=${profileUrl}`;
            
            // Facebook share
            document.getElementById('shareFacebook').href = `https://www.facebook.com/sharer/sharer.php?u=${profileUrl}`;
            
            // Email share
            document.getElementById('shareEmail').href = `mailto:?subject=Check out my fundraising profile&body=Hi,%0A%0APlease visit my fundraising profile here: ${profileUrl}%0A%0AThanks for your support!`;
        });
        
        function copyProfileUrlFromModal() {
            const urlInput = document.getElementById('profileUrl');
            urlInput.select();
            
            try {
                document.execCommand('copy');
                const btn = event.target;
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-primary');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy URL. Please copy manually from the field.');
            }
        }
        
        function generateProfileQR() {
            const modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
            modal.show();
            
            // Show loading, hide QR
            document.getElementById('qrLoading').style.display = 'block';
            document.getElementById('qrCodeContainer').style.display = 'none';
            
            try {
                const response = fetch('{{ route("users.profile-qr.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                response.then(res => res.json()).then(result => {
                    if (result.success) {
                        currentProfileQRData = result;
                        document.getElementById('qrCodeImage').src = result.qr_code_base64;
                        document.getElementById('qrProfileName').textContent = '{{ Auth::user()->name }} {{ Auth::user()->last_name }}';
                        document.getElementById('qrUrl').textContent = result.profile_url;
                        
                        document.getElementById('qrLoading').style.display = 'none';
                        document.getElementById('qrCodeContainer').style.display = 'block';
                    } else {
                        alert('Error: ' + (result.message || 'Failed to generate QR code'));
                        modal.hide();
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Error generating QR code: ' + error.message);
                    modal.hide();
                });
            } catch (error) {
                console.error('Error:', error);
                alert('Error generating QR code: ' + error.message);
                modal.hide();
            }
        }
        
        function downloadProfileQR() {
            if (!currentProfileQRData) {
                alert('QR Code not generated yet');
                return;
            }
            
            const link = document.createElement('a');
            link.href = currentProfileQRData.qr_code_base64;
            link.download = `profile-qr-code-{{ Auth::user()->id }}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        </script>

        <script>
            ClassicEditor
                .create(document.querySelector('#description'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                            'undo', 'redo'
                        ]
                    },
                    image: {
                        toolbar: [
                            'imageTextAlternative', 'toggleImageCaption', 'imageStyle:inline',
                            'imageStyle:block', 'imageStyle:side'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells'
                        ]
                    },
                    mediaEmbed: {
                        previewsInData: true
                    }
                })
                .then(editor => {
                    // Custom upload adapter for images
                    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                        return {
                            upload: () => {
                                return loader.file.then(file => {
                                    return new Promise((resolve, reject) => {
                                        const reader = new FileReader();
                                        reader.onload = () => {
                                            resolve({ default: reader.result });
                                        };
                                        reader.onerror = error => reject(error);
                                        reader.readAsDataURL(file);
                                    });
                                });
                            }
                        };
                    };
                })
                .catch(error => {
                    console.error(error);
                });
        </script>

<!-- Add Student Modal -->
@if(Auth::user()->role == 'parents')
<div class="modal fade" style="margin-top: 70px;" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 20px !important">
        <div class="modal-content">
            <form id="addStudentForm" action="{{ route('parent.add-student') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add Participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                        <div class="form-text">Credentials are automatically generated for system use only and are not shared or tracked outside the fundraiser.</div>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                        <select class="form-select teacher-select" id="teacher_id" name="teacher_id" required>
                            <option value="">Choose a teacher</option>
                            @if(isset($teachers))
                                @foreach($teachers->sort(function($a, $b) {
                                    $nameA = preg_replace('/^(Mr|Ms|Mrs|Dr)\\.?\\s*/i', '', $a->name);
                                    $nameB = preg_replace('/^(Mr|Ms|Mrs|Dr)\\.?\\s*/i', '', $b->name);
                                    return strcasecmp($nameA, $nameB);
                                }) as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_goal" class="form-label">Fundraising Goal</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="modal_goal" name="goal" min="0" step="0.01">
                            <span class="input-group-text">.00 USD</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modal_tshirt_size" class="form-label">T-Shirt Size</label>
                        <select class="form-select" id="modal_tshirt_size" name="tshirt_size">
                            <option value="">Select a size</option>
                            <option value="Youth XS">Youth XS</option>
                            <option value="Youth Small">Youth Small</option>
                            <option value="Youth Medium">Youth Medium</option>
                            <option value="Youth Large">Youth Large</option>
                            <option value="Adult Small">Adult Small</option>
                            <option value="Adult Medium">Adult Medium</option>
                            <option value="Adult Large">Adult Large</option>
                            <option value="Adult XL">Adult XL</option>
                            <option value="Adult XXL">Adult XXL</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_description" class="form-label">Profile Description</label>
                        <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="modal_photo" class="form-label">Upload Photo</label>
                        <input class="form-control @error('photo') is-invalid @enderror" type="file" id="modal_photo" name="photo" accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                        <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                        <div class="invalid-feedback" id="modal_photo_error" style="@error('photo') display: block; @else display: none; @enderror">@error('photo'){{ $message }}@enderror</div>
                    </div>
                </div>
                <div class="modal-footer pt-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Wait for jQuery and Select2 to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Check if jQuery and Select2 are loaded
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            // Initialize Select2 for teacher select with search
            jQuery('.teacher-select').select2({
                placeholder: 'Search and select a teacher',
                allowClear: true,
                width: '100%'
            });
        }

        $(document).ready(function() {
            // Hide loader if there are backend validation errors
            @if($errors->any())
                const participantLoader = document.getElementById('participant-loader');
                if (participantLoader) {
                    participantLoader.style.display = 'none';
                }
                document.body.classList.remove('page-locked');
                window.onbeforeunload = null;
            @endif
        });
        
        // Photo upload validation
        document.addEventListener('DOMContentLoaded', function() {
            // Reopen modal if there are backend validation errors
            @if($errors->has('photo') || $errors->has('first_name') || $errors->has('last_name') || $errors->has('teacher_id'))
                var addStudentModal = new bootstrap.Modal(document.getElementById('addStudentModal'));
                addStudentModal.show();
                
                // Restore form values
                @if(old('first_name'))
                    document.getElementById('first_name').value = "{{ old('first_name') }}";
                @endif
                @if(old('last_name'))
                    document.getElementById('last_name').value = "{{ old('last_name') }}";
                @endif
                @if(old('teacher_id'))
                    document.getElementById('teacher_id').value = "{{ old('teacher_id') }}";
                @endif
                @if(old('goal'))
                    document.getElementById('modal_goal').value = "{{ old('goal') }}";
                @endif
                @if(old('tshirt_size'))
                    document.getElementById('modal_tshirt_size').value = "{{ old('tshirt_size') }}";
                @endif
                @if(old('description'))
                    document.getElementById('modal_description').value = `{{ old('description') }}`;
                @endif
            @endif
        });
        
        // Photo upload validation
        const photoInput = document.getElementById('modal_photo');
        const photoError = document.getElementById('modal_photo_error');
        const form = document.getElementById('addStudentForm');
            const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
            let removeFileBtn = document.getElementById('removeFileBtn');
            
            // Create remove file button if not exists
            if (!removeFileBtn && photoInput) {
                removeFileBtn = document.createElement('button');
                removeFileBtn.id = 'removeFileBtn';
                removeFileBtn.type = 'button';
                removeFileBtn.className = 'btn btn-sm btn-danger ms-2';
                removeFileBtn.innerHTML = '<i class="fas fa-times me-1"></i>Remove File';
                removeFileBtn.style.display = 'none';
                removeFileBtn.addEventListener('click', function() {
                    photoInput.value = '';
                    photoInput.classList.remove('is-invalid');
                    photoError.style.display = 'none';
                    photoError.textContent = '';
                    removeFileBtn.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = false;
                });
                photoInput.parentNode.appendChild(removeFileBtn);
            }
            
            if (photoInput && form) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    
                    if (file) {
                        // Clear previous errors only when a new file is selected
                        photoInput.classList.remove('is-invalid');
                        photoError.style.display = 'none';
                        photoError.textContent = '';
                        
                        // Check file size (5MB max)
                        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                        if (file.size > maxSize) {
                            photoInput.classList.add('is-invalid');
                            photoError.style.display = 'block';
                            photoError.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Get file extension
                        const fileName = file.name.toLowerCase();
                        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        const fileExtension = fileName.split('.').pop();
                        
                        // Check file extension first (catches HEIC, WEBP, etc.)
                        if (!allowedExtensions.includes(fileExtension)) {
                            photoInput.classList.add('is-invalid');
                            photoError.style.display = 'block';
                            photoError.textContent = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // Check file type
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            photoInput.classList.add('is-invalid');
                            photoError.style.display = 'block';
                            photoError.textContent = 'Invalid file type. Please upload an image file (PNG, JPG, GIF).';
                            e.target.value = '';
                            if (submitBtn) submitBtn.disabled = true;
                            if (removeFileBtn) removeFileBtn.style.display = 'inline-block';
                            return;
                        }
                        
                        // File is valid - enable submit button and hide remove button
                        if (submitBtn) submitBtn.disabled = false;
                        if (removeFileBtn) removeFileBtn.style.display = 'none';
                    } else {
                        // No file selected - enable submit button (photo is optional)
                        if (submitBtn) submitBtn.disabled = false;
                        if (removeFileBtn) removeFileBtn.style.display = 'none';
                    }
                });
                
                // Prevent form submission if there's a validation error
                form.addEventListener('submit', function(e) {
                // Check if there's a file and validate it immediately
                const file = photoInput.files[0];
                let hasError = false;
                let errorMessage = '';
                
                if (file) {
                    // Check file size
                    const maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        hasError = true;
                        errorMessage = 'File size exceeds 5MB. Please choose a smaller image.';
                    }
                    
                    // Check file extension
                    const fileName = file.name.toLowerCase();
                    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    const fileExtension = fileName.split('.').pop();
                    if (!hasError && !allowedExtensions.includes(fileExtension)) {
                        hasError = true;
                        errorMessage = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                    }
                    
                    // Check MIME type
                    if (!hasError) {
                        const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                        if (!allowedTypes.includes(file.type)) {
                            hasError = true;
                            errorMessage = 'Invalid file type. Please upload an image file (PNG, JPG, GIF).';
                        }
                    }
                }
                
                // Also check if input already has invalid class
                if (!hasError && photoInput.classList.contains('is-invalid')) {
                    hasError = true;
                    errorMessage = 'Please fix the file upload error before submitting.';
                }
                
                if (hasError) {
                    // PREVENT submission completely
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // Show error message
                    photoInput.classList.add('is-invalid');
                    photoError.style.display = 'block';
                    photoError.textContent = errorMessage;
                    photoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // DO NOT show loader
                    return false;
                }
                
                // ONLY show loader if we reach here (no errors)
                const participantLoader = document.getElementById('participant-loader');
                if (participantLoader) {
                    participantLoader.style.display = 'flex';
                }
                document.body.classList.add('page-locked');
            });
        }
    });
    </script>

    <!-- Parent Tutorial Script -->
    @if(Auth::user()->role == 'parents')
    <script>
        let isFirstVisit = @if(isset($showTutorial) && $showTutorial) true @else false @endif;
        
        function startParentTutorial() {
            const intro = introJs();
            
            // Add class to body to hide skip button via CSS
            if (isFirstVisit) {
                document.body.classList.add('tutorial-first-visit');
            }
            
            // Detect if user is on mobile
            const isMobile = window.innerWidth < 768;
            
            // Build steps based on device type
            let tutorialSteps = [];
            
            // Welcome step (both mobile and desktop)
            tutorialSteps.push({
                title: 'Welcome! 👋',
                intro: 'Welcome to your dashboard! Let me show you how to add and manage participants under your profile.'
            });
            
            if (isMobile) {
                // Mobile-specific tutorial steps (skip sidebar references)
                tutorialSteps.push({
                    title: 'Navigation Menu 📱',
                    intro: 'On mobile, tap the menu icon (☰) at the top to access all sections like participants, Profile, and Payments.',
                    tooltipClass: 'introjs-floating'
                });
                
                tutorialSteps.push({
                    title: 'Adding Participants 🎓',
                    intro: 'To add a new participant:<br><br>1. Tap the menu icon (☰) at the top<br>2. Select "Participant"<br>3. Tap "Add Participant" button<br>4. Fill in their information<br>5. Tap "Save" to add them',
                    tooltipClass: 'introjs-floating'
                });
                
                tutorialSteps.push({
                    title: 'Managing Participants',
                    intro: 'Once you\'ve added participants, you can:<br><br>• View their fundraising progress<br>• Edit their profile information<br>• Track donations received<br>• Share their fundraising page',
                    tooltipClass: 'introjs-floating'
                });
                
                tutorialSteps.push({
                    element: document.querySelector('#tutorialBtn'),
                    title: 'Need Help Later?',
                    intro: 'You can always replay this tutorial by tapping this button anytime!',
                    position: 'bottom'
                });
                
                tutorialSteps.push({
                    title: 'You\'re All Set! 🎉',
                    intro: 'That\'s it! You\'re ready to start managing your participants. Tap the menu icon (☰) and select "Participant" to get started!',
                    tooltipClass: 'introjs-floating'
                });
            } else {
                // Desktop tutorial steps (original with sidebar references)
                tutorialSteps.push({
                    element: document.querySelector('#students-menu-item'),
                    title: 'Participants',
                    intro: 'Click here to view and manage all your participants. This is where you\'ll spend most of your time!',
                    position: 'right'
                });
                
                tutorialSteps.push({
                    element: document.querySelector('#profile-menu-item'),
                    title: 'Your Profile',
                    intro: 'Update your personal information and profile settings here.',
                    position: 'right'
                });
                
                tutorialSteps.push({
                    title: 'Adding Participants 🎓',
                    intro: 'To add a new participant:<br><br>1. Click on "Participant" in the sidebar<br>2. Click the "Add Participant" button<br>3. Fill in their information<br>4. Click "Save" to add them to your account',
                    tooltipClass: 'introjs-floating'
                });
                
                tutorialSteps.push({
                    title: 'Managing Participants',
                    intro: 'Once you\'ve added participants, you can:<br><br>• View their fundraising progress<br>• Edit their profile information<br>• Track donations received<br>• Share their fundraising page',
                    tooltipClass: 'introjs-floating'
                });
                
                tutorialSteps.push({
                    element: document.querySelector('#tutorialBtn'),
                    title: 'Need Help Later?',
                    intro: 'You can always replay this tutorial by clicking this button anytime!',
                    position: 'left'
                });
                
                tutorialSteps.push({
                    title: 'You\'re All Set! 🎉',
                    intro: 'That\'s it! You\'re ready to start managing your participants. Click "Participant" in the sidebar to get started!',
                    tooltipClass: 'introjs-floating'
                });
            }
            
            intro.setOptions({
                steps: tutorialSteps,
                showProgress: true,
                showBullets: false,
                exitOnOverlayClick: isFirstVisit ? false : true,
                exitOnEsc: isFirstVisit ? false : true,
                nextLabel: 'Next →',
                prevLabel: '← Back',
                doneLabel: 'Finish',
                scrollToElement: true,
                scrollPadding: 30,
                disableInteraction: true,
                overlayOpacity: 0.7
            });
            
            // Prevent exit on first visit via any method
            intro.onbeforeexit(function() {
                if (isFirstVisit) {
                    console.log('Blocking exit on first visit');
                    return false;
                }
                return true;
            });
            
            intro.oncomplete(function() {
                isFirstVisit = false;
                document.body.classList.remove('tutorial-first-visit');
                markTutorialAsSeen();
            });
            
            intro.onexit(function() {
                if (!isFirstVisit) {
                    document.body.classList.remove('tutorial-first-visit');
                    markTutorialAsSeen();
                }
            });
            
            intro.start();
        }
        
        function markTutorialAsSeen() {
            fetch('{{ route("parent.tutorial.seen") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
              .then(data => console.log('Tutorial marked as seen'))
              .catch(error => console.error('Error:', error));
        }
        
        // Auto-start tutorial on first visit for parents
        @if(isset($showTutorial) && $showTutorial)
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure page is fully loaded
            setTimeout(function() {
                startParentTutorial();
            }, 500);
        });
        @endif
    </script>
    <style>
        /* Intro.js Custom Styling */
        .introjs-overlay {
            background: rgba(0, 0, 0, 0.5);
        }

        .introjs-tooltip {
            max-width: 450px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: white;
        }

        .introjs-tooltip-title {
            font-size: 18px;
            font-weight: 700;
            padding: 15px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .introjs-tooltiptext {
            font-size: 14px;
            line-height: 1.6;
            padding: 15px 20px;
            color: #333;
        }

        .introjs-tooltipbuttons {
            padding: 0 20px 15px;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .introjs-button {
            border-radius: 5px;
            padding: 8px 16px;
            font-weight: 600;
            text-shadow: none;
            cursor: pointer;
            font-size: 12px;
            border: none;
            transition: all 0.2s ease;
        }

        .introjs-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .introjs-nextbutton {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .introjs-prevbutton {
            background: #e2e8f0;
            color: #2d3748;
        }

        .introjs-donebutton {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .introjs-skipbutton {
            color: #475569;
            background: #f8fafc;
            padding: 0;
            width: 15px;
            height: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            font-size: 16px;
            font-weight: 600;
            line-height: 28px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            margin-top: 21px;
            margin-right: 4px;
        }

        .introjs-skipbutton:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .introjs-skipbutton:disabled,
        .introjs-skipbutton.disabled {
            display: none !important;
        }

        body.tutorial-first-visit .introjs-skipbutton {
            display: none !important;
        }

        .introjs-progressbar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        /* Mobile responsive */
        @media(max-width: 768px) {
            .introjs-tooltip {
                max-width: 90vw;
            }
            
            .introjs-tooltipbuttons {
                flex-wrap: wrap;
            }
            
            .introjs-button {
                font-size: 11px;
                padding: 6px 12px;
                flex: 1;
            }
        }

        /* Safari-specific fixes for Intro.js */
        @supports (-webkit-appearance:none) {
            .introjs-tooltip {
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                will-change: transform, opacity;
            }
            
            .introjs-helperLayer {
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
            }
            
            .introjs-overlay {
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
            }
            
            /* Ensure tooltips are always visible in Safari */
            .introjs-tooltipReferenceLayer {
                visibility: visible !important;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
            
            /* Fix for centered tooltips without elements in Safari */
            .introjs-tooltip.introjs-floating {
                position: fixed !important;
                left: 50% !important;
                top: 50% !important;
                -webkit-transform: translate(-50%, -50%) translateZ(0) !important;
                transform: translate(-50%, -50%) translateZ(0) !important;
                margin: 0 !important;
            }
        }
    </style>
    @endif

@endsection
