@extends('admin.layouts.app')

@section('title', 'Add Testimonial - Lau Paradise Adventures')
@section('description', 'Add a new testimonial')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-add-line me-2"></i>Add Testimonial
                    </h4>
                    <a href="{{ route('admin.homepage.testimonials') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Testimonials
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.testimonials.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Author Name <span class="text-danger">*</span></label>
                                <input type="text" name="author_name" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name') }}" required>
                                @error('author_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author Title</label>
                                <input type="text" name="author_title" class="form-control @error('author_title') is-invalid @enderror" value="{{ old('author_title') }}" placeholder="e.g., Travel Enthusiast">
                                @error('author_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author Image URL</label>
                                <div class="input-group">
                                    <input type="text" name="author_image_url" class="form-control @error('author_image_url') is-invalid @enderror" value="{{ old('author_image_url') }}" placeholder="/storage/gallery/image.jpg or https://example.com/avatar.jpg">
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearAuthorImage()" title="Clear">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Enter a full URL or a path starting with /storage/ or /images/</small>
                                <div id="author_image_preview" class="mt-2"></div>
                                @error('author_image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rating <span class="text-danger">*</span></label>
                                <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                    <option value="5" {{ old('rating', '5') == '5' ? 'selected' : '' }}>5 Stars ⭐⭐⭐⭐⭐</option>
                                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars ⭐⭐⭐⭐</option>
                                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars ⭐⭐⭐</option>
                                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars ⭐⭐</option>
                                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star ⭐</option>
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Review Source <span class="text-danger">*</span></label>
                                <select name="source" class="form-select @error('source') is-invalid @enderror" required>
                                    <option value="website" {{ old('source', 'website') == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="google" {{ old('source') == 'google' ? 'selected' : '' }}>Google Reviews</option>
                                    <option value="tripadvisor" {{ old('source') == 'tripadvisor' ? 'selected' : '' }}>TripAdvisor</option>
                                    <option value="facebook" {{ old('source') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <small class="text-muted">Select where this review came from</small>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Review URL</label>
                                <input type="url" name="review_url" class="form-control @error('review_url') is-invalid @enderror" value="{{ old('review_url') }}" placeholder="https://www.google.com/maps/reviews/... or https://www.tripadvisor.com/...">
                                <small class="text-muted">Link to the original review (optional)</small>
                                @error('review_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Review Date</label>
                                <input type="date" name="review_date" class="form-control @error('review_date') is-invalid @enderror" value="{{ old('review_date') }}">
                                <small class="text-muted">Date when the review was posted</small>
                                @error('review_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Tour</label>
                                <select name="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">No Tour (General)</option>
                                    @foreach($tours ?? [] as $tour)
                                        <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                                            {{ $tour->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tour_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order') }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Testimonial Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_approved" id="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_approved">
                                                <i class="ri-check-line me-1"></i>Approved
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                <i class="ri-star-line me-1"></i>Featured
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">
                                                <i class="ri-verified-badge-line me-1"></i>Verified Review
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Add Testimonial
                            </button>
                            <a href="{{ route('admin.homepage.testimonials') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Author image preview
    document.querySelector('input[name="author_image_url"]').addEventListener('input', function() {
        const url = this.value.trim();
        const preview = document.getElementById('author_image_preview');
        if (url) {
            const fullUrl = url.startsWith('http') ? url : '{{ asset("") }}' + url;
            preview.innerHTML = `
                <div class="border rounded p-2" style="max-width: 150px;">
                    <img src="${fullUrl}" alt="Preview" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; padding: 20px; text-align: center; color: #999;">
                        <i class="ri-error-warning-line" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2 small">Invalid image URL</p>
                    </div>
                </div>
            `;
        } else {
            preview.innerHTML = '';
        }
    });

    function clearAuthorImage() {
        document.querySelector('input[name="author_image_url"]').value = '';
        document.getElementById('author_image_preview').innerHTML = '';
    }
</script>
@endpush
@endsection



