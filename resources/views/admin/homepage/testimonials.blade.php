@extends('admin.layouts.app')

@section('title', 'Testimonials - Lau Paradise Adventures')
@section('description', 'Manage testimonials')

@php
use Illuminate\Support\Str;
@endphp

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

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-star-smile-line me-2"></i>Testimonials & Reviews
                        </h4>
                        <p class="text-muted mb-0 small">Manage customer reviews from Google, TripAdvisor, and your website</p>
                    </div>
                    <a href="{{ route('admin.homepage.testimonials.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add Testimonial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.homepage.testimonials') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select">
                            <option value="">All Sources</option>
                            <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="google" {{ request('source') == 'google' ? 'selected' : '' }}>Google</option>
                            <option value="tripadvisor" {{ request('source') == 'tripadvisor' ? 'selected' : '' }}>TripAdvisor</option>
                            <option value="facebook" {{ request('source') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Featured</label>
                        <select name="featured" class="form-select">
                            <option value="">All</option>
                            <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search testimonials..." value="{{ request('search') }}">
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

    <!-- Testimonials Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="150">Author</th>
                            <th>Content</th>
                            <th width="100">Rating</th>
                            <th width="120">Source</th>
                            <th width="150">Tour</th>
                            <th width="120">Status</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($testimonial->author_image_url)
                                    <img src="{{ str_starts_with($testimonial->author_image_url, 'http') ? $testimonial->author_image_url : asset($testimonial->author_image_url) }}" 
                                         alt="{{ $testimonial->author_name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;"
                                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\'%3E%3Ccircle fill=\'%23ddd\' cx=\'20\' cy=\'20\' r=\'20\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\' font-size=\'12\'%3E{{ substr($testimonial->author_name, 0, 1) }}%3C/text%3E%3C/svg%3E'">
                                    @else
                                    <div class="rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <span class="text-muted">{{ substr($testimonial->author_name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $testimonial->author_name }}</strong>
                                        @if($testimonial->author_title)
                                            <br><small class="text-muted">{{ $testimonial->author_title }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0">{{ Str::limit($testimonial->content, 100) }}</p>
                                @if($testimonial->review_date)
                                <small class="text-muted">
                                    <i class="ri-calendar-line me-1"></i>{{ $testimonial->review_date->format('M d, Y') }}
                                </small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="ri-star-{{ $i < $testimonial->rating ? 'fill' : 'line' }} text-warning"></i>
                                    @endfor
                                    <span class="ms-1 small text-muted">({{ $testimonial->rating }})</span>
                                </div>
                            </td>
                            <td>
                                @if($testimonial->source == 'google')
                                    <span class="badge bg-label-danger">
                                        <i class="ri-google-fill me-1"></i>Google
                                    </span>
                                @elseif($testimonial->source == 'tripadvisor')
                                    <span class="badge bg-label-success">
                                        <i class="ri-global-line me-1"></i>TripAdvisor
                                    </span>
                                @elseif($testimonial->source == 'facebook')
                                    <span class="badge bg-label-primary">
                                        <i class="ri-facebook-fill me-1"></i>Facebook
                                    </span>
                                @elseif($testimonial->source == 'other')
                                    <span class="badge bg-label-secondary">Other</span>
                                @else
                                    <span class="badge bg-label-info">Website</span>
                                @endif
                                @if($testimonial->is_verified)
                                <br><small class="text-success"><i class="ri-verified-badge-line"></i> Verified</small>
                                @endif
                            </td>
                            <td>
                                @if($testimonial->tour)
                                    <span class="badge bg-label-info">{{ Str::limit($testimonial->tour->name, 20) }}</span>
                                @else
                                    <span class="text-muted">General</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($testimonial->is_approved)
                                        <span class="badge bg-label-success"><i class="ri-check-line me-1"></i>Approved</span>
                                    @else
                                        <span class="badge bg-label-warning"><i class="ri-time-line me-1"></i>Pending</span>
                                    @endif
                                    @if($testimonial->is_featured)
                                        <span class="badge bg-label-primary"><i class="ri-star-line me-1"></i>Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.homepage.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    @if($testimonial->review_url)
                                    <a href="{{ $testimonial->review_url }}" target="_blank" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip" title="View Original Review">
                                        <i class="ri-external-link-line"></i>
                                    </a>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-icon btn-danger delete-testimonial" data-id="{{ $testimonial->id }}" data-name="{{ $testimonial->author_name }}" data-bs-toggle="modal" data-bs-target="#deleteTestimonialModal" data-bs-tooltip title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ri-star-smile-line" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-2">No testimonials found</p>
                                    <p class="small">Start building trust by adding customer reviews from Google, TripAdvisor, or your website</p>
                                    <a href="{{ route('admin.homepage.testimonials.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="ri-add-line me-1"></i>Add First Testimonial
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete testimonial from <strong id="deleteTestimonialName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteTestimonialForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Testimonial</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Delete testimonial modal
    $('.delete-testimonial').on('click', function() {
        const testimonialId = $(this).data('id');
        const testimonialName = $(this).data('name');
        $('#deleteTestimonialName').text(testimonialName);
        $('#deleteTestimonialForm').attr('action', '{{ route("admin.homepage.testimonials.destroy", ":id") }}'.replace(':id', testimonialId));
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection
