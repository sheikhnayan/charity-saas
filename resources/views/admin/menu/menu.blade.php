@extends('admin.main')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="card p-4">
                        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="row gy-3" bis_skin_checked="1">

                                <div class="col-md-6 col-lg-4" data-step="1" data-title="Header status"
                                    data-intro="To completely remove the header section from your website, change the status to disabled."
                                    bis_skin_checked="1">
                                    <label for="status" class="form-label">
                                        Status
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                        data-title="Header status"
                                        data-description="To completely remove the header section from your website, change the status to disabled."></i>
                                    <select class="form-select" id="status" name="status">
                                        <option {{ $data->status == 1 ? 'selected' : '' }} value="1">
                                            Enabled
                                        </option>
                                        <option {{ $data->status == 0 ? 'selected' : '' }} value="0">
                                            Disabled
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4" data-step="3" data-title="Header text color"
                                    data-intro="Choose a color for the header text." bis_skin_checked="1">
                                    <label for="text_color" class="form-label">
                                        Header text color
                                    </label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="text_color_picker"
                                            value="{{ $data->color ?? '#000000' }}" title="Choose your color"
                                            style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="text_color" name="color"
                                            value="{{ $data->color ?? '#000000' }}" placeholder="#000000 or color name">
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

                                <div class="col-md-6 col-lg-4" data-step="4" data-title="Header background"
                                    data-intro="Choose a background color for the header of your website."
                                    bis_skin_checked="1">
                                    <label for="background_color" class="form-label">
                                        Header background
                                    </label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="background_color_picker" value="{{ $data->background ?? '#ffffff' }}" title="Choose background color" style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="background_color" name="background" value="{{ $data->background ?? '#ffffff' }}" placeholder="#ffffff or color name">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const colorInput = document.getElementById('background_color_picker');
                                            const textInput = document.getElementById('background_color');
                                            colorInput.addEventListener('input', function() {
                                                textInput.value = colorInput.value;
                                            });
                                            textInput.addEventListener('input', function() {
                                                const val = textInput.value.trim();
                                                if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                    colorInput.value = val;
                                                }
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Menu
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <select class="form-select" id="display_menu" name="menu">
                                        <option value="1" {{ $data->menu == 1 ? 'selected' : '' }}>
                                            Yes, display the menu
                                        </option>
                                        <option value="0" {{ $data->menu == 0 ? 'selected' : '' }}>
                                            No, hide the menu
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Floating
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <select class="form-select" id="display_menu" name="floating">
                                        <option value="1" {{ $data->floating == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                        <option value="0" {{ $data->floating == 0 ? 'selected' : '' }}>
                                            No
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="logo_size" class="form-label text-capitalize">
                                        Logo Width (px)
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Logo Width"
                                        data-description="Set the width of your logo in pixels. This controls how wide the logo appears in the navigation bar."></i>
                                    <input type="number" name="logo_size" id="logo_size" value="{{ $data->logo_size ?? 100 }}" class="form-control" min="20" max="400" placeholder="100">
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="logo_height" class="form-label text-capitalize">
                                        Logo Height (px)
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Logo Height"
                                        data-description="Set the height of your logo in pixels. This controls how tall the logo appears and affects the navbar height."></i>
                                    <input type="number" name="logo_height" id="logo_height" value="{{ $data->logo_height ?? 60 }}" class="form-control" min="20" max="200" placeholder="60">
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="menu_font_family" class="form-label text-capitalize">
                                        Menu Font Family
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Menu Font Family"
                                        data-description="Choose the font for navigation menu items."></i>
                                    <select class="form-select" id="menu_font_family" name="menu_font_family">
                                        <option value="">Default (System Font)</option>
                                        <option value="outfit" {{ ($data->menu_font_family ?? '') == 'outfit' ? 'selected' : '' }}>Outfit (Google Font)</option>
                                        <option value="arial" {{ ($data->menu_font_family ?? '') == 'arial' ? 'selected' : '' }}>Arial</option>
                                        <option value="helvetica" {{ ($data->menu_font_family ?? '') == 'helvetica' ? 'selected' : '' }}>Helvetica</option>
                                        <option value="times" {{ ($data->menu_font_family ?? '') == 'times' ? 'selected' : '' }}>Times New Roman</option>
                                        <option value="georgia" {{ ($data->menu_font_family ?? '') == 'georgia' ? 'selected' : '' }}>Georgia</option>
                                        <option value="verdana" {{ ($data->menu_font_family ?? '') == 'verdana' ? 'selected' : '' }}>Verdana</option>
                                        <option value="courier" {{ ($data->menu_font_family ?? '') == 'courier' ? 'selected' : '' }}>Courier New</option>
                                        @if(isset($customFonts) && $customFonts->count() > 0)
                                            <optgroup label="Custom Fonts">
                                                @foreach($customFonts as $font)
                                                    <option value="{{ $font->font_family }}" {{ ($data->menu_font_family ?? '') == $font->font_family ? 'selected' : '' }}>
                                                        {{ $font->font_name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="menu_font_size" class="form-label text-capitalize">
                                        Menu Font Size (px)
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Menu Font Size"
                                        data-description="Set the font size for menu and submenu items."></i>
                                    <input type="number" class="form-control" id="menu_font_size" name="menu_font_size"
                                           value="{{ $data->menu_font_size ?? 16 }}" min="8" max="60" step="1"
                                           placeholder="16">
                                    <small class="text-muted">Applied in pixels to main menu and dropdown links.</small>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="submenu_background_color" class="form-label text-capitalize">
                                        Submenu Background Color
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Submenu Background Color"
                                        data-description="Set background color for dropdown menus. Enable Transparent to remove submenu background."></i>
                                    <div class="input-group mb-2">
                                        <input type="color" class="form-control form-control-color" id="submenu_background_color_picker"
                                               value="{{ (isset($data->submenu_background_color) && $data->submenu_background_color && strtolower($data->submenu_background_color) !== 'transparent') ? $data->submenu_background_color : '#ffffff' }}"
                                               title="Choose submenu background color" style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="submenu_background_color" name="submenu_background_color"
                                               value="{{ $data->submenu_background_color ?? '#ffffff' }}"
                                               placeholder="#ffffff or transparent">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="submenu_background_transparent"
                                               {{ (isset($data->submenu_background_color) && strtolower($data->submenu_background_color) === 'transparent') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="submenu_background_transparent">
                                            Transparent
                                        </label>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const colorInput = document.getElementById('submenu_background_color_picker');
                                            const textInput = document.getElementById('submenu_background_color');
                                            const transparentCheckbox = document.getElementById('submenu_background_transparent');

                                            const syncTransparentState = () => {
                                                const isTransparent = transparentCheckbox.checked;
                                                if (isTransparent) {
                                                    textInput.value = 'transparent';
                                                    colorInput.disabled = true;
                                                } else {
                                                    if ((textInput.value || '').trim().toLowerCase() === 'transparent') {
                                                        textInput.value = colorInput.value || '#ffffff';
                                                    }
                                                    colorInput.disabled = false;
                                                }
                                            };

                                            colorInput.addEventListener('input', function() {
                                                if (!transparentCheckbox.checked) {
                                                    textInput.value = colorInput.value;
                                                }
                                            });

                                            textInput.addEventListener('input', function() {
                                                const val = (textInput.value || '').trim().toLowerCase();
                                                if (val === 'transparent') {
                                                    transparentCheckbox.checked = true;
                                                    syncTransparentState();
                                                    return;
                                                }

                                                if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(textInput.value.trim())) {
                                                    colorInput.value = textInput.value.trim();
                                                    transparentCheckbox.checked = false;
                                                    syncTransparentState();
                                                }
                                            });

                                            transparentCheckbox.addEventListener('change', syncTransparentState);
                                            syncTransparentState();
                                        });
                                    </script>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="contact_topbar_font_family" class="form-label text-capitalize">
                                        Contact Topbar Font Family
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Contact Topbar Font Family"
                                        data-description="Choose the font for the contact information topbar (phone, email, address)."></i>
                                    <select class="form-select" id="contact_topbar_font_family" name="contact_topbar_font_family">
                                        <option value="">Default (System Font)</option>
                                        <option value="outfit" {{ ($data->contact_topbar_font_family ?? '') == 'outfit' ? 'selected' : '' }}>Outfit (Google Font)</option>
                                        <option value="arial" {{ ($data->contact_topbar_font_family ?? '') == 'arial' ? 'selected' : '' }}>Arial</option>
                                        <option value="helvetica" {{ ($data->contact_topbar_font_family ?? '') == 'helvetica' ? 'selected' : '' }}>Helvetica</option>
                                        <option value="times" {{ ($data->contact_topbar_font_family ?? '') == 'times' ? 'selected' : '' }}>Times New Roman</option>
                                        <option value="georgia" {{ ($data->contact_topbar_font_family ?? '') == 'georgia' ? 'selected' : '' }}>Georgia</option>
                                        <option value="verdana" {{ ($data->contact_topbar_font_family ?? '') == 'verdana' ? 'selected' : '' }}>Verdana</option>
                                        <option value="courier" {{ ($data->contact_topbar_font_family ?? '') == 'courier' ? 'selected' : '' }}>Courier New</option>
                                        @if(isset($customFonts) && $customFonts->count() > 0)
                                            <optgroup label="Custom Fonts">
                                                @foreach($customFonts as $font)
                                                    <option value="{{ $font->font_family }}" {{ ($data->contact_topbar_font_family ?? '') == $font->font_family ? 'selected' : '' }}>
                                                        {{ $font->font_name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="investor_exclusives_font_family" class="form-label text-capitalize">
                                        Investor Exclusives Font Family
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Investor Exclusives Font Family"
                                        data-description="Choose the font for the investor exclusives bar (investment websites only)."></i>
                                    <select class="form-select" id="investor_exclusives_font_family" name="investor_exclusives_font_family">
                                        <option value="">Default (System Font)</option>
                                        <option value="outfit" {{ ($data->investor_exclusives_font_family ?? '') == 'outfit' ? 'selected' : '' }}>Outfit (Google Font)</option>
                                        <option value="arial" {{ ($data->investor_exclusives_font_family ?? '') == 'arial' ? 'selected' : '' }}>Arial</option>
                                        <option value="helvetica" {{ ($data->investor_exclusives_font_family ?? '') == 'helvetica' ? 'selected' : '' }}>Helvetica</option>
                                        <option value="times" {{ ($data->investor_exclusives_font_family ?? '') == 'times' ? 'selected' : '' }}>Times New Roman</option>
                                        <option value="georgia" {{ ($data->investor_exclusives_font_family ?? '') == 'georgia' ? 'selected' : '' }}>Georgia</option>
                                        <option value="verdana" {{ ($data->investor_exclusives_font_family ?? '') == 'verdana' ? 'selected' : '' }}>Verdana</option>
                                        <option value="courier" {{ ($data->investor_exclusives_font_family ?? '') == 'courier' ? 'selected' : '' }}>Courier New</option>
                                        @if(isset($customFonts) && $customFonts->count() > 0)
                                            <optgroup label="Custom Fonts">
                                                @foreach($customFonts as $font)
                                                    <option value="{{ $font->font_family }}" {{ ($data->investor_exclusives_font_family ?? '') == $font->font_family ? 'selected' : '' }}>
                                                        {{ $font->font_name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>
                                
                                @if ($website && $website->type == 'investment')
                                    <div class="col-md-6 col-lg-4">
                                        <label for="invest_now_button_text" class="form-label text-capitalize">
                                            Invest Now Button Text
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Invest Now Button Text"
                                            data-description="Set the text for the 'Invest Now' button that appears on the investment pages."></i>
                                        <input type="text" name="invest_now_button_text" id="invest_now_button_text" value="{{ $data->invest_now_button_text ?? 'INVEST NOW' }}" class="form-control" placeholder="INVEST NOW">
                                    </div>
                                @endif
                                
                                <!-- Login/Registration Button Section -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-sign-in-alt me-2"></i>Login / Registration Button</h5>
                                    <small class="text-muted">Configure the login/registration button in the header.</small>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="show_auth_button" class="form-label">
                                        Show Login/Registration Button
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Login/Registration Button"
                                        data-description="Enable this to show a login/registration button in the top right corner of the header. When clicked, it will open your authentication modal."></i>
                                    <select class="form-select" id="show_auth_button" name="show_auth_button">
                                        <option value="1" {{ ($data->show_auth_button ?? 0) == 1 ? 'selected' : '' }}>
                                            Yes, show the button
                                        </option>
                                        <option value="0" {{ ($data->show_auth_button ?? 0) == 0 ? 'selected' : '' }}>
                                            No, hide the button
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="auth_button_text" class="form-label">
                                        Button Text
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Button Text"
                                        data-description="The text displayed on the login/registration button."></i>
                                    <input type="text" class="form-control" id="auth_button_text" 
                                           name="auth_button_text" 
                                           value="{{ $data->auth_button_text ?? 'Login / Register' }}"
                                           placeholder="Login / Register">
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="auth_button_bg_color" class="form-label">
                                        Button Background Color
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Background Color"
                                        data-description="Choose a background color for the login/registration button."></i>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="auth_button_bg_color_picker" 
                                               value="{{ $data->auth_button_bg_color ?? '#007bff' }}" title="Choose background color" style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="auth_button_bg_color" name="auth_button_bg_color" 
                                               value="{{ $data->auth_button_bg_color ?? '#007bff' }}" placeholder="#007bff">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const colorInput = document.getElementById('auth_button_bg_color_picker');
                                            const textInput = document.getElementById('auth_button_bg_color');
                                            colorInput.addEventListener('input', function() {
                                                textInput.value = colorInput.value;
                                            });
                                            textInput.addEventListener('input', function() {
                                                const val = textInput.value.trim();
                                                if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                    colorInput.value = val;
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <label for="auth_button_text_color" class="form-label">
                                        Button Text Color
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Text Color"
                                        data-description="Choose a text color for the login/registration button."></i>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="auth_button_text_color_picker" 
                                               value="{{ $data->auth_button_text_color ?? '#ffffff' }}" title="Choose text color" style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="auth_button_text_color" name="auth_button_text_color" 
                                               value="{{ $data->auth_button_text_color ?? '#ffffff' }}" placeholder="#ffffff">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const colorInput = document.getElementById('auth_button_text_color_picker');
                                            const textInput = document.getElementById('auth_button_text_color');
                                            colorInput.addEventListener('input', function() {
                                                textInput.value = colorInput.value;
                                            });
                                            textInput.addEventListener('input', function() {
                                                const val = textInput.value.trim();
                                                if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                    colorInput.value = val;
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                                
                                <!-- Investor Exclusives Section -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-megaphone me-2"></i>Investor Exclusives Top Bar</h5>
                                    <small class="text-muted">Configure the promotional bar that appears below the menu.</small>
                                </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="show_investor_exclusives" class="form-label">
                                            Show Investor Exclusives Bar
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Investor Exclusives Bar"
                                            data-description="Display a promotional bar below the menu to highlight exclusive investor content."></i>
                                        <select class="form-select" id="show_investor_exclusives" name="show_investor_exclusives">
                                            <option value="1" {{ ($data->show_investor_exclusives ?? 0) == 1 ? 'selected' : '' }}>
                                                Yes, show the bar
                                            </option>
                                            <option value="0" {{ ($data->show_investor_exclusives ?? 0) == 0 ? 'selected' : '' }}>
                                                No, hide the bar
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="investor_exclusives_text" class="form-label">
                                            Display Text
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Display Text"
                                            data-description="The text that will appear in the investor exclusives bar."></i>
                                        <input type="text" class="form-control" id="investor_exclusives_text" 
                                               name="investor_exclusives_text" 
                                               value="{{ $data->investor_exclusives_text ?? 'Exclusive access for investors' }}"
                                               placeholder="Enter display text">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="investor_exclusives_url" class="form-label">
                                            Button Link URL
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Button Link"
                                            data-description="The URL where users will be directed when they click the button."></i>
                                        <input type="url" class="form-control" id="investor_exclusives_url" 
                                               name="investor_exclusives_url" 
                                               value="{{ $data->investor_exclusives_url ?? '#' }}"
                                               placeholder="https://example.com/investor-portal">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="topbar_background_color" class="form-label">
                                            Top Bar Background Color
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Background Color"
                                            data-description="Choose the background color for the investor exclusives bar."></i>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="topbar_background_color_picker" 
                                                   value="{{ $data->topbar_background_color ?? '#1e3a8a' }}" 
                                                   title="Choose background color" style="max-width: 3rem;">
                                            <input type="text" class="form-control" id="topbar_background_color" 
                                                   name="topbar_background_color" 
                                                   value="{{ $data->topbar_background_color ?? '#1e3a8a' }}" 
                                                   placeholder="#1e3a8a">
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const colorInput = document.getElementById('topbar_background_color_picker');
                                                const textInput = document.getElementById('topbar_background_color');
                                                colorInput.addEventListener('input', function() {
                                                    textInput.value = colorInput.value;
                                                });
                                                textInput.addEventListener('input', function() {
                                                    const val = textInput.value.trim();
                                                    if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                        colorInput.value = val;
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="topbar_text_color" class="form-label">
                                            Top Bar Text Color
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Text Color"
                                            data-description="Choose the text color for the investor exclusives bar."></i>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="topbar_text_color_picker" 
                                                   value="{{ $data->topbar_text_color ?? '#ffffff' }}" 
                                                   title="Choose text color" style="max-width: 3rem;">
                                            <input type="text" class="form-control" id="topbar_text_color" 
                                                   name="topbar_text_color" 
                                                   value="{{ $data->topbar_text_color ?? '#ffffff' }}" 
                                                   placeholder="#ffffff">
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const colorInput = document.getElementById('topbar_text_color_picker');
                                                const textInput = document.getElementById('topbar_text_color');
                                                colorInput.addEventListener('input', function() {
                                                    textInput.value = colorInput.value;
                                                });
                                                textInput.addEventListener('input', function() {
                                                    const val = textInput.value.trim();
                                                    if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                        colorInput.value = val;
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    
                                <!-- Contact Top Bar Section -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-phone me-2"></i>Contact Information Top Bar</h5>
                                    <small class="text-muted">Configure the contact information bar that appears above the menu.</small>
                                </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="show_contact_topbar" class="form-label">
                                            Show Contact Top Bar
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Contact Top Bar"
                                            data-description="Display a contact information bar above the menu with phone, email, and address."></i>
                                        <select class="form-select" id="show_contact_topbar" name="show_contact_topbar">
                                            <option value="1" {{ ($data->show_contact_topbar ?? 0) == 1 ? 'selected' : '' }}>
                                                Yes, show the bar
                                            </option>
                                            <option value="0" {{ ($data->show_contact_topbar ?? 0) == 0 ? 'selected' : '' }}>
                                                No, hide the bar
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_phone" class="form-label">
                                            Phone Number
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Phone Number"
                                            data-description="The phone number that will appear in the contact bar (clickable)."></i>
                                        <input type="text" class="form-control" id="contact_phone" 
                                               name="contact_phone" 
                                               value="{{ $data->contact_phone ?? '' }}"
                                               placeholder="425-243-7643">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_email" class="form-label">
                                            Email Address
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Email Address"
                                            data-description="The email address that will appear in the contact bar (clickable)."></i>
                                        <input type="email" class="form-control" id="contact_email" 
                                               name="contact_email" 
                                               value="{{ $data->contact_email ?? '' }}"
                                               placeholder="invest@deathondcompany.com">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_address" class="form-label">
                                            Address
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Address"
                                            data-description="The address that will appear in the contact bar."></i>
                                        <input type="text" class="form-control" id="contact_address" 
                                               name="contact_address" 
                                               value="{{ $data->contact_address ?? '' }}"
                                               placeholder="123 Investment St, City, State 12345">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_cta_text" class="form-label">
                                            CTA Button Text
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="CTA Button Text"
                                            data-description="The text for the call-to-action button in the contact bar."></i>
                                        <input type="text" class="form-control" id="contact_cta_text" 
                                               name="contact_cta_text" 
                                               value="{{ $data->contact_cta_text ?? '' }}"
                                               placeholder="Schedule a call">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_cta_url" class="form-label">
                                            CTA Button URL
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="CTA Button URL"
                                            data-description="The URL where users will be directed when they click the CTA button."></i>
                                        <input type="url" class="form-control" id="contact_cta_url" 
                                               name="contact_cta_url" 
                                               value="{{ $data->contact_cta_url ?? '' }}"
                                               placeholder="https://calendly.com/your-booking-link">
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_topbar_bg_color" class="form-label">
                                            Contact Bar Background Color
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Background Color"
                                            data-description="Choose the background color for the contact top bar."></i>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="contact_topbar_bg_color_picker" 
                                                   value="{{ $data->contact_topbar_bg_color ?? '#000000' }}" 
                                                   title="Choose background color" style="max-width: 3rem;">
                                            <input type="text" class="form-control" id="contact_topbar_bg_color" 
                                                   name="contact_topbar_bg_color" 
                                                   value="{{ $data->contact_topbar_bg_color ?? '#000000' }}" 
                                                   placeholder="#000000">
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const colorInput = document.getElementById('contact_topbar_bg_color_picker');
                                                const textInput = document.getElementById('contact_topbar_bg_color');
                                                colorInput.addEventListener('input', function() {
                                                    textInput.value = colorInput.value;
                                                });
                                                textInput.addEventListener('input', function() {
                                                    const val = textInput.value.trim();
                                                    if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                        colorInput.value = val;
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_topbar_text_color" class="form-label">
                                            Contact Bar Text Color
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="Text Color"
                                            data-description="Choose the text color for the contact top bar."></i>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="contact_topbar_text_color_picker" 
                                                   value="{{ $data->contact_topbar_text_color ?? '#ffffff' }}" 
                                                   title="Choose text color" style="max-width: 3rem;">
                                            <input type="text" class="form-control" id="contact_topbar_text_color" 
                                                   name="contact_topbar_text_color" 
                                                   value="{{ $data->contact_topbar_text_color ?? '#ffffff' }}" 
                                                   placeholder="#ffffff">
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const colorInput = document.getElementById('contact_topbar_text_color_picker');
                                                const textInput = document.getElementById('contact_topbar_text_color');
                                                colorInput.addEventListener('input', function() {
                                                    textInput.value = colorInput.value;
                                                });
                                                textInput.addEventListener('input', function() {
                                                    const val = textInput.value.trim();
                                                    if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                        colorInput.value = val;
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_cta_bg_color" class="form-label">
                                            CTA Button Background Color
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="CTA Background Color"
                                            data-description="Choose the background color for the CTA button."></i>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="contact_cta_bg_color_picker" 
                                                   value="{{ $data->contact_cta_bg_color ?? '#007bff' }}" 
                                                   title="Choose button background color" style="max-width: 3rem;">
                                            <input type="text" class="form-control" id="contact_cta_bg_color" 
                                                   name="contact_cta_bg_color" 
                                                   value="{{ $data->contact_cta_bg_color ?? '#007bff' }}" 
                                                   placeholder="#007bff">
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const colorInput = document.getElementById('contact_cta_bg_color_picker');
                                                const textInput = document.getElementById('contact_cta_bg_color');
                                                colorInput.addEventListener('input', function() {
                                                    textInput.value = colorInput.value;
                                                });
                                                textInput.addEventListener('input', function() {
                                                    const val = textInput.value.trim();
                                                    if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                        colorInput.value = val;
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contact_cta_text_color" class="form-label">
                                            CTA Button Text Color
                                        </label>
                                        <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-title="CTA Text Color"
                                            data-description="Choose the text color for the CTA button."></i>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="contact_cta_text_color_picker" 
                                                   value="{{ $data->contact_cta_text_color ?? '#ffffff' }}" 
                                                   title="Choose button text color" style="max-width: 3rem;">
                                            <input type="text" class="form-control" id="contact_cta_text_color" 
                                                   name="contact_cta_text_color" 
                                                   value="{{ $data->contact_cta_text_color ?? '#ffffff' }}" 
                                                   placeholder="#ffffff">
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const colorInput = document.getElementById('contact_cta_text_color_picker');
                                                const textInput = document.getElementById('contact_cta_text_color');
                                                colorInput.addEventListener('input', function() {
                                                    textInput.value = colorInput.value;
                                                });
                                                textInput.addEventListener('input', function() {
                                                    const val = textInput.value.trim();
                                                    if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                        colorInput.value = val;
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                            </div>
                            <div class="col-12 mb-4">
                                <label class="form-label">Menu Order</label>
                                <ul id="menu-sortable" class="list-group">
                                    @foreach($pages as $page)
                                        <li class="list-group-item d-flex align-items-center" data-id="{{ $page->id }}">
                                            <span class="handle me-2" style="cursor:move;">&#9776;</span>
                                            <span>{{ $page->name }}</span>
                                            <input type="hidden" name="menu_order[]" value="{{ $page->id }}">
                                        </li>
                                    @endforeach
                                </ul>
                                <small class="text-muted">Drag and drop to reorder your menu.</small>
                            </div>

                            <div class="sticky-save-button-container mt-4" bis_skin_checked="1">
                                <div class="sticky-save-button-inner" bis_skin_checked="1">
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
            <!-- / Content -->
            <script>
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .catch(error => {
                        console.error(error);
                    });
            </script>

            <!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('menu-sortable');
        if (el) {
            Sortable.create(el, {
                handle: '.handle',
                animation: 150,
                onEnd: function () {
                    // Update hidden inputs to match new order
                    let ids = [];
                    el.querySelectorAll('li').forEach(function(li, idx) {
                        li.querySelector('input[name="menu_order[]"]').value = li.getAttribute('data-id');
                    });
                }
            });
        }
    });
</script>
        @endsection
