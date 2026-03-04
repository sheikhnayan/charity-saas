@extends('admin.main')

@section('content')
<div class="container py-4">
    <h2>Website Email Settings</h2>
    <p class="text-muted">Configure SMTP settings for {{ $website->name }} ({{ $website->domain }})</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ $settings && $settings->exists ? route('admin.website.email.update', ['website' => $website->id]) : route('admin.website.email.store', ['website' => $website->id]) }}">
        @csrf
        @if($settings && $settings->exists)
            @method('PUT')
        @endif
        <div class="card mb-3">
            <div class="card-header">SMTP Server</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mailer</label>
                        <select name="mailer" class="form-select">
                            <option value="smtp" {{ old('mailer', $settings->mailer) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Host</label>
                        <input type="text" name="host" class="form-control" value="{{ old('host', $settings->host) }}" placeholder="smtp.example.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Port</label>
                        <input type="number" name="port" class="form-control" value="{{ old('port', $settings->port ?? 587) }}" placeholder="587">
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label">Encryption</label>
                        <select name="encryption" class="form-select">
                            <option value="" {{ old('encryption', $settings->encryption) === null ? 'selected' : '' }}>None</option>
                            <option value="tls" {{ old('encryption', $settings->encryption) === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('encryption', $settings->encryption) === 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $settings->username) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password', $settings->password) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Sender & Reply-To</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">From Address</label>
                        <input type="email" name="from_address" class="form-control" value="{{ old('from_address', $settings->from_address) }}" placeholder="noreply@{{ $website->domain }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Name</label>
                        <input type="text" name="from_name" class="form-control" value="{{ old('from_name', $settings->from_name ?? $website->name) }}" placeholder="{{ $website->name }}">
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Reply-To Address</label>
                        <input type="email" name="reply_to_address" class="form-control" value="{{ old('reply_to_address', $settings->reply_to_address) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reply-To Name</label>
                        <input type="text" name="reply_to_name" class="form-control" value="{{ old('reply_to_name', $settings->reply_to_name) }}">
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheckbox" {{ old('is_active', $settings->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActiveCheckbox">
                        Use these settings for this website (active)
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Settings</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
