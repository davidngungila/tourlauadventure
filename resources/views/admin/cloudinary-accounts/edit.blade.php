@extends('admin.layouts.app')

@section('title', 'Edit Cloudinary Account')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-cloud-line me-2"></i>Edit Cloudinary Account
                    </h4>
                    <p class="text-muted mb-0">Update Cloudinary account configuration</p>
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
                    <form method="POST" action="{{ route('admin.cloudinary-accounts.update', $account->id) }}" id="cloudinaryAccountForm">
                        @csrf
                        @method('PUT')
                        
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
                                <input type="text" class="form-control" name="name" value="{{ old('name', $account->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cloud Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cloud_name" value="{{ old('cloud_name', $account->cloud_name) }}" required>
                                @error('cloud_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="api_key" value="{{ old('api_key', $account->api_key) }}" required>
                                @error('api_key')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">API Secret <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="api_secret" id="api_secret" 
                                           value="{{ old('api_secret', $account->api_secret) }}" required>
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
                                       value="{{ old('cloudinary_url', $account->cloudinary_url) }}">
                                <small class="text-muted">Full Cloudinary URL. If provided, individual credentials above are optional.</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2">{{ old('description', $account->description) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                                    <input type="hidden" name="is_active" value="0">
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1"
                                           {{ old('is_default', $account->is_default) ? 'checked' : '' }}>
                                    <input type="hidden" name="is_default" value="0">
                                    <label class="form-check-label" for="is_default">
                                        Set as Default
                                    </label>
                                </div>
                            </div>

                            @if($account->last_connection_test)
                            <div class="col-md-12">
                                <div class="alert {{ $account->connection_status ? 'alert-success' : 'alert-danger' }}">
                                    <strong>Last Connection Test:</strong> {{ $account->last_connection_test->format('Y-m-d H:i:s') }}
                                    <br>
                                    <strong>Status:</strong> {{ $account->connection_status ? 'Connected' : 'Failed' }}
                                    @if($account->connection_error)
                                        <br><strong>Error:</strong> {{ $account->connection_error }}
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Update Account
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="testConnection({{ $account->id }})">
                                <i class="ri-refresh-line me-1"></i>Test Connection
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

function testConnection(accountId) {
    if (!confirm('Test connection with current credentials?')) {
        return;
    }

    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Testing...';

    fetch(`{{ url('admin/cloudinary-accounts') }}/${accountId}/test-connection`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof toastr !== 'undefined') {
                toastr.success(data.message || 'Connection successful');
            } else {
                alert(data.message || 'Connection successful');
            }
            setTimeout(() => location.reload(), 1500);
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error(data.message || 'Connection failed');
            } else {
                alert(data.message || 'Connection failed');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error testing connection');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    });
}

document.getElementById('cloudinaryAccountForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
});
</script>
@endpush
@endsection


