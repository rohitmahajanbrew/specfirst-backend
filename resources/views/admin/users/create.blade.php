@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" name="full_name" value="{{ old('full_name') }}">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="passwordConfirmationToggleIcon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                   id="company_name" name="company_name" value="{{ old('company_name') }}">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="vendor" {{ old('role') === 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="preferred_project_types" class="form-label">Preferred Project Types</label>
                            <select class="form-select @error('preferred_project_types') is-invalid @enderror" 
                                    id="preferred_project_types" name="preferred_project_types[]" multiple>
                                @foreach($projectTypes as $type)
                                <option value="{{ $type->slug }}" 
                                        {{ in_array($type->slug, old('preferred_project_types', [])) ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
                            @error('preferred_project_types')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="device_type" class="form-label">Device Type</label>
                            <select class="form-select @error('device_type') is-invalid @enderror" id="device_type" name="device_type">
                                <option value="web" {{ old('device_type') === 'web' ? 'selected' : '' }}>Web</option>
                                <option value="mobile" {{ old('device_type') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="tablet" {{ old('device_type') === 'tablet' ? 'selected' : '' }}>Tablet</option>
                            </select>
                            @error('device_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="onboarding_completed" 
                                       name="onboarding_completed" value="1" {{ old('onboarding_completed') ? 'checked' : '' }}>
                                <label class="form-check-label" for="onboarding_completed">
                                    Onboarding Completed
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_verified" 
                                       name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">
                                    Email Verified
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="completed_step" class="form-label">Completed Step</label>
                            <select class="form-select @error('completed_step') is-invalid @enderror" id="completed_step" name="completed_step">
                                <option value="">Select Step</option>
                                <option value="profile_setup" {{ old('completed_step') === 'profile_setup' ? 'selected' : '' }}>Profile Setup</option>
                                <option value="project_type_selection" {{ old('completed_step') === 'project_type_selection' ? 'selected' : '' }}>Project Type Selection</option>
                                <option value="onboarding_complete" {{ old('completed_step') === 'onboarding_complete' ? 'selected' : '' }}>Onboarding Complete</option>
                                <option value="vendor_verification" {{ old('completed_step') === 'vendor_verification' ? 'selected' : '' }}>Vendor Verification</option>
                            </select>
                            @error('completed_step')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="next_step" class="form-label">Next Step</label>
                            <select class="form-select @error('next_step') is-invalid @enderror" id="next_step" name="next_step">
                                <option value="">Select Step</option>
                                <option value="project_type_selection" {{ old('next_step') === 'project_type_selection' ? 'selected' : '' }}>Project Type Selection</option>
                                <option value="project_creation" {{ old('next_step') === 'project_creation' ? 'selected' : '' }}>Project Creation</option>
                                <option value="portfolio_setup" {{ old('next_step') === 'portfolio_setup' ? 'selected' : '' }}>Portfolio Setup</option>
                            </select>
                            @error('next_step')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Help
                </h5>
            </div>
            <div class="card-body">
                <h6>User Roles:</h6>
                <ul class="list-unstyled">
                    <li><span class="badge bg-primary me-2">User</span> Regular platform user</li>
                    <li><span class="badge bg-danger me-2">Admin</span> Full system access</li>
                    <li><span class="badge bg-warning me-2">Vendor</span> Service provider</li>
                </ul>

                <h6 class="mt-3">Password Requirements:</h6>
                <ul class="list-unstyled small">
                    <li>• Minimum 8 characters</li>
                    <li>• At least one uppercase letter</li>
                    <li>• At least one lowercase letter</li>
                    <li>• At least one number</li>
                </ul>

                <h6 class="mt-3">Onboarding Steps:</h6>
                <ul class="list-unstyled small">
                    <li>• Profile Setup</li>
                    <li>• Project Type Selection</li>
                    <li>• Onboarding Complete</li>
                    <li>• Vendor Verification (for vendors)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + 'ToggleIcon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

$(document).ready(function() {
    // Form validation
    $('#createUserForm').on('submit', function(e) {
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
            return false;
        }
    });

    // Auto-generate password
    $('#generatePassword').click(function() {
        const password = generatePassword();
        $('#password').val(password);
        $('#password_confirmation').val(password);
    });

    function generatePassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }
});
</script>
@endsection
