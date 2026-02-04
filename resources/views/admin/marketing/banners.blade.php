@extends('admin.layouts.app')

@section('title', 'Banners & Popups')
@section('description', 'Manage website banners and popups')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    .banner-preview {
        max-width: 200px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }
    .banner-card {
        transition: all 0.3s;
    }
    .banner-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="ri-error-warning-line me-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Banners & Popups</h5>
                    <p class="text-muted mb-0 small">Manage website banners and popup advertisements</p>
                </div>
                <a href="{{ route('admin.marketing.banners.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-2"></i>Create Banner
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.marketing.banners') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                                <option value="popup" {{ request('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="position" class="form-select">
                                <option value="">All Positions</option>
                                <option value="header" {{ request('position') == 'header' ? 'selected' : '' }}>Header</option>
                                <option value="sidebar" {{ request('position') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                <option value="footer" {{ request('position') == 'footer' ? 'selected' : '' }}>Footer</option>
                                <option value="popup" {{ request('position') == 'popup' ? 'selected' : '' }}>Popup</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">Preview</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Display Order</th>
                                <th>Valid Period</th>
                                <th>Target Audience</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $banner)
                            <tr class="banner-card">
                                <td>
                                    @if($banner->image_url)
                                    @php
                                        $imageUrl = $banner->image_url;
                                        if (str_starts_with($imageUrl, '/storage/') || str_starts_with($imageUrl, '/images/')) {
                                            $imageUrl = asset($imageUrl);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $banner->title }}" class="banner-preview" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'100\'%3E%3Crect fill=\'%23ddd\' width=\'200\' height=\'100\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                                    @else
                                    <div class="banner-preview bg-light d-flex align-items-center justify-content-center">
                                        <i class="ri-image-line text-muted"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $banner->title }}</strong>
                                    @if($banner->description)
                                    <br><small class="text-muted">{{ Str::limit($banner->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->type == 'banner')
                                        <span class="badge bg-label-primary"><i class="ri-image-line me-1"></i>Banner</span>
                                    @else
                                        <span class="badge bg-label-info"><i class="ri-window-line me-1"></i>Popup</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary">{{ ucfirst($banner->position) }}</span>
                                </td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge bg-label-success"><i class="ri-eye-line me-1"></i>Active</span>
                                    @else
                                        <span class="badge bg-label-secondary"><i class="ri-eye-off-line me-1"></i>Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $banner->display_order }}</span>
                                </td>
                                <td>
                                    @if($banner->start_date && $banner->end_date)
                                        <small>
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ $banner->start_date->format('M d') }} - {{ $banner->end_date->format('M d, Y') }}
                                        </small>
                                        @if($banner->end_date->isPast())
                                            <br><span class="badge bg-label-danger">Expired</span>
                                        @elseif($banner->start_date->isFuture())
                                            <br><span class="badge bg-label-warning">Scheduled</span>
                                        @endif
                                    @else
                                        <span class="badge bg-label-success">Always Active</span>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->target_audience == 'all')
                                        <span class="badge bg-label-primary">All Visitors</span>
                                    @elseif($banner->target_audience == 'logged_in')
                                        <span class="badge bg-label-info">Logged In</span>
                                    @else
                                        <span class="badge bg-label-warning">Guests Only</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.marketing.banners.edit', $banner->id) }}" class="btn btn-sm btn-icon btn-primary" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.marketing.banners.toggle', $banner->id) }}" method="POST" class="toggle-form d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon {{ $banner->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="{{ $banner->is_active ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.marketing.banners.destroy', $banner->id) }}" method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this banner? This action cannot be undone.')">
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
                                        <i class="ri-image-line" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-2">No banners found</p>
                                        <p class="small">Create your first banner or popup to get started</p>
                                        <a href="{{ route('admin.marketing.banners.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="ri-add-line me-2"></i>Create Your First Banner
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($banners->hasPages())
                <div class="mt-4">
                    {{ $banners->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Handle toggle form submission with confirmation
        document.querySelectorAll('.toggle-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const button = form.querySelector('button[type="submit"]');
                const isActive = button.classList.contains('btn-warning');
                
                if (confirm(isActive ? 'Are you sure you want to deactivate this banner?' : 'Are you sure you want to activate this banner?')) {
                    form.submit();
                }
            });
        });

        // Handle delete form with better confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this banner? This action cannot be undone.')) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
