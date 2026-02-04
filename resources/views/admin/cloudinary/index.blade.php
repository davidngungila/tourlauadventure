@extends('admin.layouts.app')

@section('title', 'Cloudinary Media Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-cloud-line me-2"></i>Cloudinary Media Management
                    </h4>
                    <p class="text-muted mb-0">Manage your Cloudinary media library - upload, organize, and import images</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.cloudinary-accounts.index') }}" class="btn btn-outline-primary">
                        <i class="ri-settings-3-line me-1"></i>Manage Accounts
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="ri-upload-cloud-2-line me-1"></i>Upload Media
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Selection -->
    @php
        $cloudinaryAccounts = \App\Models\CloudinaryAccount::getActive();
    @endphp
    @if($cloudinaryAccounts->count() > 1)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <label class="form-label">Select Cloudinary Account</label>
                    <select class="form-select" id="cloudinary_account" onchange="loadCloudinaryImages()">
                        @foreach($cloudinaryAccounts as $acc)
                            <option value="{{ $acc->id }}" {{ $acc->is_default ? 'selected' : '' }}>
                                {{ $acc->name }} {{ $acc->is_default ? '(Default)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Folder</label>
                            <select class="form-select" id="cloudinary_folder" onchange="loadCloudinaryImages()">
                                <option value="">All Folders</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Resource Type</label>
                            <select class="form-select" id="resource_type" onchange="loadCloudinaryImages()">
                                <option value="image">Images</option>
                                <option value="video">Videos</option>
                                <option value="raw">Raw Files</option>
                                <option value="auto">Auto</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" onclick="loadCloudinaryImages()">
                                <i class="ri-refresh-line me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Media Library</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" onclick="createFolder()">
                            <i class="ri-folder-add-line me-1"></i>Create Folder
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" onclick="bulkDelete()" style="display:none;">
                            <i class="ri-delete-bin-line me-1"></i>Delete Selected
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="cloudinary_grid" class="row g-3">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-2">Loading Cloudinary media...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload to Cloudinary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" class="form-control" id="upload_file" name="file" accept="image/*,video/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Folder (optional)</label>
                        <input type="text" class="form-control" id="upload_folder" name="folder" placeholder="e.g., tours/destinations">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Public ID (optional)</label>
                        <input type="text" class="form-control" id="upload_public_id" name="public_id" placeholder="Leave empty for auto-generated">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-upload-cloud-2-line me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedImages = new Set();

function loadCloudinaryFolders() {
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const params = accountId ? `?account_id=${accountId}` : '';
    
    fetch('{{ route("admin.cloudinary.folders") }}' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const select = document.getElementById('cloudinary_folder');
        select.innerHTML = '<option value="">All Folders</option>';
        if (data.folders) {
            data.folders.forEach(folder => {
                const option = document.createElement('option');
                option.value = folder.path;
                option.textContent = folder.path;
                select.appendChild(option);
            });
        }
    })
    .catch(err => console.error('Error loading folders:', err));
}

function loadCloudinaryImages() {
    const grid = document.getElementById('cloudinary_grid');
    const folder = document.getElementById('cloudinary_folder')?.value || '';
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const resourceType = document.getElementById('resource_type')?.value || 'image';
    
    grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Loading Cloudinary media...</p></div>';
    
    const params = new URLSearchParams({ max_results: 500, resource_type: resourceType });
    if (folder) params.append('folder', folder);
    if (accountId) params.append('account_id', accountId);
    
    fetch('{{ route("admin.cloudinary.assets") }}?' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => {
        if (!r.ok) {
            throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
    })
    .then(data => {
        grid.innerHTML = '';
        if (data.success === false) {
            grid.innerHTML = `<div class="col-12 text-center py-5">
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    <strong>Error:</strong> ${data.message || 'Failed to load images'}
                    ${data.error ? '<br><small>' + data.error + '</small>' : ''}
                </div>
                <p class="text-muted mt-3">Please check:</p>
                <ul class="text-start text-muted">
                    <li>Cloudinary account is configured correctly</li>
                    <li>Account credentials are valid</li>
                    <li>You have at least one active Cloudinary account</li>
                </ul>
                <a href="{{ route('admin.cloudinary-accounts.index') }}" class="btn btn-primary mt-3">
                    <i class="ri-settings-3-line me-1"></i>Manage Accounts
                </a>
            </div>`;
            return;
        }
        if (data.resources && data.resources.length > 0) {
            data.resources.forEach(asset => {
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6';
                const imageUrl = asset.url || asset.secure_url || '';
                const isImage = asset.format && ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(asset.format.toLowerCase());
                
                col.innerHTML = `
                    <div class="card h-100">
                        <div class="card-body p-2">
                            <div class="position-relative">
                                <input type="checkbox" class="form-check-input position-absolute top-0 start-0 m-2" 
                                       value="${asset.public_id}" onchange="toggleSelection('${asset.public_id}', this.checked)">
                                ${isImage && imageUrl ? 
                                    `<img src="${imageUrl}" class="img-fluid rounded" style="width:100%;height:150px;object-fit:cover;" alt="${asset.public_id}" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'bg-light rounded d-flex align-items-center justify-content-center\\' style=\\'height:150px;\\'><i class=\\'ri-file-line ri-48px text-muted\\'></i></div>';">` :
                                    `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:150px;">
                                        <i class="ri-file-line ri-48px text-muted"></i>
                                    </div>`
                                }
                            </div>
                            <div class="mt-2">
                                <small class="text-muted d-block" style="font-size:0.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${asset.public_id}">
                                    ${asset.public_id}
                                </small>
                                <div class="d-flex gap-1 mt-2">
                                    ${isImage && imageUrl ? `<button class="btn btn-sm btn-outline-primary" onclick="importToGallery('${asset.public_id}', '${imageUrl}')" title="Import to Gallery">
                                        <i class="ri-download-line"></i>
                                    </button>` : ''}
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteImage('${asset.public_id}')" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        } else {
            grid.innerHTML = '<div class="col-12 text-center py-5"><i class="ri-inbox-line ri-48px text-muted"></i><p class="text-muted mt-2">No media found</p></div>';
        }
    })
    .catch(err => {
        console.error('Error loading Cloudinary images:', err);
        grid.innerHTML = `<div class="col-12 text-center py-5">
            <div class="alert alert-danger">
                <i class="ri-error-warning-line me-2"></i>
                <strong>Error loading media:</strong> ${err.message || 'Unknown error'}
            </div>
            <p class="text-muted mt-3">Please check your browser console for more details.</p>
            <a href="{{ route('admin.cloudinary-accounts.index') }}" class="btn btn-primary mt-3">
                <i class="ri-settings-3-line me-1"></i>Check Cloudinary Accounts
            </a>
        </div>`;
    });
}

function toggleSelection(publicId, checked) {
    if (checked) {
        selectedImages.add(publicId);
    } else {
        selectedImages.delete(publicId);
    }
    document.getElementById('bulkDeleteBtn').style.display = selectedImages.size > 0 ? 'block' : 'none';
}

function bulkDelete() {
    if (selectedImages.size === 0) return;
    if (!confirm(`Delete ${selectedImages.size} item(s) from Cloudinary? This cannot be undone.`)) return;
    
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const promises = Array.from(selectedImages).map(publicId => {
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
    
    Promise.all(promises).then(() => {
        selectedImages.clear();
        document.getElementById('bulkDeleteBtn').style.display = 'none';
        loadCloudinaryImages();
        toastr.success('Items deleted successfully');
    });
}

function deleteImage(publicId) {
    if (!confirm('Delete this item from Cloudinary? This cannot be undone.')) return;
    
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
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            toastr.success('Item deleted successfully');
            loadCloudinaryImages();
        } else {
            toastr.error(data.message || 'Failed to delete item');
        }
    });
}

function importToGallery(publicId, url) {
    if (!confirm('Import this image to your local gallery?')) return;
    
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const body = { public_id: publicId, url: url };
    if (accountId) body.account_id = accountId;
    
    fetch('{{ route("admin.cloudinary.import-to-gallery") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            toastr.success('Image imported to gallery successfully');
        } else {
            toastr.error(data.message || 'Failed to import image');
        }
    });
}

function createFolder() {
    const folderName = prompt('Enter folder name:');
    if (!folderName) return;
    
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    const body = { folder: folderName };
    if (accountId) body.account_id = accountId;
    
    fetch('{{ route("admin.cloudinary.create-folder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            toastr.success('Folder created successfully');
            loadCloudinaryFolders();
        } else {
            toastr.error(data.message || 'Failed to create folder');
        }
    });
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const accountId = document.getElementById('cloudinary_account')?.value || '';
    if (accountId) formData.append('account_id', accountId);
    
    fetch('{{ route("admin.cloudinary.upload") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            toastr.success('File uploaded successfully');
            document.getElementById('uploadForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            loadCloudinaryImages();
        } else {
            toastr.error(data.message || 'Failed to upload file');
        }
    });
});

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCloudinaryFolders();
    loadCloudinaryImages();
});
</script>
@endpush
@endsection
