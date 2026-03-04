@extends('user.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 15px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        object-fit: cover;
        background: white;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    }
    
    .info-label {
        font-weight: 600;
        color: #666;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #333;
        font-weight: 500;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #667eea;
        display: inline-block;
    }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/users">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/users/student">
                                <i class="fas fa-users"></i> My Participants
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Student Profile</li>
                    </ol>
                </nav>

                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                            @if($user->photo)
                                <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" class="profile-avatar">
                            @else
                                <div class="profile-avatar d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <i class="fas fa-user fa-4x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md">
                            <h2 class="mb-2">{{ $user->name }} {{ $user->last_name }}</h2>
                            <p class="mb-2">
                                <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-globe me-2"></i>{{ $user->website->name ?? 'N/A' }}
                            </p>
                            <div class="mt-3">
                                @if($user->status == 1)
                                    <span class="status-badge bg-success text-white">
                                        <i class="fas fa-check-circle me-1"></i> Approved
                                    </span>
                                @else
                                    <span class="status-badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i> Pending Approval
                                    </span>
                                @endif
                                
                                <span class="status-badge bg-primary text-white ms-2">
                                    <i class="fas fa-user-tag me-1"></i> {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-auto text-center text-md-end mt-3 mt-md-0">
                            <a href="/users/student" class="btn btn-light btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to My Participants
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="section-title">Personal Information</h4>
                        
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-id-badge text-primary"></i> User ID
                            </div>
                            <div class="info-value">#{{ $user->id }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-user text-primary"></i> Full Name
                            </div>
                            <div class="info-value">{{ $user->name }} {{ $user->last_name }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-envelope text-primary"></i> Email Address
                            </div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>

                        @if($user->teacher)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-users-class text-primary"></i> Team
                            </div>
                            <div class="info-value">{{ $user->teacher->name }}</div>
                        </div>
                        @endif

                        @if($user->parent)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-user-friends text-primary"></i> Parent
                            </div>
                            <div class="info-value">
                                <a href="{{ route('admin.user.profile', $user->parent->id) }}" class="text-decoration-none">
                                    {{ $user->parent->name }} {{ $user->parent->last_name }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(($user->role === 'student' || $user->role === 'parents') && $user->donations->count() > 0)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-hand-holding-heart text-success"></i> Amount Raised
                            </div>
                            <div class="info-value">
                                ${{ number_format($user->donations->sum('amount') + $user->donations->sum('tip_amount'), 2) }}
                            </div>
                            <small class="text-muted">Total from {{ $user->donations->count() }} donation(s)</small>
                        </div>
                        @endif

                        @if($user->role === 'parents' && $user->children->count() > 0)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-users text-primary"></i> Children
                            </div>
                            <div class="info-value">
                                @foreach($user->children as $child)
                                    <div class="mb-2">
                                        <a href="{{ route('admin.user.profile', $child->id) }}" class="text-decoration-none">
                                            {{ $child->name }} {{ $child->last_name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h4 class="section-title">Additional Details</h4>

                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-globe text-success"></i> Website
                            </div>
                            <div class="info-value">{{ $user->website->name ?? 'N/A' }}</div>
                            @if($user->website)
                            <small class="text-muted">{{ $user->website->domain }}</small>
                            @endif
                        </div>

                        @if($user->goal)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-bullseye text-success"></i> Goal
                            </div>
                            <div class="info-value">${{ number_format($user->goal, 2) }}</div>
                        </div>
                        @endif

                        @if($user->size)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-ruler text-success"></i> Size
                            </div>
                            <div class="info-value">{{ $user->size }}</div>
                        </div>
                        @endif

                        @if($user->grade)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-graduation-cap text-success"></i> Grade
                            </div>
                            <div class="info-value">{{ $user->grade }}</div>
                        </div>
                        @endif

                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus text-success"></i> Registered On
                            </div>
                            <div class="info-value">{{ $user->created_at->format('F d, Y h:i A') }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-clock text-success"></i> Last Updated
                            </div>
                            <div class="info-value">{{ $user->updated_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
