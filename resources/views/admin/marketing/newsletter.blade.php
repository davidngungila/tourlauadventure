@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')
@section('description', 'Manage newsletter subscribers captured from the website')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-mail-line me-2"></i>Newsletter Subscribers
                        </h4>
                        <p class="text-muted mb-0">Manage subscribers captured from website forms</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.marketing.newsletter.export') }}" class="btn btn-outline-primary">
                            <i class="ri-download-line me-1"></i>Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="ri-user-check-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Verified Subscribers</div>
                                <h5 class="mb-0">{{ number_format($totalSubscribers) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded">
                                    <i class="ri-user-unfollow-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Unverified Subscribers</div>
                                <h5 class="mb-0">{{ number_format($unverifiedSubscribers) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="ri-mail-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Total Subscribers</div>
                                <h5 class="mb-0">{{ number_format($subscribers->total()) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.marketing.newsletter') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Subscribers</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified Only</option>
                                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified Only</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.marketing.newsletter') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subscribers List</h5>
                </div>
                <div class="card-body">
                    @if($subscribers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Subscribed At</th>
                                    <th>Verified At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscribers as $subscriber)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-initial bg-label-primary rounded">
                                                    <i class="ri-mail-line"></i>
                                                </div>
                                            </div>
                                            <strong>{{ $subscriber->email }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subscriber->verified_at)
                                            <span class="badge bg-label-success">
                                                <i class="ri-checkbox-circle-line me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-label-warning">
                                                <i class="ri-time-line me-1"></i>Pending Verification
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($subscriber->verified_at)
                                            {{ $subscriber->verified_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Not verified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSubscriber({{ $subscriber->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $subscribers->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <div class="avatar-initial bg-label-secondary rounded">
                                <i class="ri-mail-line icon-48px"></i>
                            </div>
                        </div>
                        <h5>No Subscribers Found</h5>
                        <p class="text-muted">No newsletter subscribers match your search criteria.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSubscriberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Subscriber</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this subscriber? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSubscriberForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteSubscriber(id) {
    if (confirm('Are you sure you want to delete this subscriber?')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("admin/marketing/newsletter") }}/' + id;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Append to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

