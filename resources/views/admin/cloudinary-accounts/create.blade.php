@extends('admin.layouts.app')

@section('title', 'Add Cloudinary Account')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-cloud-line me-2"></i>Add Cloudinary Account
                    </h4>
                    <p class="text-muted mb-0">Configure a new Cloudinary account connection</p>
                </div>
                <a href="{{ route('admin.cloudinary-accounts.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.cloudinary-accounts.store') }}" id="cloudinaryAccountForm">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>Note:</strong> You can find your Cloudinary credentials in your 
                                    <a href="https://console.cloudinary.com/settings/api-keys" target="_blank">Cloudinary Dashboard</a>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required 
                                       placeholder="e.g., Production Account, Staging Account">
                                <small class="text-muted">A friendly name to identify this account</small>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cloud Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cloud_name" value="{{ old('cloud_name') }}" required 
                                       placeholder="your-cloud-name">
                                @error('cloud_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="api_key" value="{{ old('api_key') }}" required>
                                @error('api_key')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">API Secret <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="api_secret" id="api_secret" 
                                           value="{{ old('api_secret') }}" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('api_secret')">
                                        <i class="ri-eye-line" id="api_secret_icon"></i>
                                    </button>
                                </div>
                                @error('api_secret')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Cloudinary URL (Optional)</label>
                                <input type="text" class="form-control" name="cloudinary_url" 
                                       value="{{ old('cloudinary_url') }}" 
                                       placeholder="cloudinary://api_key:api_secret@cloud_name">
                                <small class="text-muted">Full Cloudinary URL. If provided, individual credentials above are optional.</small>
                                @error('cloudinary_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2" 
                                          placeholder="Optional description for this account">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                                <small class="text-muted">Only active accounts can be used</small>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" 
                                           {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        Set as Default
                                    </label>
                                </div>
                                <small class="text-muted">Default account will be used when no account is specified</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Account
                            </button>
                            <a href="{{ route('admin.cloudinary-accounts.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ri-eye-line');
        icon.classList.add('ri-eye-off-line');
    } else {
        input.type = 'password';
        icon.classList.remove('ri-eye-off-line');
        icon.classList.add('ri-eye-line');
    }
}

document.getElementById('cloudinaryAccountForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
});
</script>
@endpush
@endsection

