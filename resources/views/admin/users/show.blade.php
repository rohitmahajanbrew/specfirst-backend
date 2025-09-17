@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details: ' . $user->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Name</label>
                            <div class="fw-bold">{{ $user->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <div>{{ $user->full_name ?: 'Not provided' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <div>{{ $user->email }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <div>{{ $user->phone_number ?: 'Not provided' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Company</label>
                            <div>{{ $user->company_name ?: 'Not provided' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Role</label>
                            <div>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'vendor' ? 'warning' : 'primary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <div>
                                <span class="badge bg-{{ $user->onboarding_completed ? 'success' : 'secondary' }}">
                                    {{ $user->onboarding_completed ? 'Active' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Device Type</label>
                            <div>{{ ucfirst($user->device_type) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Types -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Preferred Project Types
                </h5>
            </div>
            <div class="card-body">
                @if($user->preferred_project_types && count($user->preferred_project_types) > 0)
                    <div class="row">
                        @foreach($user->preferred_project_types as $typeSlug)
                            @php
                                $projectType = $projectTypes->firstWhere('slug', $typeSlug);
                            @endphp
                            @if($projectType)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="{{ $projectType->icon }} fa-2x text-{{ $projectType->color }} mb-2"></i>
                                            <h6 class="card-title">{{ $projectType->name }}</h6>
                                            <p class="card-text small text-muted">{{ $projectType->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-project-diagram fa-3x mb-3"></i>
                        <div>No preferred project types selected</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Onboarding Progress -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tasks me-2"></i>Onboarding Progress
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Completed Step</label>
                            <div>
                                @if($user->completed_step)
                                    <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $user->completed_step)) }}</span>
                                @else
                                    <span class="text-muted">Not started</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Next Step</label>
                            <div>
                                @if($user->next_step)
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $user->next_step)) }}</span>
                                @else
                                    <span class="text-muted">Completed</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress mb-3" style="height: 8px;">
                    @php
                        $steps = ['profile_setup', 'project_type_selection', 'onboarding_complete', 'vendor_verification'];
                        $currentStep = $user->completed_step;
                        $progress = 0;
                        if ($currentStep) {
                            $stepIndex = array_search($currentStep, $steps);
                            $progress = $stepIndex !== false ? (($stepIndex + 1) / count($steps)) * 100 : 0;
                        }
                    @endphp
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                </div>

                <div class="row">
                    @foreach($steps as $index => $step)
                        <div class="col-3 text-center">
                            <div class="step-indicator {{ $user->completed_step === $step ? 'completed' : ($user->next_step === $step ? 'current' : 'pending') }}">
                                <i class="fas fa-{{ $user->completed_step === $step ? 'check' : 'circle' }}"></i>
                                <div class="small mt-1">{{ ucfirst(str_replace('_', ' ', $step)) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- User Avatar & Quick Info -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="d-grid gap-2">
                    {{-- <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </a> --}}
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Account Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $user->created_at->format('d') }}</h4>
                            <small class="text-muted">{{ $user->created_at->format('M Y') }}</small>
                            <div class="small">Joined</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $user->last_login_at ? $user->last_login_at->format('d') : 'N/A' }}</h4>
                        <small class="text-muted">{{ $user->last_login_at ? $user->last_login_at->format('M Y') : 'Never' }}</small>
                        <div class="small">Last Login</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Account Details
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Email Verified:</strong>
                        <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }} ms-2">
                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                        </span>
                    </li>
                    <li class="mb-2">
                        <strong>Onboarding:</strong>
                        <span class="badge bg-{{ $user->onboarding_completed ? 'success' : 'secondary' }} ms-2">
                            {{ $user->onboarding_completed ? 'Complete' : 'Incomplete' }}
                        </span>
                    </li>
                    <li class="mb-2">
                        <strong>Device Type:</strong>
                        <span class="badge bg-info ms-2">{{ ucfirst($user->device_type) }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Created:</strong>
                        <div class="small text-muted">{{ $user->created_at->format('M d, Y H:i') }}</div>
                    </li>
                    <li class="mb-2">
                        <strong>Updated:</strong>
                        <div class="small text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- <!-- Danger Zone -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">These actions are permanent and cannot be undone.</p>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        <i class="fas fa-trash me-2"></i>Delete User
                    </button>
                </form>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@section('styles')
<style>
.step-indicator {
    padding: 1rem 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.step-indicator.completed {
    background-color: #d1fae5;
    color: #065f46;
}

.step-indicator.current {
    background-color: #dbeafe;
    color: #1e40af;
}

.step-indicator.pending {
    background-color: #f3f4f6;
    color: #6b7280;
}

</style>
@endsection
