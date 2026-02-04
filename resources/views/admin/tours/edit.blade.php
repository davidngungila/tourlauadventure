@extends('admin.layouts.app')

@section('title', 'Edit Tour - Lau Paradise Adventures')
@section('description', 'Edit tour details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Tour: {{ $tour->name }}
                    </h4>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Tours
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tour Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $tour->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Destination <span class="text-danger">*</span></label>
                                <select name="destination_id" class="form-select @error('destination_id') is-invalid @enderror" required>
                                    <option value="">Select Destination</option>
                                    @foreach($destinations ?? [] as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id', $tour->destination_id) == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $tour->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Excerpt</label>
                                <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="2" maxlength="500">{{ old('excerpt', $tour->excerpt) }}</textarea>
                                <small class="text-muted">Brief summary (max 500 characters)</small>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days', $tour->duration_days) }}" min="1" required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $tour->price) }}" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rating</label>
                                <input type="number" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating', $tour->rating) }}" step="0.1" min="0" max="5">
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fitness Level</label>
                                <select name="fitness_level" class="form-select @error('fitness_level') is-invalid @enderror">
                                    <option value="">Select Level</option>
                                    <option value="Easy" {{ old('fitness_level', $tour->fitness_level) == 'Easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="Moderate" {{ old('fitness_level', $tour->fitness_level) == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="Challenging" {{ old('fitness_level', $tour->fitness_level) == 'Challenging' ? 'selected' : '' }}>Challenging</option>
                                    <option value="Strenuous" {{ old('fitness_level', $tour->fitness_level) == 'Strenuous' ? 'selected' : '' }}>Strenuous</option>
                                </select>
                                @error('fitness_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image URL</label>
                                <div class="input-group">
                                    <input type="text" name="image_url" id="image_url" class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url', $tour->image_url) }}" placeholder="images/tours/image.jpg or https://example.com/image.jpg" onchange="updateImagePreview()">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imageBrowserModal" onclick="setImageTarget('image_url')">
                                        <i class="ri-folder-open-line me-1"></i>Browse
                                    </button>
                                </div>
                                <small class="text-muted">Enter full URL (http://...) or relative path (images/...). Click Browse to select from local files or Cloudinary.</small>
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview" class="mt-2">
                                    @php
                                        $currentImageUrl = $tour->image_url 
                                            ? (str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') 
                                                ? $tour->image_url 
                                                : asset($tour->image_url))
                                            : null;
                                    @endphp
                                    @if($currentImageUrl)
                                    <img src="{{ $currentImageUrl }}" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 200px; object-fit: cover;" onerror="this.style.display='none';">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $tour->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured Tour
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Update Tour
                            </button>
                            <a href="{{ route('admin.tours.index') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Image Browser Modal -->
<div class="modal fade" id="imageBrowserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Browse Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Source Tabs -->
                <ul class="nav nav-tabs mb-3" id="imageSourceTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#localImages" type="button">
                            <i class="ri-folder-line me-1"></i>Local Files
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cloudinaryImages" type="button" onclick="loadCloudinaryImages()">
                            <i class="ri-cloud-line me-1"></i>Cloudinary
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Local Images Tab -->
                    <div class="tab-pane fade show active" id="localImages">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="image_search" placeholder="Search images..." onkeyup="filterImages()">
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="image_folder" onchange="loadImages()">
                                    <option value="images">All Images</option>
                                    <option value="images/hero-slider">Hero Slider</option>
                                    <option value="images/tours">Tours</option>
                                </select>
                            </div>
                        </div>
                        <div id="images_grid" class="row g-3" style="max-height: 400px; overflow-y: auto;">
                            <!-- Images will be loaded here -->
                        </div>
                    </div>
                    
                    <!-- Cloudinary Images Tab -->
                    <div class="tab-pane fade" id="cloudinaryImages">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="cloudinary_search" placeholder="Search Cloudinary..." onkeyup="filterCloudinaryImages()">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="cloudinary_folder" onchange="loadCloudinaryImages()">
                                    <option value="">All Folders</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-warning w-100" onclick="loadCloudinaryImages()">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </div>
                        </div>
                        <div id="cloudinary_grid" class="row g-3" style="max-height: 400px; overflow-y: auto;">
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">Click tab to load Cloudinary images</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="selectImage()">Select</button>
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentImageTarget = null;
let selectedPublicImages = [];

// Image browser for public/images
document.getElementById('imageBrowserModal')?.addEventListener('show.bs.modal', function() {
    loadImages();
});

function setImageTarget(targetId) {
    currentImageTarget = targetId;
    selectedPublicImages = [];
}

function loadImages() {
    const grid = document.getElementById('images_grid');
    const folder = document.getElementById('image_folder')?.value || 'images';
    
    grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Loading images...</p></div>';
    
    fetch(`/admin/homepage/destinations/get-images?folder=${encodeURIComponent(folder)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        grid.innerHTML = '';
        if (data.images && data.images.length > 0) {
            data.images.forEach(image => {
                const isSelected = selectedPublicImages.includes(image.path);
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6';
                col.innerHTML = `
                    <div class="card h-100 ${isSelected ? 'border-primary border-2' : ''}" style="cursor: pointer;" onclick="togglePublicImage('${image.path.replace(/'/g, "\\'")}')">
                        <div class="position-relative">
                            <img src="${image.url}" class="card-img-top" style="height: 150px; object-fit: cover;" loading="lazy">
                            ${isSelected ? '<span class="badge bg-primary position-absolute top-0 end-0 m-1"><i class="ri-check-line"></i></span>' : ''}
                        </div>
                        <div class="card-body p-2">
                            <p class="card-text small mb-0 text-truncate" title="${image.name}">${image.name}</p>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        } else {
            grid.innerHTML = '<div class="col-12 text-center py-4"><p class="text-muted">No images found in this folder</p></div>';
        }
    })
    .catch(error => {
        console.error('Error loading images:', error);
        grid.innerHTML = '<div class="col-12 text-center py-4"><p class="text-danger">Error loading images</p></div>';
    });
}

function filterImages() {
    const search = document.getElementById('image_search')?.value.toLowerCase() || '';
    const cards = document.querySelectorAll('#images_grid .card');
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.closest('.col-md-3').style.display = text.includes(search) ? '' : 'none';
    });
}

function togglePublicImage(imagePath) {
    selectedPublicImages = [imagePath];
    if (currentImageTarget) {
        document.getElementById(currentImageTarget).value = imagePath;
        updateImagePreview();
    }
}

function selectImage() {
    if (selectedPublicImages.length > 0 && currentImageTarget) {
        document.getElementById(currentImageTarget).value = selectedPublicImages[0];
        updateImagePreview();
    }
    bootstrap.Modal.getInstance(document.getElementById('imageBrowserModal')).hide();
}

// ========== CLOUDINARY INTEGRATION ==========
function loadCloudinaryFolders() {
    fetch('{{ route("admin.cloudinary.folders") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.folders) {
            const select = document.getElementById('cloudinary_folder');
            data.folders.forEach(folder => {
                const option = document.createElement('option');
                option.value = folder.path;
                option.textContent = folder.name;
                select.appendChild(option);
            });
        }
    })
    .catch(err => console.log('Could not load Cloudinary folders'));
}

