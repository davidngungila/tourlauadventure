@extends('admin.layouts.app')

@section('title', 'Cloudinary Accounts Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-cloud-line me-2"></i>Cloudinary Accounts
                    </h4>
                    <p class="text-muted mb-0">Manage multiple Cloudinary accounts and test connections</p>
                </div>
                <a href="{{ route('admin.cloudinary-accounts.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i>Add Account
                </a>
            </div>
        </div>
    </div>

    <!-- Accounts List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($accounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Cloud Name</th>
                                        <th>Status</th>
                                        <th>Connection</th>
                                        <th>Default</th>
                                        <th>Last Test</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial bg-label-primary rounded">
                                                        <i class="ri-cloud-line"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong>{{ $account->name }}</strong>
                                                    @if($account->description)
                                                        <br><small class="text-muted">{{ Str::limit($account->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $account->cloud_name }}</code>
                                        </td>
                                        <td>
                                            @if($account->is_active)
                                                <span class="badge bg-label-success">Active</span>
                                            @else
                                                <span class="badge bg-label-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->connection_status)
                                                <span class="badge bg-label-success">
                                                    <i class="ri-check-line me-1"></i>Connected
                                                </span>
                                            @else
                                                <span class="badge bg-label-danger">
                                                    <i class="ri-close-line me-1"></i>Failed
                                                </span>
                                                @if($account->connection_error)
                                                    <br><small class="text-danger" title="{{ $account->connection_error }}">
                                                        {{ Str::limit($account->connection_error, 30) }}
                                                    </small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->is_default)
                                                <span class="badge bg-label-primary">Default</span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="setDefaultAccount({{ $account->id }})">
                                                    Set Default
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->last_connection_test)
                                                <small class="text-muted">
                                                    {{ $account->last_connection_test->diffForHumans() }}
                                                </small>
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        onclick="testConnection({{ $account->id }})" title="Test Connection">
                                                    <i class="ri-refresh-line"></i>
                                                </button>
                                                <a href="{{ route('admin.cloudinary-accounts.edit', $account->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form action="{{ route('admin.cloudinary-accounts.destroy', $account->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this account?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-cloud-line" style="font-size: 4rem; color: #d0d0d0;"></i>
                            <h5 class="mt-3">No Cloudinary Accounts</h5>
                            <p class="text-muted">Get started by adding your first Cloudinary account</p>
                            <a href="{{ route('admin.cloudinary-accounts.create') }}" class="btn btn-primary mt-2">
                                <i class="ri-add-line me-1"></i>Add Account
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testConnection(accountId) {
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

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
            setTimeout(() => location.reload(), 1000);
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

function setDefaultAccount(accountId) {
    if (!confirm('Set this account as the default Cloudinary account?')) {
        return;
    }

    fetch(`{{ url('admin/cloudinary-accounts') }}/${accountId}/set-default`, {
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
                toastr.success(data.message || 'Default account updated');
            } else {
                alert(data.message || 'Default account updated');
            }
            setTimeout(() => location.reload(), 1000);
        } else {
            alert(data.message || 'Failed to update default account');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating default account');
    });
}
</script>
@endpush
@endsection


