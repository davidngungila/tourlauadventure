@extends('admin.layouts.app')

@section('title', 'Edit User')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/@form-validation/form-validation.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- User Info & Stats -->
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 3rem;">
                                {{ $user->initials }}
                            </span>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-label-info">{{ $role->name }}</span>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-label-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                            {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Bookings</span>
                        <strong>{{ $stats['bookings'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Spent</span>
                        <strong>${{ number_format($stats['total_spent'] ?? 0, 2) }}</strong>
                    </div>
                    @if($stats['last_booking'] ?? null)
                    <div class="d-flex justify-content-between">
                        <span>Last Booking</span>
                        <strong>{{ $stats['last_booking']->created_at->format('M d, Y') }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="resetPassword()">
                            <i class="ri-lock-password-line me-1"></i>Reset Password
                        </button>
                        <button type="button" class="btn btn-outline-{{ $user->email_verified_at ? 'warning' : 'success' }}" onclick="toggleStatus()">
                            <i class="ri-{{ $user->email_verified_at ? 'user-unfollow' : 'user-follow' }}-line me-1"></i>
                            {{ $user->email_verified_at ? 'Deactivate' : 'Activate' }} User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-user-settings-line me-2"></i>Edit User Information
                    </h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="editUserForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-user-line me-2"></i>Personal Information</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $user->mobile) }}">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Avatar</label>
                                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->avatar)
                                    <small class="text-muted">Current: <a href="{{ asset('storage/' . $user->avatar) }}" target="_blank">View</a></small>
                                @endif
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-map-pin-line me-2"></i>Address Information</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $user->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $user->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-file-text-line me-2"></i>Additional Information</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Roles & Permissions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-shield-user-line me-2"></i>Roles & Permissions</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Roles <span class="text-danger">*</span></label>
                                <select name="roles[]" class="form-select select2 @error('roles') is-invalid @enderror" multiple required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if($role->permissions->count() > 0)
                                                <small class="text-muted">({{ $role->permissions->count() }} permissions)</small>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Additional Permissions</label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($permissions as $module => $perms)
                                        <div class="mb-3">
                                            <strong class="d-block mb-2">{{ $module }}</strong>
                                            <div class="row">
                                                @foreach($perms as $permission)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" {{ $user->hasPermissionTo($permission) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-settings-3-line me-2"></i>Account Settings</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_verified" id="email_verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        Email Verified
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update User
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Password Reset Modal -->
<div class="modal fade" id="passwordResetModal" tabindex="-1" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordResetModalLabel">
                    <i class="ri-lock-password-line me-2"></i>Reset User Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordResetForm">
                    @csrf
                    <div class="mb-3">
                        <label for="resetPassword" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="resetPassword" name="password" required minlength="8">
                        <div class="form-text">Minimum 8 characters</div>
                    </div>
                    <div class="mb-3">
                        <label for="resetPasswordConfirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="resetPasswordConfirmation" name="password_confirmation" required minlength="8">
                        <div class="form-text">Re-enter the new password</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitPasswordReset()">
                    <i class="ri-lock-password-line me-1"></i>Reset Password
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/assets/vendor/libs/@form-validation/popular.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/select2/select2.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select roles...',
        allowClear: true
    });
});

function resetPassword() {
    // Show password reset modal
    const modal = new bootstrap.Modal(document.getElementById('passwordResetModal'));
    modal.show();
}

function submitPasswordReset() {
    const form = document.getElementById('passwordResetForm');
    const formData = new FormData(form);
    
    // Validate passwords match
    const password = formData.get('password');
    const passwordConfirmation = formData.get('password_confirmation');
    
    if (password.length < 8) {
        showErrorToast('Password must be at least 8 characters long');
        return;
    }
    
    if (password !== passwordConfirmation) {
        showErrorToast('Passwords do not match');
        return;
    }
    
    // Submit via AJAX
    fetch('{{ route("admin.users.reset-password", $user->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message);
            bootstrap.Modal.getInstance(document.getElementById('passwordResetModal')).hide();
            // Clear form
            form.reset();
        } else {
            showErrorToast(data.message || 'Password reset failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('An error occurred while resetting password');
    });
}

function showErrorToast(message) {
    // Show error toast (implement or use existing toast system)
    if (typeof showSuccessToast === 'function') {
        // If toast system exists, use it
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    } else {
        alert(message);
    }
}

function showSuccessToast(message) {
    // Show success toast (implement or use existing toast system)
    if (typeof showSuccessToast === 'function' && showSuccessToast.toString().includes('showSuccessToast')) {
        showSuccessToast(message);
    } else {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}

function toggleStatus() {
    // Implement status toggle via AJAX
    alert('Status toggle functionality to be implemented');
}
</script>
@endpush