function loadCloudinaryImages() {
    const grid = document.getElementById('cloudinary_grid');
    const folder = document.getElementById('cloudinary_folder')?.value || '';
    
    grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-warning"></div><p class="mt-2">Loading Cloudinary images...</p></div>';
    
    const params = new URLSearchParams({ max_results: 100 });
    if (folder) params.append('folder', folder);
    
    fetch(`{{ route("admin.cloudinary.assets") }}?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        grid.innerHTML = '';
        
        if (!data.success) {
            grid.innerHTML = `<div class="col-12 text-center py-5"><p class="text-danger">${data.message || 'Failed to load'}</p><small>Make sure CLOUDINARY_URL is set in .env</small></div>`;
            return;
        }
        
        if (!data.resources || data.resources.length === 0) {
            grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No images found in Cloudinary</p></div>';
            return;
        }
        
        data.resources.forEach(asset => {
            const col = document.createElement('div');
            col.className = 'col-md-3 col-sm-4 col-6 cloudinary-img-item';
            col.setAttribute('data-name', asset.filename.toLowerCase());
            col.innerHTML = `
                <div class="card h-100" style="cursor: pointer;" onclick="selectCloudinaryImage('${asset.url.replace(/'/g, "\\'")}')">
                    <div class="position-relative">
                        <img src="${asset.url}" class="card-img-top" style="height: 150px; object-fit: cover;" loading="lazy">
                        <span class="badge bg-warning position-absolute bottom-0 start-0 m-1" style="font-size: 0.65rem;">
                            <i class="ri-cloud-line"></i>
                        </span>
                    </div>
                    <div class="card-body p-2">
                        <p class="card-text small mb-0 text-truncate" title="${asset.filename}">${asset.filename}</p>
                        <small class="text-muted">${asset.width || '?'}×${asset.height || '?'}</small>
                    </div>
                </div>
            `;
            grid.appendChild(col);
        });
    })
    .catch(error => {
        console.error('Error loading Cloudinary images:', error);
        grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Error loading Cloudinary images</p></div>';
    });
}

function filterCloudinaryImages() {
    const search = document.getElementById('cloudinary_search')?.value.toLowerCase() || '';
    document.querySelectorAll('#cloudinary_grid .cloudinary-img-item').forEach(item => {
        const name = item.getAttribute('data-name') || '';
        item.style.display = name.includes(search) ? '' : 'none';
    });
}

function selectCloudinaryImage(url) {
    selectedPublicImages = [url];
    if (currentImageTarget) {
        document.getElementById(currentImageTarget).value = url;
        updateImagePreview();
    }
    bootstrap.Modal.getInstance(document.getElementById('imageBrowserModal')).hide();
}

function updateImagePreview() {
    const preview = document.getElementById('imagePreview');
    const imageUrlInput = document.getElementById('image_url');
    const imageUrl = imageUrlInput.value;
    
    if (imageUrl) {
        const displayUrl = imageUrl.startsWith('http://') || imageUrl.startsWith('https://') 
            ? imageUrl 
            : (imageUrl.startsWith('/') ? imageUrl : '/' + imageUrl);
        preview.innerHTML = `<img src="${displayUrl}" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 200px; object-fit: cover;" onerror="this.style.display='none';">`;
    } else {
        preview.innerHTML = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCloudinaryFolders();
});
</script>
@endpush



