@php
  // Resolve website by current domain for dynamic branding
  $url = url()->current();
  $domain = parse_url($url, PHP_URL_HOST);
  $website = \App\Models\Website::where('domain', $domain)->first();
  $companyName = null;
  $logoPath = null;
  $primaryColor = '#1773b0';

  if ($website) {
    $setting = \App\Models\Setting::where('user_id', $website->user_id)->first();
    $companyName = $setting?->company_name ?? $website->name;
    $logoPath = $setting?->logo ? asset('/uploads/' . $setting->logo) : null;
    $header = \App\Models\Header::where('website_id', $website->id)->first();
    if ($header && $header->background_color) { $primaryColor = $header->background_color; }
  }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $companyName ? $companyName . ' | Login' : 'Login' }}</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="{{ route('fonts.css') }}" rel="stylesheet">

  <style>
    :root { --brand-color: {{ $primaryColor }}; }
    html, body { height: 100%; }
    body { display: flex; align-items: center; justify-content: center; background: #f5f7fb; }
    .login-wrapper { max-width: 960px; width: 100%; background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; }
    .brand-panel { background: linear-gradient(135deg, var(--brand-color), #4c6fff); color: #fff; padding: 32px; display: flex; flex-direction: column; justify-content: space-between; min-height: 100%; }
    .brand-logo { display: flex; align-items: center; gap: 12px; }
    .brand-logo img { height: 48px; width: auto; border-radius: 8px; background: #fff; padding: 4px; }
    .brand-logo .title { font-weight: 700; letter-spacing: 0.2px; }
    .brand-copy { margin-top: 20px; font-size: 0.95rem; opacity: 0.95; }
    .trust { margin-top: 24px; font-size: 0.85rem; opacity: 0.9; }
    .trust .badge { background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); }
    .form-panel { padding: 32px; }
    .form-panel .title { font-weight: 700; margin-bottom: 8px; color: #111; }
    .form-panel .subtitle { color: #6b7280; margin-bottom: 24px; }
    .form-control { padding: 0.8rem 0.9rem; }
    .btn-brand { background: var(--brand-color); border-color: var(--brand-color); color: #fff; }
    .btn-brand:hover { background: #3371da; color: #000; }
    .helper-links { display: flex; justify-content: space-between; align-items: center; margin-top: 16px; font-size: 0.9rem; }
    .footer-note { margin-top: 24px; font-size: 0.85rem; color: #6b7280; }
    
    /* Loader styles */
    .loader-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center; z-index: 9999; }
    .loader-overlay.active { display: flex; }
    .spinner { border: 4px solid rgba(255, 255, 255, 0.3); border-top: 4px solid #fff; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    
    /* Success notification styles */
    .success-notification { position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; align-items: center; gap: 12px; z-index: 10000; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .success-notification.hide { animation: slideOut 0.3s ease-out forwards; }
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
    
    @media (max-width: 992px) { .brand-panel { display: none; } }
  </style>
</head>

<body>
  <!-- Loader -->
  <div class="loader-overlay" id="loaderOverlay">
    <div class="spinner"></div>
  </div>

  <div class="login-wrapper">
    <div class="row g-0">
      <div class="col-lg-5 brand-panel">
        <div>
          <div class="brand-logo">
            @if($logoPath)
              <img src="{{ $logoPath }}" alt="{{ $companyName ?? 'Brand' }} Logo">
            @else
              <span class="fs-3 fw-bold">{{ $companyName ?? 'Welcome' }}</span>
            @endif
            @if($companyName)
              <span class="title">{{ $companyName }}</span>
            @endif
          </div>
          <div class="brand-copy">
            <p class="mb-2">Secure access to your account.</p>
            <p class="mb-0">Use your email and password to sign in. Your data is protected using industry-standard encryption.</p>
          </div>
          <div class="trust">
            <div class="d-flex gap-2 flex-wrap">
              <span class="badge rounded-pill text-bg-light">SSL Secured</span>
              <span class="badge rounded-pill text-bg-light">Encrypted</span>
              <span class="badge rounded-pill text-bg-light">Safe & Secure</span>
            </div>
          </div>
        </div>
        <div class="mt-4 small">Need help? Contact support or your site administrator.</div>
      </div>
      <div class="col-lg-7">
        <div class="form-panel">
          <h1 class="title">Sign in</h1>
          <p class="subtitle">To access your dashboard.</p>
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          <form method="POST" action="/login" novalidate>
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Show password"><i class="fa fa-eye"></i></button>
              </div>
            </div>
            <button type="submit" class="btn btn-brand btn-lg w-100">Login</button>
            <div class="helper-links">
              <a href="#" id="forgotLink">Forgot your password?</a>
              {{-- <span class="text-muted">No registration on this page</span> --}}
            </div>
          </form>
          <div id="forgotCard" class="card mt-3 d-none">
            <div class="card-body">
              <h5 class="card-title">Reset your password</h5>
              <p class="text-muted mb-3">Enter your email to receive a verification code.</p>
              <div id="forgotStep1">
                <div class="mb-3">
                  <label for="forgotEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="forgotEmail" placeholder="you@example.com">
                </div>
                <button class="btn btn-brand" id="forgotRequestBtn">Send code</button>
              </div>
              <div id="forgotStep2" class="d-none">
                <div class="mb-3">
                  <label for="forgotCode" class="form-label">Verification code</label>
                  <input type="text" class="form-control" id="forgotCode" placeholder="Enter code">
                </div>
                <div class="mb-3">
                  <label for="newPassword" class="form-label">New password</label>
                  <input type="password" class="form-control" id="newPassword" placeholder="New password">
                </div>
                <button class="btn btn-brand" id="forgotResetBtn">Reset password</button>
              </div>
              <div id="forgotAlert" class="mt-3"></div>
            </div>
          </div>
          <div class="footer-note">By continuing, you agree to the site's Terms and Privacy Policy.</div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const loaderOverlay = document.getElementById('loaderOverlay');
    
    togglePassword?.addEventListener('click', () => {
      const isText = passwordInput.type === 'text';
      passwordInput.type = isText ? 'password' : 'text';
      togglePassword.innerHTML = isText ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
    });
    
    function showLoader() {
      loaderOverlay.classList.add('active');
    }
    
    function hideLoader() {
      loaderOverlay.classList.remove('active');
    }
    
    function showSuccessNotification(message = 'Success!') {
      const notification = document.createElement('div');
      notification.className = 'success-notification';
      notification.innerHTML = `
        <i class="fa fa-check-circle"></i>
        <span>${message}</span>
      `;
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.classList.add('hide');
        setTimeout(() => notification.remove(), 300);
      }, 3000);
    }
    
    const forgotLink = document.getElementById('forgotLink');
    const forgotCard = document.getElementById('forgotCard');
    const forgotStep1 = document.getElementById('forgotStep1');
    const forgotStep2 = document.getElementById('forgotStep2');
    const forgotAlert = document.getElementById('forgotAlert');
    const forgotEmail = document.getElementById('forgotEmail');
    const forgotCode = document.getElementById('forgotCode');
    const newPassword = document.getElementById('newPassword');
    const csrfToken = document.querySelector('meta[name=csrf-token]')?.content;
    
    function showAlert(type, message) { forgotAlert.innerHTML = `<div class="alert alert-${type}">${message}</div>`; }
    
    forgotLink?.addEventListener('click', (e) => { 
      e.preventDefault(); 
      forgotCard.classList.toggle('d-none'); 
      forgotAlert.innerHTML = ''; 
    });
    
    document.getElementById('forgotRequestBtn')?.addEventListener('click', async () => {
      const email = (forgotEmail.value || '').trim();
      if (!email) { showAlert('warning', 'Please enter your email.'); return; }
      
      showLoader();
      try {
        const res = await fetch('/ajax/ticket-auth/forgot-request', { 
          method: 'POST', 
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
          body: JSON.stringify({ email }) 
        });
        const data = await res.json();
        hideLoader();
        
        if (data.success) { 
          showAlert('success', 'Verification code sent to your email.'); 
          forgotStep1.classList.add('d-none'); 
          forgotStep2.classList.remove('d-none'); 
        }
        else { showAlert('danger', data.message || 'Failed to send code.'); }
      } catch (e) { 
        hideLoader();
        showAlert('danger', 'Network error. Please try again.'); 
      }
    });
    
    document.getElementById('forgotResetBtn')?.addEventListener('click', async () => {
      const email = (forgotEmail.value || '').trim();
      const code = (forgotCode.value || '').trim();
      const password = (newPassword.value || '').trim();
      if (!email || !code || !password) { showAlert('warning', 'Please fill all fields.'); return; }
      
      showLoader();
      try {
        const res = await fetch('/ajax/ticket-auth/forgot-reset', { 
          method: 'POST', 
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
          body: JSON.stringify({ email, code, password }) 
        });
        const data = await res.json();
        hideLoader();
        
        if (data.success) { 
          showSuccessNotification('Password reset successful!');
          showAlert('success', 'Password reset successful. You can now log in.'); 
          setTimeout(() => {
            forgotCard.classList.add('d-none');
            forgotStep1.classList.remove('d-none');
            forgotStep2.classList.add('d-none');
            forgotEmail.value = '';
            forgotCode.value = '';
            newPassword.value = '';
            forgotAlert.innerHTML = '';
          }, 2000);
        }
        else { showAlert('danger', data.message || 'Failed to reset password.'); }
      } catch (e) { 
        hideLoader();
        showAlert('danger', 'Network error. Please try again.'); 
      }
    });
  </script>
</body>
</html>
