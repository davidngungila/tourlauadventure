@extends('admin.layouts.app')

@section('title', 'Gallery Management - Lau Paradise Adventures')

@push('styles')
<style>
    .gallery-simple-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .gallery-item-simple {
        position: relative;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .gallery-item-simple:hover {
        border-color: #3ea572;
        box-shadow: 0 4px 12px rgba(62, 165, 114, 0.15);
        transform: translateY(-2px);
    }
    .gallery-item-simple img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        display: block;
    }
    .gallery-item-info {
        padding: 0.75rem;
        background: #fff;
    }
    .gallery-item-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .gallery-item-meta {
        font-size: 0.75rem;
        color: #666;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .gallery-item-actions {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        display: flex;
        gap: 0.25rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .gallery-item-simple:hover .gallery-item-actions {
        opacity: 1;
    }
    .gallery-item-checkbox {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .gallery-item-simple:hover .gallery-item-checkbox,
    .gallery-item-simple.selected .gallery-item-checkbox {
        opacity: 1;
    }
    .gallery-item-simple.selected {
        border-color: #3ea572;
        box-shadow: 0 0 0 2px rgba(62, 165, 114, 0.3);
    }
    .gallery-item-simple[data-type="filesystem"] {
        border-left: 3px solid #696cff;
    }
    .gallery-item-simple[data-type="database"] {
        border-left: 3px solid #3ea572;
    }
    .bulk-actions-simple {
        background: #3ea572;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        display: none;
        align-items: center;
        justify-content: space-between;
    }
    .bulk-actions-simple.show {
        display: flex;
    }
    .upload-area-simple {
        border: 2px dashed #d0d0d0;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background: #f9f9f9;
        transition: all 0.2s;
        cursor: pointer;
    }
    .upload-area-simple:hover,
    .upload-area-simple.dragover {
        border-color: #3ea572;
        background: #f0f9f4;
    }
</style>
@endpush

@php
$totalImages = ($totalDbImages ?? 0) + ($totalFsImages ?? 0);
$activeImages = \App\Models\Gallery::where('is_active', true)->count();
@endphp

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-image-line me-2"></i>Gallery Management
                        </h4>
                        <small class="text-muted">Manage images for homepage, destinations, and more</small>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- View Type Toggle -->
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.homepage.gallery', array_merge(request()->all(), ['view' => 'all'])) }}" 
                               class="btn btn-sm {{ ($viewType ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="ri-layout-grid-line me-1"></i>All ({{ ($totalDbImages ?? 0) + ($totalFsImages ?? 0) }})
                            </a>
                            <a href="{{ route('admin.homepage.gallery', array_merge(request()->all(), ['view' => 'database'])) }}" 
                               class="btn btn-sm {{ ($viewType ?? 'all') === 'database' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="ri-database-2-line me-1"></i>Database ({{ $totalDbImages ?? 0 }})
                            </a>
                            <a href="{{ route('admin.homepage.gallery', array_merge(request()->all(), ['view' => 'filesystem'])) }}" 
                               class="btn btn-sm {{ ($viewType ?? 'all') === 'filesystem' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="ri-folder-line me-1"></i>Filesystem ({{ $totalFsImages ?? 0 }})
                            </a>
                            @if($cloudinaryConfigured ?? false)
                            <a href="{{ route('admin.homepage.gallery', array_merge(request()->all(), ['view' => 'cloudinary'])) }}" 
                               class="btn btn-sm {{ ($viewType ?? 'all') === 'cloudinary' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="ri-cloud-line me-1"></i>Cloudinary
                            </a>
                            @endif
                        </div>
                        <a href="{{ route('admin.homepage.gallery.create') }}" class="btn btn-primary">
                            <i class="ri-upload-cloud-2-line me-1"></i>Upload Images
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
                                <i class="ri-image-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $totalImages }}</h5>
                            <small class="text-muted">Total Images</small>
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
                            <h5 class="mb-0">{{ $activeImages }}</h5>
                            <small class="text-muted">Active Images</small>
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
                                <i class="ri-price-tag-3-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ count($categories ?? []) }}</h5>
                            <small class="text-muted">Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bulk-actions-simple" id="bulkActionsBar">
        <div>
            <strong id="selectedCount">0</strong> image(s) selected
        </div>
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select form-select-sm" id="bulkActionSelect" style="width: auto; max-width: 200px;">
                <option value="">Choose Action...</option>
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
                <option value="delete">Delete</option>
            </select>
            <button type="button" class="btn btn-light btn-sm" id="executeBulkAction">
                <i class="ri-play-line me-1"></i>Apply
            </button>
            <button type="button" class="btn btn-light btn-sm" id="clearSelection">
                <i class="ri-close-line me-1"></i>Clear
            </button>
        </div>
    </div>

    <!-- Homepage Gallery Info -->
    <div class="alert alert-info mb-4">
        <div class="d-flex align-items-start">
            <i class="ri-information-line me-2 mt-1" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Homepage Gallery - "Tanzania in Pictures"</strong>
                <p class="mb-0 small">Images with category <strong>"Homepage Gallery"</strong> or <strong>"Tanzania in Pictures"</strong>, or marked as <strong>Featured</strong> will appear in the homepage gallery section. Up to 12 images are displayed, ordered by display order and priority.</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.homepage.gallery') }}" class="row g-3">
                <input type="hidden" name="view" value="{{ $viewType ?? 'all' }}">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search images..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Album</label>
                    <select name="album_id" class="form-select">
                        <option value="">All Albums</option>
                        @foreach($albums ?? [] as $album)
                            <option value="{{ $album->id }}" {{ request('album_id') == $album->id ? 'selected' : '' }}>{{ $album->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="">Default</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line"></i>
                    </button>
                    <a href="{{ route('admin.homepage.gallery') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Cloudinary Media Library -->
    @if(($viewType ?? 'all') === 'cloudinary')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="ri-cloud-line me-2"></i>Cloudinary Media Library</h5>
                <small class="text-muted">
                    <a href="{{ route('admin.cloudinary-accounts.index') }}" class="text-primary">
                        <i class="ri-settings-3-line me-1"></i>Manage Accounts
                    </a>
                </small>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary" onclick="loadCloudinaryImages()">
                    <i class="ri-refresh-line me-1"></i>Refresh
                </button>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#cloudinaryUploadModal">
                    <i class="ri-upload-cloud-2-line me-1"></i>Upload
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Cloudinary Filters -->
            <div class="row g-3 mb-4">
                @if(($cloudinaryAccounts ?? collect())->count() > 1)
                <div class="col-md-3">
                    <label class="form-label">Account</label>
                    <select class="form-select" id="cloudinary_account" onchange="loadCloudinaryImages()">
                        @foreach($cloudinaryAccounts as $acc)
                            <option value="{{ $acc->id }}" {{ $acc->is_default ? 'selected' : '' }}>
                                {{ $acc->name }} {{ $acc->is_default ? '(Default)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-{{ ($cloudinaryAccounts ?? collect())->count() > 1 ? '3' : '4' }}">
                    <label class="form-label">Folder</label>
                    <select class="form-select" id="cloudinary_folder" onchange="loadCloudinaryImages()">
                        <option value="">All Folders</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="cloudinary_search" placeholder="Search images..." onkeyup="filterCloudinaryImages()">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Actions</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="importSelectedCloudinaryImages()">
                            <i class="ri-download-cloud-line me-1"></i>Import Selected
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="deleteSelectedCloudinaryImages()">
                            <i class="ri-delete-bin-line me-1"></i>Delete Selected
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cloudinary Images Grid -->
            <div id="cloudinary_grid" class="gallery-simple-grid">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading Cloudinary images...</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Images Grid -->
    @if(($viewType ?? 'all') !== 'cloudinary')
    <div class="card">
        <div class="card-body">
            @if($images->count() > 0)
                <div class="gallery-simple-grid">
                    @foreach($images as $image)
                    <div class="gallery-item-simple" data-id="{{ $image->id }}" data-type="{{ $image->type ?? 'database' }}">
                        @if(($image->type ?? 'database') === 'database')
                        <input type="checkbox" class="gallery-item-checkbox form-check-input" value="{{ $image->id }}">
                        <div class="gallery-item-actions">
                            <a href="{{ route('admin.homepage.gallery.edit', $image->id) }}" class="btn btn-sm btn-light" title="Edit">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light delete-gallery" data-id="{{ $image->id }}" data-name="{{ $image->title }}" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                        @else
                        <div class="gallery-item-actions">
                            <a href="{{ $image->display_url }}" target="_blank" class="btn btn-sm btn-light" title="View">
                                <i class="ri-eye-line"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light copy-path" data-path="{{ $image->image_url }}" title="Copy Path">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        @endif
                        @if($image->image_url ?? $image->display_url)
                            <img src="{{ $image->display_url ?? asset($image->image_url) }}" alt="{{ $image->title }}" loading="lazy">
                        @else
                            <div style="height: 140px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-image-line" style="font-size: 2rem; color: #ccc;"></i>
                            </div>
                        @endif
                        <div class="gallery-item-info">
                            <div class="gallery-item-title" title="{{ $image->title }}">{{ $image->title }}</div>
                            <div class="gallery-item-meta">
                                <span>
                                    @if(($image->width ?? null) && ($image->height ?? null))
                                        {{ $image->width }}×{{ $image->height }}
                                    @else
                                        —
                                    @endif
                                </span>
                                @if($image->category ?? null)
                                    <span class="badge bg-label-info" style="font-size: 0.7rem;">{{ $image->category }}</span>
                                @endif
                                @if(($image->type ?? 'database') === 'filesystem')
                                    <span class="badge bg-label-secondary" style="font-size: 0.7rem;">FS</span>
                                @endif
                            </div>
                            @if(($image->type ?? 'database') === 'filesystem' && isset($image->folder))
                                <div class="mt-1">
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        <i class="ri-folder-line"></i> {{ $image->folder }}
                                    </small>
                                </div>
                            @endif
                            @if(($image->is_featured ?? false) && ($image->type ?? 'database') === 'database')
                                <div class="mt-1">
                                    <span class="badge bg-label-success" style="font-size: 0.7rem;">Featured</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $images->appends(array_merge(request()->query(), ['view' => $viewType ?? 'all']))->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-image-line" style="font-size: 4rem; color: #d0d0d0;"></i>
                    <h5 class="mt-3">No images found</h5>
                    <p class="text-muted">Start by uploading your first image</p>
                    <a href="{{ route('admin.homepage.gallery.create') }}" class="btn btn-primary mt-2">
                        <i class="ri-upload-cloud-2-line me-1"></i>Upload Images
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Cloudinary Upload Modal -->
@if(($viewType ?? 'all') === 'cloudinary')
<div class="modal fade" id="cloudinaryUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-upload-cloud-2-line me-2"></i>Upload to Cloudinary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="cloudinaryUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Image</label>
                        <input type="file" class="form-control" id="cloudinary_file" name="file" accept="image/*" required>
                        <small class="text-muted">Max size: 10MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Folder (optional)</label>
                        <input type="text" class="form-control" id="cloudinary_upload_folder" name="folder" placeholder="e.g., gallery/tours">
                        <small class="text-muted">Leave empty for root folder</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="uploadToCloudinary()">
                    <i class="ri-upload-cloud-2-line me-1"></i>Upload
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteGalleryName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteGalleryForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Image Selection (only for database images)
    let selectedImages = [];
    
    $('.gallery-item-checkbox').on('change', function() {
        const id = $(this).val();
        const item = $(this).closest('.gallery-item-simple');
        
        if ($(this).is(':checked')) {
            if (!selectedImages.includes(id)) {
                selectedImages.push(id);
                item.addClass('selected');
            }
        } else {
            selectedImages = selectedImages.filter(x => x != id);
            item.removeClass('selected');
        }
        updateBulkActionsBar();
    });
    
    // Copy path for filesystem images
    $('.copy-path').on('click', function() {
        const path = $(this).data('path');
        navigator.clipboard.writeText(path).then(function() {
            alert('Path copied to clipboard: ' + path);
        }).catch(function() {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = path;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Path copied to clipboard: ' + path);
        });
    });

    function updateBulkActionsBar() {
        if (selectedImages.length > 0) {
            $('#bulkActionsBar').addClass('show');
            $('#selectedCount').text(selectedImages.length);
        } else {
            $('#bulkActionsBar').removeClass('show');
        }
    }

    $('#clearSelection').on('click', function() {
        selectedImages = [];
        $('.gallery-item-checkbox').prop('checked', false);
        $('.gallery-item-simple').removeClass('selected');
        updateBulkActionsBar();
    });

    // Bulk Actions
    $('#executeBulkAction').on('click', function() {
        const action = $('#bulkActionSelect').val();
        if (!action || selectedImages.length === 0) {
            alert('Please select an action and at least one image');
            return;
        }

        if (action === 'delete') {
            if (!confirm(`Delete ${selectedImages.length} image(s)? This cannot be undone.`)) return;
        }

        $.ajax({
            url: '{{ route("admin.homepage.gallery.bulk-action") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                action: action,
                ids: selectedImages
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('An error occurred');
            }
        });
    });

    // Delete Gallery
    $('.delete-gallery').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#deleteGalleryName').text(name);
        $('#deleteGalleryForm').attr('action', '{{ route("admin.homepage.gallery.destroy", ":id") }}'.replace(':id', id));
        $('#deleteGalleryModal').modal('show');
    });

    @if(($viewType ?? 'all') === 'cloudinary')
    // Load Cloudinary folders and images on page load
    loadCloudinaryFolders();
    loadCloudinaryImages();
    @endif
});

@if(($viewType ?? 'all') === 'cloudinary')
// ========== CLOUDINARY INTEGRATION ==========
let selectedCloudinaryImages = [];
let cloudinaryAssets = [];

function loadCloudinaryFolders() {
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const params = accountId ? `?account_id=${accountId}` : '';
    
    fetch('{{ route("admin.cloudinary.folders") }}' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.folders) {
            const select = document.getElementById('cloudinary_folder');
            select.innerHTML = '<option value="">All Folders</option>';
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
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    
    grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Loading Cloudinary images...</p></div>';
    
    const params = new URLSearchParams({ max_results: 500 });
    if (folder) params.append('folder', folder);
    if (accountId) params.append('account_id', accountId);
    
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
        
        cloudinaryAssets = data.resources || [];
        
        if (cloudinaryAssets.length === 0) {
            grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No images found in Cloudinary</p></div>';
            return;
        }
        
        cloudinaryAssets.forEach(asset => {
            const item = document.createElement('div');
            item.className = 'gallery-item-simple cloudinary-item';
            item.setAttribute('data-public-id', asset.public_id);
            item.setAttribute('data-url', asset.url);
            item.setAttribute('data-name', asset.filename.toLowerCase());
            
            const isSelected = selectedCloudinaryImages.includes(asset.public_id);
            
            item.innerHTML = `
                <input type="checkbox" class="gallery-item-checkbox form-check-input cloudinary-checkbox" 
                       value="${asset.public_id}" ${isSelected ? 'checked' : ''} 
                       onchange="toggleCloudinarySelection('${asset.public_id}')">
                <div class="gallery-item-actions">
                    <a href="${asset.url}" target="_blank" class="btn btn-sm btn-light" title="View">
                        <i class="ri-eye-line"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-light" onclick="copyCloudinaryUrl('${asset.url.replace(/'/g, "\\'")}')" title="Copy URL">
                        <i class="ri-file-copy-line"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="deleteCloudinaryImage('${asset.public_id}', '${asset.filename.replace(/'/g, "\\'")}')" title="Delete">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <img src="${asset.url}" alt="${asset.filename}" loading="lazy">
                <div class="gallery-item-info">
                    <div class="gallery-item-title" title="${asset.filename}">${asset.filename}</div>
                    <div class="gallery-item-meta">
                        <span>${asset.width || '?'}×${asset.height || '?'}</span>
                        <span class="badge bg-label-warning" style="font-size: 0.7rem;">
                            <i class="ri-cloud-line"></i> Cloudinary
                        </span>
                    </div>
                    ${asset.folder ? `<div class="mt-1"><small class="text-muted" style="font-size: 0.7rem;"><i class="ri-folder-line"></i> ${asset.folder}</small></div>` : ''}
                </div>
            `;
            
            if (isSelected) {
                item.classList.add('selected');
            }
            
            grid.appendChild(item);
        });
    })
    .catch(error => {
        console.error('Error loading Cloudinary images:', error);
        grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Error loading Cloudinary images</p></div>';
    });
}

