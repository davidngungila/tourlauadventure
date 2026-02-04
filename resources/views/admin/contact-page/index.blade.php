@extends('admin.layouts.app')

@section('title', 'Contact Page Management - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-customer-service-line me-2"></i>Contact Page Management
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections Tab -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Page Sections</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                        <tr>
                            <td><strong>{{ $section->section_name }}</strong></td>
                            <td>{{ $section->data['title'] ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $section->is_active ? 'success' : 'secondary' }}">
                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $section->display_order }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="editSection({{ $section->id }})">
                                    <i class="ri-edit-line"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No sections found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Features Tab -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Why Choose Us Features</h5>
            <button class="btn btn-primary" onclick="addFeature()">
                <i class="ri-add-line me-1"></i>Add Feature
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($features as $feature)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        @if($feature->image_url)
                        <img src="{{ str_starts_with($feature->image_url, 'http') ? $feature->image_url : asset($feature->image_url) }}" 
                             class="card-img-top" alt="{{ $feature->title }}" style="height: 150px; object-fit: cover;" 
                             onerror="this.style.display='none';">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $feature->title }}</h6>
                            <p class="card-text small">{{ Str::limit($feature->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $feature->is_active ? 'success' : 'secondary' }}">
                                    {{ $feature->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <div>
                                    <button class="btn btn-sm btn-primary" onclick="editFeature({{ $feature->id }})">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteFeature({{ $feature->id }})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p>No features found. Click "Add Feature" to create one.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Section Edit Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sectionForm">
                <div class="modal-body">
                    <input type="hidden" name="section_id" id="section_id">
                    <div class="mb-3">
                        <label class="form-label">Section Name</label>
                        <input type="text" class="form-control" name="section_name" id="section_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Badge Text</label>
                        <input type="text" class="form-control" name="badge" id="section_badge">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="section_title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <textarea class="form-control" name="subtitle" id="section_subtitle" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="text" class="form-control" name="image_url" id="section_image_url" placeholder="Any image URL or path">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="display_order" id="section_display_order">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="section_is_active" value="1">
                            <label class="form-check-label" for="section_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Feature Modal -->
<div class="modal fade" id="featureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="featureModalTitle">Add Feature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="featureForm">
                <div class="modal-body">
                    <input type="hidden" name="feature_id" id="feature_id">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="feature_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="feature_description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" class="form-control" name="icon" id="feature_icon" placeholder="fas fa-star">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="text" class="form-control" name="image_url" id="feature_image_url" placeholder="Any image URL or path">
                        <small class="text-muted">Image will be displayed instead of icon if provided</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="display_order" id="feature_display_order" min="0">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="feature_is_active" value="1" checked>
                            <label class="form-check-label" for="feature_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const sections = @json($sections);
const features = @json($features);

function editSection(id) {
    const section = sections.find(s => s.id === id);
    if (!section) return;
    
    document.getElementById('section_id').value = section.id;
    document.getElementById('section_name').value = section.section_name;
    document.getElementById('section_badge').value = section.data?.badge || '';
    document.getElementById('section_title').value = section.data?.title || '';
    document.getElementById('section_subtitle').value = section.data?.subtitle || '';
    document.getElementById('section_image_url').value = section.image_url || '';
    document.getElementById('section_display_order').value = section.display_order;
    document.getElementById('section_is_active').checked = section.is_active;
    
    new bootstrap.Modal(document.getElementById('sectionModal')).show();
}

function addFeature() {
    document.getElementById('featureForm').reset();
    document.getElementById('feature_id').value = '';
    document.getElementById('featureModalTitle').textContent = 'Add Feature';
    new bootstrap.Modal(document.getElementById('featureModal')).show();
}

function editFeature(id) {
    const feature = features.find(f => f.id === id);
    if (!feature) return;
    
    document.getElementById('feature_id').value = feature.id;
    document.getElementById('feature_title').value = feature.title;
    document.getElementById('feature_description').value = feature.description;
    document.getElementById('feature_icon').value = feature.icon || '';
    document.getElementById('feature_image_url').value = feature.image_url || '';
    document.getElementById('feature_display_order').value = feature.display_order;
    document.getElementById('feature_is_active').checked = feature.is_active;
    document.getElementById('featureModalTitle').textContent = 'Edit Feature';
    
    new bootstrap.Modal(document.getElementById('featureModal')).show();
}

function deleteFeature(id) {
    if (!confirm('Are you sure you want to delete this feature?')) return;
    
    fetch(`/admin/contact-page/features/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete'));
        }
    });
}

document.getElementById('sectionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('section_id').value;
    const data = {
        section_name: document.getElementById('section_name').value,
        data_json: JSON.stringify({
            badge: document.getElementById('section_badge').value,
            title: document.getElementById('section_title').value,
            subtitle: document.getElementById('section_subtitle').value,
        }),
        image_url: document.getElementById('section_image_url').value,
        display_order: document.getElementById('section_display_order').value,
        is_active: document.getElementById('section_is_active').checked ? 1 : 0,
    };
    
    fetch(`/admin/contact-page/sections/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update'));
        }
    });
});

document.getElementById('featureForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('feature_id').value;
    const data = {
        title: document.getElementById('feature_title').value,
        description: document.getElementById('feature_description').value,
        icon: document.getElementById('feature_icon').value,
        image_url: document.getElementById('feature_image_url').value,
        display_order: document.getElementById('feature_display_order').value || 0,
        is_active: document.getElementById('feature_is_active').checked ? 1 : 0,
    };
    
    const url = id ? `/admin/contact-page/features/${id}` : '/admin/contact-page/features';
    const method = id ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to save'));
        }
    });
});
</script>
@endpush
@endsection












