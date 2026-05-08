<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select Website</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f5f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .selector-card {
            max-width: 760px;
            width: 100%;
            border: none;
            border-radius: 14px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .account-option {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 10px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .account-option:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.12);
        }

        .account-meta {
            color: #6b7280;
            font-size: 0.92rem;
        }
    </style>
</head>
<body>
<div class="card selector-card">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-2">Select Website To Continue</h2>
        <p class="text-muted mb-4">
            This email is linked to multiple websites. Choose the website account you want to access.
            @if(!empty($email))
                <br><strong>{{ $email }}</strong>
            @endif
        </p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.select.website.submit') }}">
            @csrf

            @foreach($accounts as $index => $account)
                <label class="account-option d-block" for="account_{{ $account->id }}">
                    <div class="form-check mb-0">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="user_id"
                            id="account_{{ $account->id }}"
                            value="{{ $account->id }}"
                            {{ $index === 0 ? 'checked' : '' }}
                            required
                        >
                        <div class="ms-1">
                            <div class="fw-semibold">
                                {{ $account->website->name ?? ('Website #' . ($account->website_id ?? 'N/A')) }}
                            </div>
                            <div class="account-meta">
                                Domain: {{ $account->website->domain ?? 'N/A' }} | Role: {{ ucfirst($account->role ?? 'user') }}
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach

            @error('user_id')
                <div class="text-danger small mb-3">{{ $message }}</div>
            @enderror

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Continue</button>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back To Login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