function filterCloudinaryImages() {
    const search = document.getElementById('cloudinary_search')?.value.toLowerCase() || '';
    document.querySelectorAll('.cloudinary-item').forEach(item => {
        const name = item.getAttribute('data-name') || '';
        item.style.display = name.includes(search) ? '' : 'none';
    });
}

function toggleCloudinarySelection(publicId) {
    const index = selectedCloudinaryImages.indexOf(publicId);
    const item = document.querySelector(`[data-public-id="${publicId}"]`);
    
    if (index > -1) {
        selectedCloudinaryImages.splice(index, 1);
        item?.classList.remove('selected');
    } else {
        selectedCloudinaryImages.push(publicId);
        item?.classList.add('selected');
    }
}

function copyCloudinaryUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        if (typeof toastr !== 'undefined') {
            toastr.success('URL copied to clipboard');
        } else {
            alert('URL copied to clipboard');
        }
    }).catch(() => {
        alert('URL: ' + url);
    });
}

function uploadToCloudinary() {
    const fileInput = document.getElementById('cloudinary_file');
    const folderInput = document.getElementById('cloudinary_upload_folder');
    const accountSelect = document.getElementById('cloudinary_account');
    const form = document.getElementById('cloudinaryUploadForm');
    
    if (!fileInput.files.length) {
        alert('Please select a file to upload');
        return;
    }
    
    const formData = new FormData(form);
    if (folderInput.value) {
        formData.append('folder', folderInput.value);
    }
    if (accountSelect && accountSelect.value) {
        formData.append('account_id', accountSelect.value);
    }
    
    const uploadBtn = event.target;
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Uploading...';
    
    fetch('{{ route("admin.cloudinary.upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof toastr !== 'undefined') {
                toastr.success(data.message || 'File uploaded successfully');
            } else {
                alert(data.message || 'File uploaded successfully');
            }
            bootstrap.Modal.getInstance(document.getElementById('cloudinaryUploadModal')).hide();
            form.reset();
            loadCloudinaryImages();
        } else {
            alert(data.message || 'Upload failed');
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Upload failed: ' + error.message);
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="ri-upload-cloud-2-line me-1"></i>Upload';
    });
}

