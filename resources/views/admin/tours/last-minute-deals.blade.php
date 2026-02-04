@extends('admin.layouts.app')

@section('title', 'Last Minute Deals - Lau Paradise Adventures')
@section('description', 'Manage last-minute deals')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-time-line me-2"></i>Last Minute Deals
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tours.last-minute-deals.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add New Deal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Deals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['active'] ?? 0) }}</h5>
                            <small class="text-muted">Active Deals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-time-expired-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['expired'] ?? 0) }}</h5>
                            <small class="text-muted">Expired Deals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-percent-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['avg_discount'] ?? 0, 1) }}%</h5>
                            <small class="text-muted">Avg. Discount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tours.last-minute-deals') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Destination</label>
                        <select name="destination_id" class="form-select">
                            <option value="">All Destinations</option>
                            @foreach($destinations ?? [] as $dest)
                                <option value="{{ $dest->id }}" {{ request('destination_id') == $dest->id ? 'selected' : '' }}>
                                    {{ $dest->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Deals</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired Only</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search tours..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form id="bulkActionForm" method="POST" action="{{ route('admin.tours.last-minute-deals.bulk-action') }}">
        @csrf
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <select name="action" class="form-select" id="bulkActionSelect" required>
                            <option value="">Select Action</option>
                            <option value="remove">Remove from Deals</option>
                            <option value="extend">Extend Expiry</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="extendDaysContainer" style="display: none;">
                        <input type="number" name="extend_days" class="form-select" placeholder="Days to extend" min="1" max="365">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-warning" id="bulkActionBtn" disabled>
                            <i class="ri-play-line me-1"></i>Apply to Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tours Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Tour Name</th>
                                <th>Destination</th>
                                <th>Original Price</th>
                                <th>Discount</th>
                                <th>Deal Price</th>
                                <th>Expires At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tours as $tour)
                            <tr class="{{ $tour->last_minute_deal_expires_at && $tour->last_minute_deal_expires_at < now() ? 'table-secondary' : '' }}">
                                <td>
                                    <input type="checkbox" name="tour_ids[]" value="{{ $tour->id }}" class="form-check-input tour-checkbox">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($tour->image_url)
                                        <img src="{{ asset($tour->image_url) }}" alt="{{ $tour->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $tour->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $tour->tour_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $tour->destination->name ?? 'N/A' }}</td>
                                <td>
                                    <strong>${{ number_format($tour->last_minute_original_price ?? $tour->price, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ number_format($tour->last_minute_discount_percentage ?? 0, 1) }}% OFF
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($tour->starting_price ?? $tour->price, 2) }}</strong>
                                </td>
                                <td>
                                    @if($tour->last_minute_deal_expires_at)
                                        <div>
                                            {{ $tour->last_minute_deal_expires_at->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">{{ $tour->last_minute_deal_expires_at->format('h:i A') }}</small>
                                        </div>
                                        @if($tour->last_minute_deal_expires_at < now())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($tour->last_minute_deal_expires_at->diffInDays(now()) <= 3)
                                            <span class="badge bg-warning">Expiring Soon</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No expiry</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tour->last_minute_deal_expires_at && $tour->last_minute_deal_expires_at < now())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($tour->status === 'active' && $tour->publish_status === 'published')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.tours.last-minute-deals.edit', $tour->id) }}" class="btn btn-sm btn-primary">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.tours.last-minute-deals.destroy', $tour->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this tour from last-minute deals?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line" style="font-size: 3rem;"></i>
                                        <p class="mt-3">No last-minute deals found</p>
                                        <a href="{{ route('admin.tours.last-minute-deals.create') }}" class="btn btn-primary mt-2">
                                            <i class="ri-add-line me-1"></i>Create First Deal
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($tours->hasPages())
                <div class="mt-4">
                    {{ $tours->links() }}
                </div>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.tour-checkbox');
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const extendDaysContainer = document.getElementById('extendDaysContainer');
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActionButton();
    });
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            selectAll.checked = Array.from(checkboxes).every(c => c.checked);
            updateBulkActionButton();
        });
    });
    
    bulkActionSelect.addEventListener('change', function() {
        if (this.value === 'extend') {
            extendDaysContainer.style.display = 'block';
        } else {
            extendDaysContainer.style.display = 'none';
        }
        updateBulkActionButton();
    });
    
    function updateBulkActionButton() {
        const checked = Array.from(checkboxes).some(cb => cb.checked);
        bulkActionBtn.disabled = !checked || !bulkActionSelect.value;
    }
    
    // Form submission
    document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
        if (!bulkActionSelect.value) {
            e.preventDefault();
            alert('Please select an action');
            return false;
        }
        
        if (bulkActionSelect.value === 'extend' && !document.querySelector('[name="extend_days"]').value) {
            e.preventDefault();
            alert('Please enter number of days to extend');
            return false;
        }
        
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one tour');
            return false;
        }
        
        return confirm(`Are you sure you want to ${bulkActionSelect.value} for ${checked.length} tour(s)?`);
    });
});
</script>
@endsection












