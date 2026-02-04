@extends('admin.layouts.app')

@section('title', 'Create Banner')
@section('description', 'Create a new banner or popup')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Create Banner</h5>
                <a href="{{ route('admin.marketing.banners') }}" class="btn btn-outline-secondary">
                    <i class="icon-base ri ri-arrow-left-line me-2"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <form id="formBanner" method="POST" action="{{ route('admin.marketing.banners.store') }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required />
                                <label for="title">Title <span class="text-danger">*</span></label>
                            </div>
                            @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                <label for="description">Description</label>
                            </div>
                            @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="image_url" name="image_url" value="{{ old('image_url') }}" required />
                                <label for="image_url">Image URL <span class="text-danger">*</span></label>
                            </div>
                            <small class="text-body-secondary">Enter a full URL (https://example.com/image.jpg) or a path (/storage/gallery/image.jpg or /images/image.jpg)</small>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openImagePicker()">
                                    <i class="ri-image-line me-1"></i>Select from Gallery
                                </button>
                            </div>
                            <div id="image_preview" class="mt-3"></div>
                            @error('image_url')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="url" class="form-control" id="link_url" name="link_url" value="{{ old('link_url') }}" />
                                <label for="link_url">Link URL</label>
                            </div>
                            @error('link_url')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="type" name="type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                                    <option value="popup" {{ old('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                                </select>
                                <label for="type">Type <span class="text-danger">*</span></label>
                            </div>
                            @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="position" name="position" class="form-select" required>
                                    <option value="">Select Position</option>
                                    <option value="header" {{ old('position') == 'header' ? 'selected' : '' }}>Header</option>
                                    <option value="sidebar" {{ old('position') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                    <option value="footer" {{ old('position') == 'footer' ? 'selected' : '' }}>Footer</option>
                                    <option value="popup" {{ old('position') == 'popup' ? 'selected' : '' }}>Popup</option>
                                </select>
                                <label for="position">Position <span class="text-danger">*</span></label>
                            </div>
                            @error('position')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number" min="0" class="form-control" id="display_order" name="display_order" value="{{ old('display_order') }}" />
                                <label for="display_order">Display Order</label>
                            </div>
                            <small class="text-body-secondary">Lower numbers appear first</small>
                            @error('display_order')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="target_audience" name="target_audience" class="form-select" required>
                                    <option value="">Select Target Audience</option>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All Visitors</option>
                                    <option value="logged_in" {{ old('target_audience') == 'logged_in' ? 'selected' : '' }}>Logged In Users</option>
                                    <option value="guests" {{ old('target_audience') == 'guests' ? 'selected' : '' }}>Guests Only</option>
                                </select>
                                <label for="target_audience">Target Audience <span class="text-danger">*</span></label>
                            </div>
                            @error('target_audience')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" />
                                <label for="start_date">Start Date</label>
                            </div>
                            @error('start_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" />
                                <label for="end_date">End Date</label>
                            </div>
                            @error('end_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Create Banner</button>
                                <a href="{{ route('admin.marketing.banners') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image_url').addEventListener('input', function() {
        const url = this.value.trim();
        const preview = document.getElementById('image_preview');
        
        if (url) {
            preview.innerHTML = `
                <div class="border rounded p-2" style="max-width: 300px;">
                    <img src="${url}" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; padding: 20px; text-align: center; color: #999;">
                        <i class="ri-error-warning-line" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Invalid image URL</p>
                    </div>
                </div>
            `;
        } else {
            preview.innerHTML = '';
        }
    });

    // Open image picker modal (if available)
    function openImagePicker() {
        // This would open the gallery modal if integrated
        alert('Image picker integration - You can select images from the gallery here');
    }

    // Form validation
    document.getElementById('formBanner').addEventListener('submit', function(e) {
        const imageUrl = document.getElementById('image_url').value.trim();
        if (!imageUrl) {
            e.preventDefault();
            alert('Please enter an image URL');
            return false;
        }
        
        // Validate URL format - allow full URLs or paths starting with /storage/ or /images/
        const isValidUrl = /^https?:\/\//.test(imageUrl);
        const isValidPath = /^(\/storage\/|\/images\/)/.test(imageUrl);
        
        if (!isValidUrl && !isValidPath) {
            e.preventDefault();
            alert('Please enter a valid URL (http:// or https://) or a path starting with /storage/ or /images/');
            return false;
        }
    });
</script>
@endpush
@endsection
