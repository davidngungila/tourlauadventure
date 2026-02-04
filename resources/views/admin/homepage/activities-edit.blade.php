@extends('admin.layouts.app')

@section('title', 'Edit Activity - Lau Paradise Adventures')
@section('description', 'Edit activity')

@push('styles')
<style>
    .image-preview {
        max-width: 200px;
        max-height: 150px;
        margin-top: 10px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-pencil-line me-2"></i>Edit Activity
                    </h4>
                    <a href="{{ route('admin.homepage.activities') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Activities
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.activities.update', $activity->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-information-line me-2"></i>Basic Information</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Activity Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $activity->name) }}" placeholder="e.g., Wildlife Safari" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Icon Class (Font Awesome)</label>
                                <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $activity->icon) }}" placeholder="e.g., fas fa-binoculars">
                                <small class="text-muted">Font Awesome icon class (e.g., fas fa-binoculars, fas fa-mountain)</small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="e.g., Game drives, Big Five sightings, and incredible wildlife encounters">{{ old('description', $activity->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-image-line me-2"></i>Image</h5>
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>Note:</strong> Select an image from the gallery or provide a direct image URL.
                                </div>
                            </div>
                            
                            <!-- Current Image Preview -->
                            @if($activity->display_image_url)
                            <div class="col-12 mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="{{ $activity->display_image_url }}" alt="{{ $activity->name }}" class="image-preview img-thumbnail">
                                </div>
                            </div>
                            @endif
                            
                            <!-- Gallery Image Picker -->
                            <div class="col-md-6 mb-3">
                                @include('admin.partials.image-picker', [
                                    'name' => 'image_id',
                                    'label' => 'Image from Gallery',
                                    'value' => old('image_id', $activity->image_id)
                                ])
                            </div>
                            
                            <!-- Direct Image URL -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Or Image URL</label>
                                <input type="text" name="image_url" id="image_url" class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url', $activity->image_url) }}" placeholder="images/activity.jpg or full URL">
                                <small class="text-muted">Path from public/ directory or full URL</small>
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="image_url_preview" class="mt-2">
                                    @if(old('image_url', $activity->image_url))
                                    @php
                                        $imgUrl = old('image_url', $activity->image_url);
                                        $fullUrl = str_starts_with($imgUrl, 'http') ? $imgUrl : asset($imgUrl);
                                    @endphp
                                    <img src="{{ $fullUrl }}" alt="Preview" class="image-preview img-thumbnail">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Display Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="ri-settings-line me-2"></i>Display Settings</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $activity->display_order) }}" min="0">
                                <small class="text-muted">Lower numbers appear first.</small>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $activity->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Display on homepage)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update Activity
                                </button>
                                <a href="{{ route('admin.homepage.activities') }}" class="btn btn-label-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image URL when typed
    document.getElementById('image_url')?.addEventListener('input', function() {
        const url = this.value;
        const preview = document.getElementById('image_url_preview');
        if (url) {
            const fullUrl = url.startsWith('http') ? url : '{{ asset("") }}' + url;
            preview.innerHTML = `<img src="${fullUrl}" alt="Preview" class="image-preview img-thumbnail" onerror="this.parentElement.innerHTML='<p class=\"text-muted small\">Invalid image URL</p>'">`;
        } else {
            preview.innerHTML = '';
        }
    });
</script>
@endpush
@endsection












