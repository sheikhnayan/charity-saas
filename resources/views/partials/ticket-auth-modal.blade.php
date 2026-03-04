<div id="authModal" class="z-50 hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden max-h-[90vh] flex flex-col">
        <!-- Gradient Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-4 text-center flex-shrink-0">
            <button class="absolute top-3 right-3 text-white hover:text-gray-200 text-2xl font-bold z-10" onclick="closeAuthModal()">&times;</button>
            <i class="fas fa-user-circle text-white text-4xl mb-2"></i>
            <h2 class="text-xl font-bold text-white">Welcome</h2>
            <p class="text-purple-100 text-xs mt-1">Login or create your account</p>
        </div>
        
        <form id="authForm" autocomplete="off" class="p-6 overflow-y-auto flex-grow">
            @php
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $website = \App\Models\Website::where('domain', $domain)->first();
                $isFundraiser = $website && $website->type === 'fundraiser';
            @endphp

            <!-- Registration Not Available Message for Fundraiser -->
            @if($isFundraiser)
            <div id="registrationDisabledMessage" class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded hidden">
                <p class="text-sm text-blue-800 font-semibold mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Registration Not Available
                </p>
                <p class="text-xs text-blue-700 mb-3">
                    To create a new account, please visit our <strong>Registration page</strong>.
                </p>
                <a href="/register" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs font-semibold transition">
                    <i class="fas fa-user-plus mr-1"></i>Go to Registration Page
                </a>
            </div>
            
            <!-- Login Info Message for Fundraiser -->
            <div id="fundraiserLoginMessage" class="mb-4 p-4 bg-purple-50 border-l-4 border-purple-500 rounded hidden">
                <p class="text-sm text-purple-800 font-semibold mb-2">
                    <i class="fas fa-user-shield mr-2"></i>Account Required
                </p>
                <p class="text-xs text-purple-700">
                    You must <strong>register through the website first</strong> before you can login here. If you don't have an account yet, please visit the registration page to create one.
                </p>
            </div>
            @endif

            <div class="mb-3" id="nameFieldContainer">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-user text-purple-600 mr-2"></i>Full Name
                </label>
                <input type="text" name="name" id="authName" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;">
            </div>

            <div class="mb-3" id="emailField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-envelope text-purple-600 mr-2"></i>Email Address
                </label>
                <input type="email" name="email" id="authEmail" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;" required>
            </div>

            <div class="mb-3" id="passwordField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>Password
                </label>
                <input type="password" name="password" id="authPassword" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;" required>
            </div>

            <!-- Forgot Password UI -->
            <div class="mb-3 hidden" id="forgotPasswordRequestField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-envelope-open-text text-purple-600 mr-2"></i>Enter your email to reset password
                </label>
                <input type="email" name="forgot_email" id="forgotEmail" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;">
                <button type="button" id="forgotPasswordRequestBtn" class="mt-2 bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white px-4 py-2 rounded-lg font-bold w-full text-base shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-paper-plane mr-2"></i>Send Reset Code
                </button>
            </div>

            <div class="mb-3 hidden" id="forgotPasswordVerifyField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-shield-alt text-purple-600 mr-2"></i>Enter the code sent to your email
                </label>
                <input type="text" name="forgot_code" id="forgotCode" maxlength="6" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-center text-xl font-bold tracking-widest" placeholder="000000" style="color: #000 !important;">
                <button type="button" id="forgotPasswordVerifyBtn" class="mt-2 bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white px-4 py-2 rounded-lg font-bold w-full text-base shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-check mr-2"></i>Verify Code
                </button>
            </div>

            <div class="mb-3 hidden" id="forgotPasswordResetField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-key text-purple-600 mr-2"></i>Enter your new password
                </label>
                <input type="password" name="forgot_new_password" id="forgotNewPassword" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;">
                <input type="password" name="forgot_confirm_password" id="forgotConfirmPassword" class="w-full mt-2 px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;" placeholder="Confirm new password">
                <button type="button" id="forgotPasswordResetBtn" class="mt-2 bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white px-4 py-2 rounded-lg font-bold w-full text-base shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Set New Password
                </button>
            </div>

            <div class="mb-3" id="confirmPasswordField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>Confirm Password
                </label>
                <input type="password" name="confirm_password" id="authConfirmPassword" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" style="color: #000 !important;">
            </div>

            <div class="mb-3 hidden" id="verificationField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-shield-alt text-purple-600 mr-2"></i>Verification Code
                </label>
                <input type="text" name="verification_code" id="verificationCode" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-center text-xl font-bold tracking-widest" maxlength="6" placeholder="000000" style="color: #000 !important;">
                <div class="mt-2 p-2 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="text-xs text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>Check your email. Don't forget spam folder!
                    </p>
                </div>
                <div class="text-center mt-2">
                    <button type="button" id="resendCodeBtn" class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                        <i class="fas fa-redo-alt mr-1"></i>Resend Code
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white px-4 py-2 rounded-lg font-bold w-full text-base shadow-lg transition transform hover:scale-105" id="authSubmitBtn">
                    <i class="fas fa-arrow-right mr-2"></i>Continue
                </button>
            </div>
            
            <div class="text-center mb-2">
                <div id="authError" class="text-xs text-red-600 font-semibold mb-1"></div>
                <div id="authSuccess" class="text-xs text-green-600 font-semibold mb-1 hidden"></div>
            </div>
            
            <div class="text-center pt-3 border-t border-gray-200" id="authSwitchLinks">
                <div class="flex flex-col gap-2 items-center">
                    <div class="flex gap-2 justify-center" id="registerLoginLinks">
                        @if(!$isFundraiser)
                        <a href="#" id="switchToRegister" class="text-purple-600 hover:text-purple-800 font-semibold text-sm hover:underline">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                        <span class="text-gray-400">|</span>
                        @endif
                        <a href="#" id="switchToLogin" class="text-purple-600 hover:text-purple-800 font-semibold text-sm hover:underline">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                    </div>
                    <a href="#" id="switchToForgot" class="text-purple-600 hover:text-purple-800 font-semibold text-xs hover:underline mt-1">
                        <i class="fas fa-unlock-alt mr-1"></i>Forgot Password?
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Ticket Auth modal JS - reusable
(function(){
    if (window.ticketAuthModalInitialized) return; // idempotent
    window.ticketAuthModalInitialized = true;

    function openAuthModal() {
        const el = document.getElementById('authModal');
        if (!el) return;
        // Remove tailwind hidden class if present and set inline display for reliability
        el.classList.remove('hidden');
        el.style.display = 'flex';
    }
    function closeAuthModal() {
        const el = document.getElementById('authModal');
        if (!el) return;
        el.classList.add('hidden');
        el.style.display = 'none';
    }
    window.openAuthModal = openAuthModal;
    window.closeAuthModal = closeAuthModal;


    let authMode = 'login';
    let isFundraiserWebsite = {{ $isFundraiser ? 'true' : 'false' }};

    function setAuthMode(mode) {
        authMode = mode;
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        if (authError) authError.textContent = '';
        if (authSuccess) {
            authSuccess.textContent = '';
            authSuccess.classList.add('hidden');
        }

        // Hide all special fields
        document.getElementById('emailField').classList.remove('hidden');
        document.getElementById('verificationField').classList.add('hidden');
        document.getElementById('passwordField').classList.remove('hidden');
        document.getElementById('confirmPasswordField').classList.add('hidden');
        document.getElementById('nameFieldContainer').classList.add('hidden');
        document.getElementById('forgotPasswordRequestField').classList.add('hidden');
        document.getElementById('forgotPasswordVerifyField').classList.add('hidden');
        document.getElementById('forgotPasswordResetField').classList.add('hidden');

        // Hide registration disabled message
        const registrationMessage = document.getElementById('registrationDisabledMessage');
        if (registrationMessage) {
            registrationMessage.classList.add('hidden');
        }
        
        // Hide fundraiser login message
        const fundraiserLoginMessage = document.getElementById('fundraiserLoginMessage');
        if (fundraiserLoginMessage) {
            fundraiserLoginMessage.classList.add('hidden');
        }

        const submitBtn = document.getElementById('authSubmitBtn');

        if (mode === 'register') {
            // If fundraiser type, show registration disabled message instead
            if (isFundraiserWebsite) {
                if (registrationMessage) {
                    registrationMessage.classList.remove('hidden');
                }
                submitBtn.style.display = 'none';
                document.getElementById('registerLoginLinks').style.display = 'none';
                document.getElementById('switchToForgot').style.display = 'none';
                return;
            }
            submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Account';
            document.getElementById('confirmPasswordField').classList.remove('hidden');
            document.getElementById('nameFieldContainer').classList.remove('hidden');
        } else if (mode === 'verify') {
            submitBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Verify Account';
            document.getElementById('verificationField').classList.remove('hidden');
            document.getElementById('passwordField').classList.add('hidden');
            document.getElementById('nameFieldContainer').classList.add('hidden');
        } else if (mode === 'forgot') {
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login';
            document.getElementById('emailField').classList.add('hidden');
            document.getElementById('passwordField').classList.add('hidden');
            document.getElementById('confirmPasswordField').classList.add('hidden');
            document.getElementById('nameFieldContainer').classList.add('hidden');
            document.getElementById('forgotPasswordRequestField').classList.remove('hidden');
        } else {
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login';
            submitBtn.style.display = 'block';
            
            // For fundraiser websites, show the info message and hide register/login links
            if (isFundraiserWebsite) {
                if (fundraiserLoginMessage) {
                    fundraiserLoginMessage.classList.remove('hidden');
                }
                document.getElementById('registerLoginLinks').style.display = 'none';
            } else {
                document.getElementById('registerLoginLinks').style.display = 'flex';
            }
            
            document.getElementById('switchToForgot').style.display = 'block';
        }
    }
    window.setAuthMode = setAuthMode;
    setAuthMode('login');

    // Only attach register listener if the button exists (non-fundraiser websites)
    const switchToRegister = document.getElementById('switchToRegister');
    if (switchToRegister) {
        switchToRegister.addEventListener('click', function(e){ e.preventDefault(); setAuthMode('register'); });
    }
    
    document.getElementById('switchToLogin').addEventListener('click', function(e){ e.preventDefault(); setAuthMode('login'); });

    document.getElementById('switchToForgot').addEventListener('click', function(e){
        e.preventDefault();
        setAuthMode('forgot');
    });

    // Forgot Password Flow
    let forgotEmail = '';
    let forgotCode = '';

    document.getElementById('forgotPasswordRequestBtn').addEventListener('click', async function(e) {
        const email = document.getElementById('forgotEmail').value.trim();
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        if (!email) {
            authError.textContent = 'Please enter your email address.';
            return;
        }
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Sending...';
        try {
            const res = await ajaxPost('/ajax/ticket-auth/forgot-request', { email });
            if (res.success) {
                authSuccess.textContent = 'Reset code sent to ' + email + '. Check your email and spam folder.';
                authSuccess.classList.remove('hidden');
                authError.textContent = '';
                forgotEmail = email;
                document.getElementById('forgotPasswordRequestField').classList.add('hidden');
                document.getElementById('forgotPasswordVerifyField').classList.remove('hidden');
            } else {
                authError.textContent = res.message || 'Failed to send reset code.';
                authSuccess.classList.add('hidden');
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
            authSuccess.classList.add('hidden');
        } finally {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Send Reset Code';
        }
    });

    document.getElementById('forgotPasswordVerifyBtn').addEventListener('click', async function(e) {
        const code = document.getElementById('forgotCode').value.trim();
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        if (!code) {
            authError.textContent = 'Please enter the code sent to your email.';
            return;
        }
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Verifying...';
        try {
            const res = await ajaxPost('/ajax/ticket-auth/forgot-verify', { email: forgotEmail, code });
            if (res.success) {
                authSuccess.textContent = 'Code verified. Please set your new password.';
                authSuccess.classList.remove('hidden');
                authError.textContent = '';
                forgotCode = code;
                document.getElementById('forgotPasswordVerifyField').classList.add('hidden');
                document.getElementById('forgotPasswordResetField').classList.remove('hidden');
            } else {
                authError.textContent = res.message || 'Invalid code.';
                authSuccess.classList.add('hidden');
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
            authSuccess.classList.add('hidden');
        } finally {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-check mr-2"></i>Verify Code';
        }
    });

    document.getElementById('forgotPasswordResetBtn').addEventListener('click', async function(e) {
        const newPassword = document.getElementById('forgotNewPassword').value;
        const confirmPassword = document.getElementById('forgotConfirmPassword').value;
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        if (!newPassword || !confirmPassword) {
            authError.textContent = 'Please enter and confirm your new password.';
            return;
        }
        if (newPassword !== confirmPassword) {
            authError.textContent = 'Passwords do not match.';
            return;
        }
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Saving...';
        try {
            const res = await ajaxPost('/ajax/ticket-auth/forgot-reset', { email: forgotEmail, code: forgotCode, password: newPassword });
            if (res.success) {
                authSuccess.textContent = 'Password reset! You can now log in.';
                authSuccess.classList.remove('hidden');
                authError.textContent = '';
                document.getElementById('forgotPasswordResetField').classList.add('hidden');
                setAuthMode('login');
            } else {
                authError.textContent = res.message || 'Failed to reset password.';
                authSuccess.classList.add('hidden');
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
            authSuccess.classList.add('hidden');
        } finally {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-save mr-2"></i>Set New Password';
        }
    });

    // Resend verification code handler
    document.getElementById('resendCodeBtn').addEventListener('click', async function(e) {
        e.preventDefault();
        const email = document.getElementById('authEmail').value.trim();
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        const btn = e.target.closest('button');
        
        if (!email) {
            authError.textContent = 'Please enter your email address';
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Sending...';
        
        try {
            const res = await ajaxPost('/ajax/ticket-auth/resend-code', { email });
            if (res.success) {
                authSuccess.textContent = 'Verification code resent! Check your email and spam folder.';
                authSuccess.classList.remove('hidden');
                authError.textContent = '';
            } else {
                authError.textContent = res.message || 'Failed to resend code';
                authSuccess.classList.add('hidden');
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
            authSuccess.classList.add('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-redo-alt mr-1"></i>Resend Verification Code';
        }
    });

    async function ajaxPost(url, data) {
        const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : (document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '');
        const resp = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify(data)
        });
        return resp.json();
    }

    document.getElementById('authForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const email = document.getElementById('authEmail').value.trim();
        const password = document.getElementById('authPassword').value;
        const name = document.getElementById('authName').value.trim();
        const code = document.getElementById('verificationCode').value.trim();
        const authError = document.getElementById('authError');
        authError.textContent = '';

        let data = { email };
        let url = '';
        if (authMode === 'register') {
            url = '/ajax/ticket-auth/register';
            data.password = password;
            data.name = name;
            const confirm = document.getElementById('authConfirmPassword').value;
            if (password !== confirm) { authError.textContent = 'Passwords do not match.'; return; }
        } else if (authMode === 'login') {
            url = '/ajax/ticket-auth/login';
            data.password = password;
        } else if (authMode === 'verify') {
            url = '/ajax/ticket-auth/verify';
            data.code = code;
        }

        try {
            const res = await ajaxPost(url, data);
            if (res.success) {
                if (authMode === 'register') {
                    const authSuccess = document.getElementById('authSuccess');
                    const authError = document.getElementById('authError');
                    authSuccess.textContent = '✓ Verification code sent to ' + email + '. Check your email and spam folder.';
                    authSuccess.classList.remove('hidden');
                    authError.textContent = '';
                    setAuthMode('verify');
                    return;
                }
                // Success in verify or login
                closeAuthModal();
                
                // Show success alert
                if (authMode === 'login') {
                    showSuccessAlert('✓ Login Successful! Welcome back.');
                } else if (authMode === 'verify') {
                    showSuccessAlert('✓ Registration Successful! Account verified.');
                }
                
                // Check if there's a checkout redirect URL (from cart page)
                const checkoutRedirectUrl = window.checkoutRedirectUrl || null;
                
                // Check if there's an intended URL to redirect to (from payment pages)
                const intendedUrl = '{{ session("url.intended") }}';
                
                // Check if we're on an investment/fundraiser page via flag set by page-investment.blade.php
                const isInvestmentPage = window._isInvestmentPage === true;
                
                // Redirect after a short delay
                setTimeout(async () => {
                    let redirectUrl = null;
                    
                    // Priority: checkout redirect > intended URL > investment page redirect
                    if (checkoutRedirectUrl) {
                        redirectUrl = checkoutRedirectUrl;
                        window.checkoutRedirectUrl = null; // Clear the flag
                    } else if (intendedUrl && intendedUrl !== '') {
                        redirectUrl = intendedUrl;
                    } else if (isInvestmentPage) {
                        redirectUrl = '/users/profile';
                    }
                    
                    if (redirectUrl) {
                        // Clear the intended URL from session before redirect
                        try {
                            await ajaxPost('/clear-intended-url', {});
                        } catch (e) {
                            console.error('Failed to clear intended URL:', e);
                        }
                        window.location.href = redirectUrl;
                    } else {
                        window.location.reload();
                    }
                }, 1500);
                
                // Check if this is from invest page (data already filled)
                if (window._investmentFormData) {
                    // For invest page, skip investor modal since they already filled the form
                    // Just trigger the submission
                    window.dispatchEvent(new CustomEvent('investorProfileSkipped'));
                    return;
                }
                
                // Check if this is a simple ticket purchase (skip investor modal)
                if (window._isSimpleTicketPurchase) {
                    window._isSimpleTicketPurchase = false; // Reset flag
                    if (window._ticketAuthPendingForm) {
                        const f = window._ticketAuthPendingForm;
                        window._ticketAuthPendingForm = null;
                        
                        // Refresh CSRF token before submission
                        try {
                            const csrfResp = await fetch('/refresh-csrf', {
                                method: 'GET',
                                headers: { 'Accept': 'application/json' }
                            });
                            const csrfData = await csrfResp.json();
                            
                            // Update CSRF token in the form
                            const tokenInput = f.querySelector('input[name="_token"]');
                            if (tokenInput && csrfData.token) {
                                tokenInput.value = csrfData.token;
                            }
                            
                            // Update meta tag too
                            const metaTag = document.querySelector('meta[name="csrf-token"]');
                            if (metaTag && csrfData.token) {
                                metaTag.setAttribute('content', csrfData.token);
                            }
                        } catch (csrfErr) {
                            console.warn('Could not refresh CSRF token:', csrfErr);
                        }
                        
                        // Submit the form
                        f.submit();
                    }
                    return;
                }
                
                // Check if this is an auction bid (skip investor modal)
                if (window._isAuctionBid) {
                    console.log('Auction bid flow - skipping investor modal');
                    window.dispatchEvent(new CustomEvent('authSuccess'));
                    return;
                }
                
                // Check if user has investor profile (only for customer role)
                try {
                    const profileResp = await fetch('/users/investor-profile', {
                        headers: { 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const profileData = await profileResp.json();
                    
                    console.log('Profile data received:', profileData);
                    
                    // Wait a bit to ensure modal is ready
                    setTimeout(() => {
                        // If profile exists, load it into modal; otherwise modal starts empty
                        if (profileData.success && profileData.profile) {
                            if (typeof window.loadInvestorProfile === 'function') {
                                window.loadInvestorProfile(profileData.profile);
                            }
                        }
                        
                        // Show investor info modal for review/edit
                        const modalElement = document.getElementById('investorInfoModal');
                        if (modalElement) {
                            const investorModal = new bootstrap.Modal(modalElement);
                            investorModal.show();
                            console.log('Investor modal displayed successfully');
                        } else {
                            console.error('Investor modal element not found!');
                            // If modal not found, proceed with form submission
                            if (window._ticketAuthPendingForm) {
                                window._ticketAuthPendingForm.submit();
                            }
                        }
                        
                        // Store pending form for later submission
                        window._investorProfilePendingForm = window._ticketAuthPendingForm;
                        window._ticketAuthPendingForm = null;
                    }, 300);
                    
                } catch (profileErr) {
                    console.error('Failed to load investor profile:', profileErr);
                    // If profile check fails, proceed with form submission anyway
                    if (window._ticketAuthPendingForm) {
                        const f = window._ticketAuthPendingForm;
                        window._ticketAuthPendingForm = null;
                        
                        // Fetch a fresh CSRF token
                        try {
                            const csrfResp = await fetch('/refresh-csrf', {
                                method: 'GET',
                                headers: { 'Accept': 'application/json' }
                            });
                            const csrfData = await csrfResp.json();
                            
                            // Update CSRF token in the form
                            const tokenInput = f.querySelector('input[name="_token"]');
                            if (tokenInput && csrfData.token) {
                                tokenInput.value = csrfData.token;
                            }
                            
                            // Update meta tag too
                            const metaTag = document.querySelector('meta[name="csrf-token"]');
                            if (metaTag && csrfData.token) {
                                metaTag.setAttribute('content', csrfData.token);
                            }
                        } catch (err) {
                            console.error('CSRF refresh failed:', err);
                        }
                        
                        // Submit the form
                        f.submit();
                    }
                }
            } else {
                if (res.require_verification) {
                    setAuthMode('verify');
                }
                authError.textContent = res.message || 'An error occurred';
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
        }
    });

    // Intercept forms that submit to /tickets
    document.addEventListener('submit', function(e){
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        const action = form.getAttribute('action');
        if (!action) return;
        if (action.includes('/tickets')) {
            const requiresAuth = form.getAttribute('data-requires-auth');
            if (requiresAuth === '0' || requiresAuth === 'false') {
                return;
            }
            // Check auth status via ajax
            e.preventDefault();
            ajaxPost('/ajax/ticket-auth/check', {}).then(res => {
                if (res.authenticated && res.verified) {
                    form.submit();
                } else {
                    window._ticketAuthPendingForm = form;
                    // Always show login first
                    setAuthMode('login');
                    openAuthModal();
                    // Only switch mode if user is already authenticated but not verified
                    if (res.authenticated && !res.verified) {
                        setTimeout(() => setAuthMode('verify'), 100);
                    }
                }
            }).catch(() => {
                setAuthMode('login');
                openAuthModal();
            });
        }
    }, true);

    // Handle investor profile saved event
    window.addEventListener('investorProfileSaved', async function() {
        if (window._investorProfilePendingForm) {
            const f = window._investorProfilePendingForm;
            window._investorProfilePendingForm = null;
            
            // Fetch a fresh CSRF token
            try {
                const csrfResp = await fetch('/refresh-csrf', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });
                const csrfData = await csrfResp.json();
                
                // Update CSRF token in the form
                const tokenInput = f.querySelector('input[name="_token"]');
                if (tokenInput && csrfData.token) {
                    tokenInput.value = csrfData.token;
                }
                
                // Update meta tag too
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag && csrfData.token) {
                    metaTag.setAttribute('content', csrfData.token);
                }
            } catch (err) {
                console.error('CSRF refresh failed:', err);
            }
            
            // Submit the form
            f.submit();
        }
    });

    // Handle investor profile skipped event
    window.addEventListener('investorProfileSkipped', async function() {
        if (window._investorProfilePendingForm) {
            const f = window._investorProfilePendingForm;
            window._investorProfilePendingForm = null;
            
            // Fetch a fresh CSRF token
            try {
                const csrfResp = await fetch('/refresh-csrf', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });
                const csrfData = await csrfResp.json();
                
                // Update CSRF token in the form
                const tokenInput = f.querySelector('input[name="_token"]');
                if (tokenInput && csrfData.token) {
                    tokenInput.value = csrfData.token;
                }
                
                // Update meta tag too
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag && csrfData.token) {
                    metaTag.setAttribute('content', csrfData.token);
                }
            } catch (err) {
                console.error('CSRF refresh failed:', err);
            }
            
            // Submit the form
            f.submit();
        }
    });

    // Show success alert function
    function showSuccessAlert(message) {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = 'fixed top-4 right-4 z-[9999] bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg p-4 max-w-sm animate-fadeIn';
        alertDiv.style.cssText = `
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            border-radius: 6px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            padding: 16px;
            max-width: 420px;
            animation: slideInRight 0.3s ease-out;
        `;
        
        alertDiv.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                <p class="text-sm font-semibold text-green-800">${message}</p>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            alertDiv.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                alertDiv.remove();
            }, 300);
        }, 4000);
    }
    
    // Add CSS animations if not already present
    if (!document.querySelector('style[data-auth-modal-animations]')) {
        const style = document.createElement('style');
        style.setAttribute('data-auth-modal-animations', 'true');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
})();
</script>