function deleteCloudinaryImage(publicId, filename) {
    if (!confirm(`Delete "${filename}" from Cloudinary? This action cannot be undone.`)) {
        return;
    }
    
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const body = { public_id: publicId };
    if (accountId) body.account_id = accountId;
    
    fetch('{{ route("admin.cloudinary.delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify(body)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof toastr !== 'undefined') {
                toastr.success(data.message || 'Image deleted successfully');
            } else {
                alert(data.message || 'Image deleted successfully');
            }
            loadCloudinaryImages();
        } else {
            alert(data.message || 'Delete failed');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Delete failed: ' + error.message);
    });
}

function deleteSelectedCloudinaryImages() {
    if (selectedCloudinaryImages.length === 0) {
        alert('Please select images to delete');
        return;
    }
    
    if (!confirm(`Delete ${selectedCloudinaryImages.length} image(s) from Cloudinary? This action cannot be undone.`)) {
        return;
    }
    
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const promises = selectedCloudinaryImages.map(publicId => {
        const body = { public_id: publicId };
        if (accountId) body.account_id = accountId;
        return fetch('{{ route("admin.cloudinary.delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(body)
        }).then(r => r.json());
    });
    
    Promise.all(promises).then(results => {
        const successCount = results.filter(r => r.success).length;
        if (typeof toastr !== 'undefined') {
            toastr.success(`${successCount} image(s) deleted successfully`);
        } else {
            alert(`${successCount} image(s) deleted successfully`);
        }
        selectedCloudinaryImages = [];
        loadCloudinaryImages();
    });
}

function importSelectedCloudinaryImages() {
    if (selectedCloudinaryImages.length === 0) {
        alert('Please select images to import');
        return;
    }
    
    const selectedAssets = cloudinaryAssets.filter(a => selectedCloudinaryImages.includes(a.public_id));
    
    if (!confirm(`Import ${selectedAssets.length} image(s) to gallery database?`)) {
        return;
    }
    
    const promises = selectedAssets.map(asset =>
        fetch('{{ route("admin.cloudinary.import-to-gallery") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                public_id: asset.public_id,
                url: asset.url,
                category: 'Cloudinary Import'
            })
        }).then(r => r.json())
    );
    
    Promise.all(promises).then(results => {
        const successCount = results.filter(r => r.success).length;
        if (typeof toastr !== 'undefined') {
            toastr.success(`${successCount} image(s) imported to gallery successfully`);
        } else {
            alert(`${successCount} image(s) imported to gallery successfully`);
        }
        selectedCloudinaryImages = [];
        // Redirect to database view to see imported images
        window.location.href = '{{ route("admin.homepage.gallery", ["view" => "database"]) }}';
    });
}
@endif
</script>
@endpush
@endsection
