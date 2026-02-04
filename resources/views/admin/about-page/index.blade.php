@extends('admin.layouts.app')

@section('title', 'About Page Management - Lau Paradise Adventures')
@section('description', 'Manage about page content')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    .section-card {
        transition: all 0.3s;
    }
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .team-member-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
    }
    .icon-preview {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    /* Ensure all modals scroll if content overflows */
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1090;
    }
    /* Drag and Drop Styles */
    .sortable-item {
        cursor: move;
        transition: all 0.2s;
    }
    .sortable-item:hover {
        background-color: #f8f9fa;
    }
    .sortable-item.dragging {
        opacity: 0.5;
        transform: scale(0.95);
    }
    .sortable-handle {
        cursor: grab;
        color: #6c757d;
        font-size: 1.2rem;
        margin-right: 10px;
    }
    .sortable-handle:active {
        cursor: grabbing;
    }
    /* Enhanced Card Styles */
    .enhanced-card {
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .enhanced-card:hover {
        border-color: var(--bs-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    /* Bulk Actions Bar */
    .bulk-actions-bar {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
    }
    .bulk-actions-bar.active {
        display: block;
    }
    /* Advanced Filter */
    .advanced-filter {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    /* Status Badge Enhanced */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    /* Image Preview Enhanced */
    .image-preview-container {
        position: relative;
        display: inline-block;
        margin-top: 10px;
    }
    .image-preview-container img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    /* Loading States */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-radius: 8px;
    }
    /* Responsive Tables */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.9rem;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Toasts for feedback -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Please fix the highlighted errors and try again.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="ri-information-line me-2"></i>About Page Management</h4>
                    <p class="text-muted mb-0">Manage all content sections of the about page</p>
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
                                <i class="ri-file-list-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $sections->count() }}</h5>
                            <small class="text-muted">Sections</small>
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
                                <i class="ri-team-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $teamMembers->count() }}</h5>
                            <small class="text-muted">Team Members</small>
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
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $values->count() }}</h5>
                            <small class="text-muted">Core Values</small>
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
                                <i class="ri-award-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $recognitions->count() }}</h5>
                            <small class="text-muted">Recognitions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#sections" role="tab">
                        <i class="ri-file-list-line me-1"></i> Sections
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#team" role="tab">
                        <i class="ri-team-line me-1"></i> Team Members
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#values" role="tab">
                        <i class="ri-star-line me-1"></i> Values
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#recognitions" role="tab">
                        <i class="ri-award-line me-1"></i> Recognitions
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#timeline" role="tab">
                        <i class="ri-time-line me-1"></i> Timeline
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#statistics" role="tab">
                        <i class="ri-bar-chart-line me-1"></i> Statistics
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#why-travel-with-us" role="tab">
                        <i class="ri-question-line me-1"></i> Why Travel With Us
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#content-blocks" role="tab">
                        <i class="ri-layout-line me-1"></i> Content Blocks
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Sections Tab -->
        <div class="tab-pane fade show active" id="sections" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Page Sections</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($sections as $section)
                        <div class="col-md-6 mb-3">
                            <div class="card section-card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $section->section_name }}</h6>
                                    <p class="text-muted small mb-2">Key: <code>{{ $section->section_key }}</code></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $section->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <button class="btn btn-sm btn-primary" onclick="editSection({{ $section->id }})">
                                            <i class="ri-edit-line"></i> Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members Tab -->
        <div class="tab-pane fade" id="team" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Team Members</h5>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addTeamMember()">
                            <i class="ri-add-line"></i> Add Member
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleBulkActions('team')">
                            <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions Bar -->
                    <div class="bulk-actions-bar" id="teamBulkActions">
                        <div class="d-flex align-items-center gap-3">
                            <strong>Selected: <span id="teamSelectedCount">0</span> items</strong>
                            <button class="btn btn-sm btn-success" onclick="bulkActivate('team')">
                                <i class="ri-checkbox-circle-line"></i> Activate
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkDeactivate('team')">
                                <i class="ri-close-circle-line"></i> Deactivate
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkDelete('team')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection('team')">
                                <i class="ri-close-line"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="teamSelectAll" onchange="toggleSelectAll('team', this.checked)">
                                    </th>
                                    <th width="50">Order</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Expertise</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teamTableBody">
                                @forelse($teamMembers as $member)
                                <tr class="sortable-item" data-id="{{ $member->id }}" data-order="{{ $member->display_order }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input team-checkbox" value="{{ $member->id }}" onchange="updateBulkActions('team')">
                                    </td>
                                    <td>
                                        <i class="ri-drag-move-2-line sortable-handle"></i>
                                        <span class="badge bg-label-info">{{ $member->display_order }}</span>
                                    </td>
                                    <td>
                                        <img src="{{ $member->image_url ? (str_starts_with($member->image_url, 'http') ? $member->image_url : asset($member->image_url)) : 'https://via.placeholder.com/60' }}" 
                                             alt="{{ $member->name }}" 
                                             class="team-member-image">
                                    </td>
                                    <td><strong>{{ $member->name }}</strong></td>
                                    <td>{{ $member->role }}</td>
                                    <td>
                                        @if($member->expertise && is_array($member->expertise))
                                            @foreach(array_slice($member->expertise, 0, 2) as $exp)
                                                <span class="badge bg-label-primary">{{ $exp }}</span>
                                            @endforeach
                                            @if(count($member->expertise) > 2)
                                                <span class="text-muted">+{{ count($member->expertise) - 2 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $member->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-primary" onclick="editTeamMember({{ $member->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteTeamMember({{ $member->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No team members found. <a href="#" onclick="addTeamMember()">Add one</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Tab -->
        <div class="tab-pane fade" id="values" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Core Values</h5>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addValue()">
                            <i class="ri-add-line"></i> Add Value
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleBulkActions('values')">
                            <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions Bar -->
                    <div class="bulk-actions-bar" id="valuesBulkActions">
                        <div class="d-flex align-items-center gap-3">
                            <strong>Selected: <span id="valuesSelectedCount">0</span> items</strong>
                            <button class="btn btn-sm btn-success" onclick="bulkActivate('values')">
                                <i class="ri-checkbox-circle-line"></i> Activate
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkDeactivate('values')">
                                <i class="ri-close-circle-line"></i> Deactivate
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkDelete('values')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection('values')">
                                <i class="ri-close-line"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="row" id="valuesContainer">
                        @forelse($values as $value)
                        <div class="col-md-6 mb-3 sortable-item" data-id="{{ $value->id }}" data-order="{{ $value->display_order }}">
                            <div class="card enhanced-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-drag-move-2-line sortable-handle"></i>
                                            <i class="{{ $value->icon ?? 'ri-star-line' }} icon-preview"></i>
                                            <h6 class="mb-0">{{ $value->title }}</h6>
                                        </div>
                                        <input type="checkbox" class="form-check-input values-checkbox" value="{{ $value->id }}" onchange="updateBulkActions('values')">
                                    </div>
                                    @if($value->image_url || ($value->image && $value->image->image_url))
                                    <div class="mb-2">
                                        <img src="{{ $value->display_image_url ?? ($value->image_url ? (str_starts_with($value->image_url, 'http') ? $value->image_url : asset($value->image_url)) : '') }}" 
                                             alt="{{ $value->title }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 100px; width: 100%; object-fit: cover;">
                                    </div>
                                    @endif
                                    <p class="text-muted small">{{ Str::limit($value->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            <span class="badge {{ $value->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                {{ $value->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="badge bg-label-info ms-1">Order: {{ $value->display_order }}</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editValue({{ $value->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteValue({{ $value->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">No values found. <a href="#" onclick="addValue()">Add one</a></div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recognitions Tab -->
        <div class="tab-pane fade" id="recognitions" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recognitions</h5>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addRecognition()">
                            <i class="ri-add-line"></i> Add Recognition
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleBulkActions('recognitions')">
                            <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions Bar -->
                    <div class="bulk-actions-bar" id="recognitionsBulkActions">
                        <div class="d-flex align-items-center gap-3">
                            <strong>Selected: <span id="recognitionsSelectedCount">0</span> items</strong>
                            <button class="btn btn-sm btn-success" onclick="bulkActivate('recognitions')">
                                <i class="ri-checkbox-circle-line"></i> Activate
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkDeactivate('recognitions')">
                                <i class="ri-close-circle-line"></i> Deactivate
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkDelete('recognitions')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection('recognitions')">
                                <i class="ri-close-line"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="row" id="recognitionsContainer">
                        @forelse($recognitions as $recognition)
                        <div class="col-md-6 mb-3 sortable-item" data-id="{{ $recognition->id }}" data-order="{{ $recognition->display_order }}">
                            <div class="card enhanced-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-drag-move-2-line sortable-handle"></i>
                                            <i class="{{ $recognition->icon ?? 'ri-award-line' }} icon-preview"></i>
                                            <h6 class="mb-0">{{ $recognition->title }}</h6>
                                        </div>
                                        <input type="checkbox" class="form-check-input recognitions-checkbox" value="{{ $recognition->id }}" onchange="updateBulkActions('recognitions')">
                                    </div>
                                    <p class="text-muted small">{{ Str::limit($recognition->description, 100) }}</p>
                                    @if($recognition->year)
                                    <span class="badge bg-label-info mb-2">{{ $recognition->year }}</span>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            <span class="badge {{ $recognition->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                {{ $recognition->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="badge bg-label-info ms-1">Order: {{ $recognition->display_order }}</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editRecognition({{ $recognition->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteRecognition({{ $recognition->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">No recognitions found. <a href="#" onclick="addRecognition()">Add one</a></div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Tab -->
        <div class="tab-pane fade" id="timeline" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Timeline Items</h5>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addTimelineItem()">
                            <i class="ri-add-line"></i> Add Item
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleBulkActions('timeline')">
                            <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions Bar -->
                    <div class="bulk-actions-bar" id="timelineBulkActions">
                        <div class="d-flex align-items-center gap-3">
                            <strong>Selected: <span id="timelineSelectedCount">0</span> items</strong>
                            <button class="btn btn-sm btn-success" onclick="bulkActivate('timeline')">
                                <i class="ri-checkbox-circle-line"></i> Activate
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkDeactivate('timeline')">
                                <i class="ri-close-circle-line"></i> Deactivate
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkDelete('timeline')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection('timeline')">
                                <i class="ri-close-line"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="timelineSelectAll" onchange="toggleSelectAll('timeline', this.checked)">
                                    </th>
                                    <th width="50">Order</th>
                                    <th>Year</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="timelineTableBody">
                                @forelse($timelineItems as $item)
                                <tr class="sortable-item" data-id="{{ $item->id }}" data-order="{{ $item->display_order }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input timeline-checkbox" value="{{ $item->id }}" onchange="updateBulkActions('timeline')">
                                    </td>
                                    <td>
                                        <i class="ri-drag-move-2-line sortable-handle"></i>
                                        <span class="badge bg-label-info">{{ $item->display_order }}</span>
                                    </td>
                                    <td><strong>{{ $item->year }}</strong></td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->description, 80) }}</td>
                                    <td>
                                        <span class="badge {{ $item->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-primary" onclick="editTimelineItem({{ $item->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteTimelineItem({{ $item->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No timeline items found. <a href="#" onclick="addTimelineItem()">Add one</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Tab -->
        <div class="tab-pane fade" id="statistics" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistics</h5>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addStatistic()">
                            <i class="ri-add-line"></i> Add Statistic
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleBulkActions('statistics')">
                            <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions Bar -->
                    <div class="bulk-actions-bar" id="statisticsBulkActions">
                        <div class="d-flex align-items-center gap-3">
                            <strong>Selected: <span id="statisticsSelectedCount">0</span> items</strong>
                            <button class="btn btn-sm btn-success" onclick="bulkActivate('statistics')">
                                <i class="ri-checkbox-circle-line"></i> Activate
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkDeactivate('statistics')">
                                <i class="ri-close-circle-line"></i> Deactivate
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkDelete('statistics')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection('statistics')">
                                <i class="ri-close-line"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="row" id="statisticsContainer">
                        @forelse($statistics as $stat)
                        <div class="col-md-6 mb-3 sortable-item" data-id="{{ $stat->id }}" data-order="{{ $stat->display_order }}">
                            <div class="card enhanced-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-drag-move-2-line sortable-handle"></i>
                                            <i class="{{ $stat->icon ?? 'ri-bar-chart-line' }} icon-preview"></i>
                                            <h6 class="mb-0">{{ $stat->label }}</h6>
                                        </div>
                                        <input type="checkbox" class="form-check-input statistics-checkbox" value="{{ $stat->id }}" onchange="updateBulkActions('statistics')">
                                    </div>
                                    <p class="h4 mb-1">{{ $stat->value }}</p>
                                    @if($stat->description)
                                    <p class="text-muted small">{{ $stat->description }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            <span class="badge {{ $stat->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                {{ $stat->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="badge bg-label-info ms-1">Order: {{ $stat->display_order }}</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editStatistic({{ $stat->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteStatistic({{ $stat->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">No statistics found. <a href="#" onclick="addStatistic()">Add one</a></div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Travel With Us Tab -->
        <div class="tab-pane fade" id="why-travel-with-us" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Why Travel With Us</h5>
                        <p class="text-muted mb-0 small">Manage reasons why customers should travel with your company</p>
                    </div>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addWhyTravelWithUs()">
                            <i class="ri-add-line"></i> Add Item
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleBulkActions('why-travel-with-us')">
                            <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-search-line"></i></span>
                                <input type="text" class="form-control" id="whyTravelWithUsSearch" placeholder="Search by title or description..." onkeyup="filterWhyTravelWithUs()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="whyTravelWithUsStatusFilter" onchange="filterWhyTravelWithUs()">
                                <option value="">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="whyTravelWithUsSortFilter" onchange="sortWhyTravelWithUs()">
                                <option value="order">Sort by Order</option>
                                <option value="title">Sort by Title</option>
                                <option value="created">Newest First</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bulk Actions Bar -->
                    <div class="bulk-actions-bar" id="why-travel-with-usBulkActions">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <strong>Selected: <span id="why-travel-with-usSelectedCount">0</span> items</strong>
                            <button class="btn btn-sm btn-success" onclick="bulkActivate('why-travel-with-us')">
                                <i class="ri-checkbox-circle-line"></i> Activate
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkDeactivate('why-travel-with-us')">
                                <i class="ri-close-circle-line"></i> Deactivate
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkDelete('why-travel-with-us')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection('why-travel-with-us')">
                                <i class="ri-close-line"></i> Clear
                            </button>
                        </div>
                    </div>

                    <!-- Select All Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="why-travel-with-usSelectAll" onchange="toggleSelectAll('why-travel-with-us', this.checked)">
                            <label class="form-check-label" for="why-travel-with-usSelectAll">
                                Select All
                            </label>
                        </div>
                    </div>

                    <div class="row" id="whyTravelWithUsContainer">
                        @forelse($whyTravelWithUs as $item)
                        <div class="col-md-6 col-lg-4 mb-3 sortable-item why-travel-with-us-item" data-id="{{ $item->id }}" data-order="{{ $item->display_order }}" data-title="{{ strtolower($item->title) }}" data-description="{{ strtolower($item->description ?? '') }}" data-status="{{ $item->is_active ? 'active' : 'inactive' }}">
                            <div class="card enhanced-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <i class="ri-drag-move-2-line sortable-handle me-2"></i>
                                            <h6 class="mb-0 flex-grow-1">{{ $item->title }}</h6>
                                        </div>
                                        <input type="checkbox" class="form-check-input why-travel-with-us-checkbox ms-2" value="{{ $item->id }}" onchange="updateBulkActions('why-travel-with-us')">
                                    </div>
                                    @if($item->image_url || ($item->image && $item->image->image_url))
                                    <div class="mb-3 position-relative">
                                        @php
                                            $imageUrl = $item->display_image_url ?? ($item->image_url ? (str_starts_with($item->image_url, 'http') ? $item->image_url : asset($item->image_url)) : '');
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $item->title }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 180px; width: 100%; object-fit: cover; cursor: pointer;"
                                             onclick="previewImage('{{ $imageUrl }}', '{{ $item->title }}')"
                                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'200\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">
                                                <i class="ri-image-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="mb-3 bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                        <i class="ri-image-line text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                    @endif
                                    <p class="text-muted small mb-2 why-travel-with-us-description">{{ Str::limit($item->description, 120) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge {{ $item->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                <i class="ri-{{ $item->is_active ? 'eye' : 'eye-off' }}-line me-1"></i>{{ $item->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="badge bg-label-info">
                                                <i class="ri-sort-asc me-1"></i>Order: {{ $item->display_order }}
                                            </span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-primary" onclick="editWhyTravelWithUs({{ $item->id }})" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="deleteWhyTravelWithUs({{ $item->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center py-5">
                                <i class="ri-question-line" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-2">No items found</p>
                                <p class="small text-muted">Create your first "Why Travel With Us" item to showcase your company's advantages</p>
                                <button class="btn btn-primary btn-sm mt-2" onclick="addWhyTravelWithUs()">
                                    <i class="ri-add-line me-2"></i>Add Your First Item
                                </button>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Blocks Tab -->
        <div class="tab-pane fade" id="content-blocks" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Content Blocks</h5>
                        <p class="text-muted mb-0 small">Manage advanced content sections (Culture, Sustainability, Partnerships, Location, etc.)</p>
                    </div>
                    <div class="quick-actions">
                        <button class="btn btn-primary btn-sm" onclick="addContentBlock()">
                            <i class="ri-add-line"></i> Add Block
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="contentBlocksContainer">
                        @forelse($contentBlocks as $block)
                        <div class="col-md-6 mb-3 sortable-item" data-id="{{ $block->id }}" data-order="{{ $block->display_order }}">
                            <div class="card enhanced-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-drag-move-2-line sortable-handle"></i>
                                            <span class="badge bg-label-info me-2">{{ ucfirst(str_replace('_', ' ', $block->block_type)) }}</span>
                                            <h6 class="mb-0">{{ $block->title }}</h6>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editContentBlock({{ $block->id }})" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteContentBlock({{ $block->id }})" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if($block->display_image_url)
                                    <div class="mb-3">
                                        <img src="{{ $block->display_image_url }}" alt="{{ $block->title }}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                    </div>
                                    @endif
                                    @if($block->description)
                                    <p class="text-muted small">{{ Str::limit($block->description, 100) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            <span class="badge {{ $block->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                {{ $block->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="badge bg-label-info ms-1">Order: {{ $block->display_order }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                No content blocks found. <a href="#" onclick="addContentBlock()">Add one</a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sectionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Section Name</label>
                            <input type="text" class="form-control" name="section_name" id="section_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Section Key</label>
                            <input type="text" class="form-control" id="section_key" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image URL</label>
                            <input type="text" class="form-control" name="image_url" id="section_image_url" placeholder="images/your-image.jpg or https://...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="section_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="section_is_active" value="1">
                                <label class="form-check-label" for="section_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" id="section_content" rows="3" placeholder="Optional HTML or text content"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Data (JSON)</label>
                            <textarea class="form-control font-monospace" name="data_json" id="section_data_json" rows="6" placeholder='{"title": "Example", "items": []}'></textarea>
                            <small class="text-muted">Provide valid JSON for structured data used by this section.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team Member Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamModalTitle">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="teamForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="team_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="team_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" name="role" id="team_role" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image URL</label>
                            <input type="text" class="form-control" name="image_url" id="team_image_url" placeholder="images/your-image.jpg or https://...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="team_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="team_is_active" value="1">
                                <label class="form-check-label" for="team_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" id="team_bio" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expertise (comma separated)</label>
                            <input type="text" class="form-control" name="expertise_text" id="team_expertise" placeholder="Safari Expert, Wildlife Guide">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Social Links (comma separated URLs)</label>
                            <input type="text" class="form-control" name="social_links_text" id="team_social_links" placeholder="https://linkedin..., https://twitter...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Value Modal -->
<div class="modal fade" id="valueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="valueModalTitle">Add Value</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="valueForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="value_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="value_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Icon (Optional - use if no image)</label>
                            <input type="text" class="form-control" name="icon" id="value_icon" placeholder="ri-star-line">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="value_display_order" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Image (from Gallery)</label>
                            @include('admin.partials.image-picker', [
                                'name' => 'image_id',
                                'label' => '',
                                'value' => null
                            ])
                        </div>
                        <div class="col-12">
                            <label class="form-label">Or Image URL</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="image_url" id="value_image_url" placeholder="images/value.jpg or full URL">
                                <button type="button" class="btn btn-outline-warning" onclick="openCloudinaryPickerForValue()">
                                    <i class="ri-cloud-line"></i> Cloudinary
                                </button>
                            </div>
                            <small class="text-muted">Path from public/ directory, full URL, or select from Cloudinary</small>
                            <div id="value_image_preview" class="mt-2"></div>
                        </div>
                        <div class="col-12 d-flex align-items-center">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="value_is_active" value="1">
                                <label class="form-check-label" for="value_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="value_description" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Recognition Modal -->
<div class="modal fade" id="recognitionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recognitionModalTitle">Add Recognition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="recognitionForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="recognition_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="recognition_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="icon" id="recognition_icon" placeholder="ri-award-line">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" name="year" id="recognition_year" placeholder="2025">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="recognition_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="recognition_is_active" value="1">
                                <label class="form-check-label" for="recognition_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="recognition_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Why Travel With Us Modal -->
<div class="modal fade" id="whyTravelWithUsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="whyTravelWithUsModalTitle">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="whyTravelWithUsForm" method="POST" action="/admin/about-page/why-travel-with-us">
                @csrf
                <input type="hidden" name="_method" id="why_travel_with_us_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="why_travel_with_us_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="why_travel_with_us_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0" id="why_travel_with_us_is_active_hidden">
                                <input class="form-check-input" type="checkbox" name="is_active" id="why_travel_with_us_is_active" value="1" onchange="document.getElementById('why_travel_with_us_is_active_hidden').disabled = this.checked;">
                                <label class="form-check-label" for="why_travel_with_us_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Image (from Gallery)</label>
                            @include('admin.partials.image-picker', [
                                'name' => 'image_id',
                                'label' => '',
                                'value' => null,
                                'id' => 'why_travel_with_us_image_id'
                            ])
                        </div>
                        <div class="col-12">
                            <label class="form-label">Or Image URL</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="image_url" id="why_travel_with_us_image_url" placeholder="/storage/gallery/image.jpg or https://example.com/image.jpg">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearWhyTravelWithUsImage()" title="Clear Image">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                            <small class="text-muted">Enter a full URL (https://example.com/image.jpg) or a path (/storage/gallery/image.jpg or /images/image.jpg)</small>
                            <div id="why_travel_with_us_image_preview" class="mt-3"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="why_travel_with_us_description" rows="5" required placeholder="Describe why customers should travel with your company..."></textarea>
                            <small class="text-muted">
                                <span id="why_travel_with_us_description_count">0</span> characters
                            </small>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info small mb-0">
                                <i class="ri-information-line me-1"></i>
                                <strong>Tip:</strong> Use clear, compelling descriptions that highlight your unique value propositions. Include specific benefits and advantages.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Content Blocks Modal -->
<div class="modal fade" id="contentBlockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentBlockModalTitle">Add Content Block</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contentBlockForm" method="POST" action="/admin/about-page/content-blocks">
                @csrf
                <input type="hidden" name="_method" id="content_block_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Block Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="block_type" id="content_block_type" required>
                                <option value="culture">Company Culture</option>
                                <option value="sustainability">Sustainability</option>
                                <option value="partnerships">Partnerships</option>
                                <option value="location">Location/Office</option>
                                <option value="social_responsibility">Social Responsibility</option>
                                <option value="commitment">Commitment</option>
                                <option value="testimonials">Testimonials</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="content_block_display_order" min="0" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="content_block_title" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subtitle</label>
                            <input type="text" class="form-control" name="subtitle" id="content_block_subtitle">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="content_block_description" rows="2" placeholder="Short description that appears in the list view"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" id="content_block_content" rows="5" placeholder="Full content text for the block"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" name="icon" id="content_block_icon" placeholder="fas fa-heart">
                            <small class="text-muted">FontAwesome icon class (e.g., fas fa-heart, ri-global-line)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image</label>
                            @include('admin.partials.image-picker', ['id' => 'content_block_image', 'name' => 'image_id', 'imageUrlName' => 'image_url'])
                            <div id="content_block_image_preview" class="mt-2"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Image URL</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="image_url" id="content_block_image_url" placeholder="/storage/gallery/image.jpg or https://example.com/image.jpg">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearContentBlockImage()" title="Clear Image">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                            <small class="text-muted">Enter a full URL (https://example.com/image.jpg) or a path (/storage/gallery/image.jpg or /images/image.jpg)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" class="form-control" name="button_text" id="content_block_button_text" placeholder="Learn More">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Link</label>
                            <input type="text" class="form-control" name="button_link" id="content_block_button_link" placeholder="/about or https://example.com">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0" id="content_block_is_active_hidden">
                                <input class="form-check-input" type="checkbox" name="is_active" id="content_block_is_active" value="1" onchange="document.getElementById('content_block_is_active_hidden').disabled = this.checked;" checked>
                                <label class="form-check-label" for="content_block_is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Timeline Modal -->
<div class="modal fade" id="timelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timelineModalTitle">Add Timeline Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="timelineForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="timeline_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" name="year" id="timeline_year" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="timeline_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="timeline_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="timeline_is_active" value="1">
                                <label class="form-check-label" for="timeline_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="timeline_description" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Statistic Modal -->
<div class="modal fade" id="statModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statModalTitle">Add Statistic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="stat_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="label" id="stat_label" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Value</label>
                            <input type="text" class="form-control" name="value" id="stat_value" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="stat_display_order" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="icon" id="stat_icon" placeholder="ri-bar-chart-line">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="stat_is_active" value="1">
                                <label class="form-check-label" for="stat_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="stat_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const sectionsData = @json($sections);
    const teamMembersData = @json($teamMembers);
    const valuesData = @json($values);
    const recognitionsData = @json($recognitions);
    const timelineData = @json($timelineItems);
    const statisticsData = @json($statistics);
    const whyTravelWithUsData = @json($whyTravelWithUs);
    const sectionUpdateUrlTemplate = '/admin/about-page/sections/__ID__';
    const teamUpdateUrlTemplate = '/admin/about-page/team-members/__ID__';
    const valueUpdateUrlTemplate = '/admin/about-page/values/__ID__';
    const recognitionUpdateUrlTemplate = '/admin/about-page/recognitions/__ID__';
    const whyTravelWithUsUpdateUrlTemplate = '/admin/about-page/why-travel-with-us/__ID__';
    const timelineUpdateUrlTemplate = '/admin/about-page/timeline-items/__ID__';
    const statUpdateUrlTemplate = '/admin/about-page/statistics/__ID__';

    let sectionModal;
    let teamModal;
    let valueModal;
    let recognitionModal;
    let timelineModal;
    let statModal;
    let whyTravelWithUsModal;
    let contentBlockModal;

    function showToast(type, message) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const colorClass = type === 'error' ? 'text-bg-danger' : (type === 'warning' ? 'text-bg-warning' : 'text-bg-success');
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center ${colorClass} border-0 show`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        container.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }

    function validateRequired(form, fields) {
        const missing = [];
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            const val = (el.value ?? '').trim();
            if (!val) missing.push(el.previousElementSibling ? el.previousElementSibling.textContent : id);
        });
        return missing;
    }

    document.addEventListener('DOMContentLoaded', () => {
        sectionModal = new bootstrap.Modal(document.getElementById('sectionModal'));
        teamModal = new bootstrap.Modal(document.getElementById('teamModal'));
        valueModal = new bootstrap.Modal(document.getElementById('valueModal'));
        recognitionModal = new bootstrap.Modal(document.getElementById('recognitionModal'));
        timelineModal = new bootstrap.Modal(document.getElementById('timelineModal'));
        statModal = new bootstrap.Modal(document.getElementById('statModal'));
        whyTravelWithUsModal = new bootstrap.Modal(document.getElementById('whyTravelWithUsModal'));
        contentBlockModal = new bootstrap.Modal(document.getElementById('contentBlockModal'));

        // Auto-init toasts for feedback
        document.querySelectorAll('.toast').forEach(toastEl => {
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();
        });

        const sectionForm = document.getElementById('sectionForm');
        sectionForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(sectionForm, ['section_name']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            const dataTextarea = document.getElementById('section_data_json');
            const rawJson = dataTextarea.value.trim();
            if (rawJson) {
                try {
                    JSON.parse(rawJson);
                } catch (err) {
                    showToast('error', 'Please provide valid JSON in the Data field.');
                    return;
                }
            }
            
            const formData = new FormData(sectionForm);
            const action = sectionForm.action;
            
            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Section updated successfully!');
                        sectionModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to update section. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Section updated successfully!');
                    sectionModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to update section. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const teamForm = document.getElementById('teamForm');
        teamForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(teamForm, ['team_name', 'team_role']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(teamForm);
            const method = formData.get('_method') || 'POST';
            const action = teamForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    // If it's a redirect or HTML response, just reload
                    if (response.ok || response.redirected) {
                        showToast('success', 'Team member saved successfully!');
                        teamModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save team member. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Team member saved successfully!');
                    teamModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    // Handle validation errors (422 status)
                    let errorMsg = result.message || 'Failed to save team member. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const valueForm = document.getElementById('valueForm');
        valueForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(valueForm, ['value_title', 'value_description']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(valueForm);
            const method = formData.get('_method') || 'POST';
            const action = valueForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Value saved successfully!');
                        valueModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save value. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Value saved successfully!');
                    valueModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save value. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const recognitionForm = document.getElementById('recognitionForm');
        recognitionForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(recognitionForm, ['recognition_title']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(recognitionForm);
            const method = formData.get('_method') || 'POST';
            const action = recognitionForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Recognition saved successfully!');
                        recognitionModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save recognition. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Recognition saved successfully!');
                    recognitionModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save recognition. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const timelineForm = document.getElementById('timelineForm');
        timelineForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(timelineForm, ['timeline_year', 'timeline_title', 'timeline_description']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(timelineForm);
            const method = formData.get('_method') || 'POST';
            const action = timelineForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Timeline item saved successfully!');
                        timelineModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save timeline item. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Timeline item saved successfully!');
                    timelineModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save timeline item. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const statForm = document.getElementById('statForm');
        statForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(statForm, ['stat_label', 'stat_value']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(statForm);
            const method = formData.get('_method') || 'POST';
            const action = statForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Statistic saved successfully!');
                        statModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save statistic. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Statistic saved successfully!');
                    statModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save statistic. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        // Why Travel With Us Form Handler
        const whyTravelWithUsForm = document.getElementById('whyTravelWithUsForm');
        if (whyTravelWithUsForm) {
            whyTravelWithUsForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                // Validate required fields
                const title = document.getElementById('why_travel_with_us_title').value.trim();
                const description = document.getElementById('why_travel_with_us_description').value.trim();
                
                if (!title) {
                    showToast('error', 'Please enter a title');
                    document.getElementById('why_travel_with_us_title').focus();
                    return;
                }
                
                if (!description) {
                    showToast('error', 'Please enter a description');
                    document.getElementById('why_travel_with_us_description').focus();
                    return;
                }
                
                const formData = new FormData(whyTravelWithUsForm);
                const methodInput = document.getElementById('why_travel_with_us_form_method');
                const method = methodInput ? methodInput.value : 'POST';
                let action = whyTravelWithUsForm.action || '/admin/about-page/why-travel-with-us';
                
                // Ensure _method is in FormData - read from hidden input
                if (methodInput && methodInput.value === 'PUT') {
                    formData.set('_method', 'PUT');
                } else {
                    formData.set('_method', 'POST');
                }
                
                // Handle is_active checkbox - if unchecked, ensure value is 0
                const isActiveCheckbox = document.getElementById('why_travel_with_us_is_active');
                const isActiveHidden = document.getElementById('why_travel_with_us_is_active_hidden');
                if (isActiveCheckbox && !isActiveCheckbox.checked) {
                    formData.set('is_active', '0');
                    if (isActiveHidden) {
                        isActiveHidden.disabled = false;
                    }
                } else if (isActiveCheckbox && isActiveCheckbox.checked) {
                    formData.set('is_active', '1');
                    if (isActiveHidden) {
                        isActiveHidden.disabled = true;
                    }
                }
                
                // Debug: Log form data (remove in production)
                if (console && console.log) {
                    console.log('Submitting Why Travel With Us Form:', { 
                        action, 
                        method, 
                        title: formData.get('title'),
                        description: description.substring(0, 50) + '...',
                        is_active: formData.get('is_active'),
                        _method: formData.get('_method'),
                        has_image_id: !!formData.get('image_id'),
                        has_image_url: !!formData.get('image_url'),
                        display_order: formData.get('display_order')
                    });
                }
                
                try {
                    const response = await fetch(action, {
                        method: 'POST', // Always use POST for Laravel form submissions
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    console.log('Response status:', response.status, response.statusText);
                    
                    let result;
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        result = await response.json();
                        console.log('Response JSON:', result);
                    } else {
                        const text = await response.text();
                        console.log('Response text (first 500 chars):', text.substring(0, 500));
                        if (response.ok || response.redirected) {
                            showToast('success', 'Item saved successfully!');
                            whyTravelWithUsModal.hide();
                            setTimeout(() => location.reload(), 1000);
                            return;
                        }
                        result = { success: false, message: 'Failed to save item. Please try again.' };
                    }
                    
                    if (response.ok && result.success !== false) {
                        showToast('success', result.message || 'Item saved successfully!');
                        whyTravelWithUsModal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        let errorMsg = result.message || 'Failed to save item. Please try again.';
                        if (result.errors) {
                            const errorList = Object.values(result.errors).flat().join(', ');
                            errorMsg = errorList || errorMsg;
                        }
                        showToast('error', errorMsg);
                        console.error('Validation errors:', result);
                    }
                } catch (error) {
                    showToast('error', 'An error occurred. Please try again.');
                    console.error('Error:', error);
                }
            });
        }
    });

    function editSection(id) {
        const section = sectionsData.find(s => s.id === id);
        if (!section) return;

        const form = document.getElementById('sectionForm');
        form.action = sectionUpdateUrlTemplate.replace('__ID__', id);

        document.getElementById('section_name').value = section.section_name ?? '';
        document.getElementById('section_key').value = section.section_key ?? '';
        document.getElementById('section_image_url').value = section.image_url ?? '';
        document.getElementById('section_display_order').value = section.display_order ?? '';
        document.getElementById('section_content').value = section.content ?? '';
        document.getElementById('section_is_active').checked = !!section.is_active;
        document.getElementById('section_data_json').value = section.data ? JSON.stringify(section.data, null, 2) : '';

        sectionModal.show();
    }

    // Team Members
    function resetTeamForm() {
        document.getElementById('team_form_method').value = 'POST';
        document.getElementById('teamModalTitle').textContent = 'Add Team Member';
        document.getElementById('teamForm').action = '/admin/about-page/team-members';
        document.getElementById('team_name').value = '';
        document.getElementById('team_role').value = '';
        document.getElementById('team_image_url').value = '';
        document.getElementById('team_display_order').value = '';
        document.getElementById('team_is_active').checked = true;
        document.getElementById('team_bio').value = '';
        document.getElementById('team_expertise').value = '';
        document.getElementById('team_social_links').value = '';
    }

    function addTeamMember() {
        resetTeamForm();
        teamModal.show();
    }

    function editTeamMember(id) {
        const member = teamMembersData.find(m => m.id === id);
        if (!member) return;
        resetTeamForm();
        document.getElementById('team_form_method').value = 'PUT';
        document.getElementById('teamModalTitle').textContent = 'Edit Team Member';
        document.getElementById('teamForm').action = teamUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('team_name').value = member.name ?? '';
        document.getElementById('team_role').value = member.role ?? '';
        document.getElementById('team_image_url').value = member.image_url ?? '';
        document.getElementById('team_display_order').value = member.display_order ?? '';
        document.getElementById('team_is_active').checked = !!member.is_active;
        document.getElementById('team_bio').value = member.bio ?? '';
        document.getElementById('team_expertise').value = Array.isArray(member.expertise) ? member.expertise.join(', ') : '';
        document.getElementById('team_social_links').value = Array.isArray(member.social_links) ? member.social_links.join(', ') : '';
        teamModal.show();
    }
    
    function deleteTeamMember(id) {
        if (confirm('Are you sure you want to delete this team member?')) {
            fetch(`/admin/about-page/team-members/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Values
    function resetValueForm() {
        document.getElementById('value_form_method').value = 'POST';
        document.getElementById('valueModalTitle').textContent = 'Add Value';
        document.getElementById('valueForm').action = '{{ route("admin.about-page.values.store") }}';
        document.getElementById('value_title').value = '';
        document.getElementById('value_icon').value = '';
        document.getElementById('value_display_order').value = '';
        document.getElementById('value_is_active').checked = true;
        document.getElementById('value_description').value = '';
        document.getElementById('value_image_url').value = '';
        document.getElementById('value_image_preview').innerHTML = '';
        // Reset image picker
        const imageIdInput = document.getElementById('image_id');
        if (imageIdInput) imageIdInput.value = '';
        const imagePreview = document.getElementById('selected_image_preview_image_id');
        if (imagePreview) imagePreview.innerHTML = '<p class="text-muted small">No image selected.</p>';
    }

    function addValue() {
        resetValueForm();
        valueModal.show();
    }
    
    function editValue(id) {
        const value = valuesData.find(v => v.id === id);
        if (!value) return;
        resetValueForm();
        document.getElementById('value_form_method').value = 'PUT';
        document.getElementById('valueModalTitle').textContent = 'Edit Value';
        document.getElementById('valueForm').action = '{{ url("admin/about-page/values") }}/' + id;
        document.getElementById('value_title').value = value.title ?? '';
        document.getElementById('value_icon').value = value.icon ?? '';
        document.getElementById('value_display_order').value = value.display_order ?? '';
        document.getElementById('value_is_active').checked = !!value.is_active;
        document.getElementById('value_description').value = value.description ?? '';
        document.getElementById('value_image_url').value = value.image_url ?? '';
        
        // Set image_id for image picker
        const imageIdInput = document.getElementById('image_id');
        if (imageIdInput) imageIdInput.value = value.image_id ?? '';
        
        // Show image preview
        if (value.image_url) {
            const fullUrl = value.image_url.startsWith('http') ? value.image_url : '{{ asset("") }}' + value.image_url;
            document.getElementById('value_image_preview').innerHTML = `<img src="${fullUrl}" alt="Preview" class="img-thumbnail" style="max-width: 200px;">`;
        }
        valueModal.show();
    }
    
    function deleteValue(id) {
        if (confirm('Are you sure you want to delete this value?')) {
            fetch(`/admin/about-page/values/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Recognitions
    function resetRecognitionForm() {
        document.getElementById('recognition_form_method').value = 'POST';
        document.getElementById('recognitionModalTitle').textContent = 'Add Recognition';
        document.getElementById('recognitionForm').action = '/admin/about-page/recognitions';
        document.getElementById('recognition_title').value = '';
        document.getElementById('recognition_icon').value = '';
        document.getElementById('recognition_year').value = '';
        document.getElementById('recognition_display_order').value = '';
        document.getElementById('recognition_is_active').checked = true;
        document.getElementById('recognition_description').value = '';
    }

    function addRecognition() {
        resetRecognitionForm();
        recognitionModal.show();
    }
    
    function editRecognition(id) {
        const rec = recognitionsData.find(r => r.id === id);
        if (!rec) return;
        resetRecognitionForm();
        document.getElementById('recognition_form_method').value = 'PUT';
        document.getElementById('recognitionModalTitle').textContent = 'Edit Recognition';
        document.getElementById('recognitionForm').action = recognitionUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('recognition_title').value = rec.title ?? '';
        document.getElementById('recognition_icon').value = rec.icon ?? '';
        document.getElementById('recognition_year').value = rec.year ?? '';
        document.getElementById('recognition_display_order').value = rec.display_order ?? '';
        document.getElementById('recognition_is_active').checked = !!rec.is_active;
        document.getElementById('recognition_description').value = rec.description ?? '';
        recognitionModal.show();
    }
    
    function deleteRecognition(id) {
        if (confirm('Are you sure you want to delete this recognition?')) {
            fetch(`/admin/about-page/recognitions/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Timeline
    function resetTimelineForm() {
        document.getElementById('timeline_form_method').value = 'POST';
        document.getElementById('timelineModalTitle').textContent = 'Add Timeline Item';
        document.getElementById('timelineForm').action = '/admin/about-page/timeline-items';
        document.getElementById('timeline_year').value = '';
        document.getElementById('timeline_title').value = '';
        document.getElementById('timeline_display_order').value = '';
        document.getElementById('timeline_is_active').checked = true;
        document.getElementById('timeline_description').value = '';
    }

    function addTimelineItem() {
        resetTimelineForm();
        timelineModal.show();
    }
    
    function editTimelineItem(id) {
        const item = timelineData.find(t => t.id === id);
        if (!item) return;
        resetTimelineForm();
        document.getElementById('timeline_form_method').value = 'PUT';
        document.getElementById('timelineModalTitle').textContent = 'Edit Timeline Item';
        document.getElementById('timelineForm').action = timelineUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('timeline_year').value = item.year ?? '';
        document.getElementById('timeline_title').value = item.title ?? '';
        document.getElementById('timeline_display_order').value = item.display_order ?? '';
        document.getElementById('timeline_is_active').checked = !!item.is_active;
        document.getElementById('timeline_description').value = item.description ?? '';
        timelineModal.show();
    }
    
    function deleteTimelineItem(id) {
        if (confirm('Are you sure you want to delete this timeline item?')) {
            fetch(`/admin/about-page/timeline-items/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Statistics
    function resetStatisticForm() {
        document.getElementById('stat_form_method').value = 'POST';
        document.getElementById('statModalTitle').textContent = 'Add Statistic';
        document.getElementById('statForm').action = '/admin/about-page/statistics';
        document.getElementById('stat_label').value = '';
        document.getElementById('stat_value').value = '';
        document.getElementById('stat_display_order').value = '';
        document.getElementById('stat_icon').value = '';
        document.getElementById('stat_is_active').checked = true;
        document.getElementById('stat_description').value = '';
    }

    function addStatistic() {
        resetStatisticForm();
        statModal.show();
    }
    
    function editStatistic(id) {
        const stat = statisticsData.find(s => s.id === id);
        if (!stat) return;
        resetStatisticForm();
        document.getElementById('stat_form_method').value = 'PUT';
        document.getElementById('statModalTitle').textContent = 'Edit Statistic';
        document.getElementById('statForm').action = statUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('stat_label').value = stat.label ?? '';
        document.getElementById('stat_value').value = stat.value ?? '';
        document.getElementById('stat_display_order').value = stat.display_order ?? '';
        document.getElementById('stat_icon').value = stat.icon ?? '';
        document.getElementById('stat_is_active').checked = !!stat.is_active;
        document.getElementById('stat_description').value = stat.description ?? '';
        statModal.show();
    }
    
    function deleteStatistic(id) {
        if (confirm('Are you sure you want to delete this statistic?')) {
            fetch(`/admin/about-page/statistics/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Why Travel With Us
    function resetWhyTravelWithUsForm() {
        const form = document.getElementById('whyTravelWithUsForm');
        const formMethodInput = document.getElementById('why_travel_with_us_form_method');
        
        if (formMethodInput) {
            formMethodInput.value = 'POST';
        }
        if (form) {
            form.action = '/admin/about-page/why-travel-with-us';
        }
        
        document.getElementById('whyTravelWithUsModalTitle').textContent = 'Add Item';
        document.getElementById('why_travel_with_us_title').value = '';
        document.getElementById('why_travel_with_us_display_order').value = '';
        document.getElementById('why_travel_with_us_is_active').checked = true;
        document.getElementById('why_travel_with_us_description').value = '';
        
        // Clear image picker
        const imageIdInput = document.getElementById('image_id');
        if (imageIdInput) {
            imageIdInput.value = '';
        }
        const imagePickerPreview = document.getElementById('selected_image_preview_image_id');
        if (imagePickerPreview) {
            imagePickerPreview.innerHTML = '<p class="text-muted small">No image selected. Click "Select from Gallery" to choose an image.</p>';
        }
        
        document.getElementById('why_travel_with_us_image_url').value = '';
        document.getElementById('why_travel_with_us_image_preview').innerHTML = '';
        updateWhyTravelWithUsDescriptionCount();
    }

    function clearWhyTravelWithUsImage() {
        document.getElementById('why_travel_with_us_image_url').value = '';
        document.getElementById('why_travel_with_us_image_preview').innerHTML = '';
        // Also clear image picker if used
        const imageIdInput = document.getElementById('image_id');
        if (imageIdInput) {
            imageIdInput.value = '';
        }
        const imagePickerPreview = document.getElementById('selected_image_preview_image_id');
        if (imagePickerPreview) {
            imagePickerPreview.innerHTML = '<p class="text-muted small">No image selected. Click "Select from Gallery" to choose an image.</p>';
        }
    }

    function updateWhyTravelWithUsDescriptionCount() {
        const textarea = document.getElementById('why_travel_with_us_description');
        const count = document.getElementById('why_travel_with_us_description_count');
        if (textarea && count) {
            count.textContent = textarea.value.length;
        }
    }

    function addWhyTravelWithUs() {
        resetWhyTravelWithUsForm();
        whyTravelWithUsModal.show();
    }
    
    function editWhyTravelWithUs(id) {
        const item = whyTravelWithUsData.find(i => i.id === id);
        if (!item) {
            showToast('error', 'Item not found');
            return;
        }
        
        console.log('Editing item:', item);
        
        resetWhyTravelWithUsForm();
        
        // Set form method and action for update
        const formMethodInput = document.getElementById('why_travel_with_us_form_method');
        const form = document.getElementById('whyTravelWithUsForm');
        const updateUrl = whyTravelWithUsUpdateUrlTemplate.replace('__ID__', id);
        
        if (formMethodInput) {
            formMethodInput.value = 'PUT';
        }
        if (form) {
            form.action = updateUrl;
            console.log('Form action set to:', form.action);
        }
        
        document.getElementById('whyTravelWithUsModalTitle').textContent = 'Edit Item';
        
        // Set form fields
        const titleInput = document.getElementById('why_travel_with_us_title');
        const displayOrderInput = document.getElementById('why_travel_with_us_display_order');
        const isActiveCheckbox = document.getElementById('why_travel_with_us_is_active');
        const isActiveHidden = document.getElementById('why_travel_with_us_is_active_hidden');
        const descriptionInput = document.getElementById('why_travel_with_us_description');
        
        if (titleInput) titleInput.value = item.title ?? '';
        if (displayOrderInput) displayOrderInput.value = item.display_order ?? '';
        if (isActiveCheckbox) {
            isActiveCheckbox.checked = !!item.is_active;
            if (isActiveHidden) {
                isActiveHidden.disabled = isActiveCheckbox.checked;
            }
        }
        if (descriptionInput) descriptionInput.value = item.description ?? '';
        updateWhyTravelWithUsDescriptionCount();
        
        // Handle image_id - the image picker uses 'image_id' as the name/id
        const imageIdInput = document.getElementById('image_id');
        if (imageIdInput) {
            if (item.image_id) {
                imageIdInput.value = item.image_id;
                // Update image picker preview if it exists and we have image data
                const imagePickerPreview = document.getElementById('selected_image_preview_image_id');
                if (imagePickerPreview) {
                    if (item.image && item.image.display_url) {
                        imagePickerPreview.innerHTML = `
                            <div class="border rounded p-2" style="max-width: 300px;">
                                <img src="${item.image.display_url}" alt="${item.image.title || 'Selected Image'}" class="img-fluid rounded" style="max-height: 200px;">
                                <p class="mb-0 mt-1 small"><strong>${item.image.title || 'Selected Image'}</strong></p>
                            </div>
                        `;
                    } else {
                        // If we have image_id but no image data, try to load it
                        imagePickerPreview.innerHTML = '<p class="text-muted small">Loading image preview...</p>';
                    }
                }
            } else {
                imageIdInput.value = '';
                const imagePickerPreview = document.getElementById('selected_image_preview_image_id');
                if (imagePickerPreview) {
                    imagePickerPreview.innerHTML = '<p class="text-muted small">No image selected. Click "Select from Gallery" to choose an image.</p>';
                }
            }
        }
        
        // Handle image_url
        const imageUrlInput = document.getElementById('why_travel_with_us_image_url');
        if (imageUrlInput) {
            if (item.image_url) {
                imageUrlInput.value = item.image_url;
                const fullUrl = item.image_url.startsWith('http') ? item.image_url : '{{ asset("") }}' + item.image_url;
                const preview = document.getElementById('why_travel_with_us_image_preview');
                if (preview) {
                    preview.innerHTML = `
                        <div class="border rounded p-2" style="max-width: 300px;">
                            <img src="${fullUrl}" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;" onclick="previewImage('${fullUrl}', '${item.title}')" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; padding: 20px; text-align: center; color: #999;">
                                <i class="ri-error-warning-line" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Invalid image URL</p>
                            </div>
                        </div>
                    `;
                }
            } else {
                imageUrlInput.value = '';
                const preview = document.getElementById('why_travel_with_us_image_preview');
                if (preview) {
                    preview.innerHTML = '';
                }
            }
        }
        
        whyTravelWithUsModal.show();
    }
    
    function deleteWhyTravelWithUs(id) {
        const item = whyTravelWithUsData.find(i => i.id === id);
        const itemName = item ? item.title : 'this item';
        
        if (confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`)) {
            fetch(`/admin/about-page/why-travel-with-us/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    showToast('success', data.message || 'Item deleted successfully!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', data.message || 'Failed to delete item');
                }
            })
            .catch(error => {
                showToast('error', 'An error occurred while deleting the item');
                console.error('Error:', error);
            });
        }
    }

    // Content Blocks Data
    const contentBlocksData = @json($contentBlocks);

    // Content Blocks Functions
    function addContentBlock() {
        resetContentBlockForm();
        document.getElementById('contentBlockModalTitle').textContent = 'Add Content Block';
        contentBlockModal.show();
    }

    function resetContentBlockForm() {
        const form = document.getElementById('contentBlockForm');
        const formMethodInput = document.getElementById('content_block_form_method');
        
        if (formMethodInput) {
            formMethodInput.value = 'POST';
        }
        if (form) {
            form.action = '/admin/about-page/content-blocks';
        }
        
        document.getElementById('content_block_type').value = 'culture';
        document.getElementById('content_block_title').value = '';
        document.getElementById('content_block_subtitle').value = '';
        document.getElementById('content_block_description').value = '';
        document.getElementById('content_block_content').value = '';
        document.getElementById('content_block_icon').value = '';
        document.getElementById('content_block_image_url').value = '';
        document.getElementById('content_block_button_text').value = '';
        document.getElementById('content_block_button_link').value = '';
        document.getElementById('content_block_display_order').value = '0';
        document.getElementById('content_block_is_active').checked = true;
        
        // Clear image picker
        const imageIdInput = document.getElementById('content_block_image_id');
        if (imageIdInput) imageIdInput.value = '';
        
        // Clear preview
        const preview = document.getElementById('content_block_image_preview');
        if (preview) preview.innerHTML = '';
    }

    function editContentBlock(id) {
        const block = contentBlocksData.find(b => b.id === id);
        if (!block) {
            showToast('error', 'Content block not found');
            return;
        }
        
        // Set form method and action for update
        const formMethodInput = document.getElementById('content_block_form_method');
        const form = document.getElementById('contentBlockForm');
        const updateUrl = `/admin/about-page/content-blocks/${id}`;
        
        if (formMethodInput) {
            formMethodInput.value = 'PUT';
        }
        if (form) {
            form.action = updateUrl;
        }
        
        document.getElementById('contentBlockModalTitle').textContent = 'Edit Content Block';
        
        // Set form fields
        document.getElementById('content_block_type').value = block.block_type || 'culture';
        document.getElementById('content_block_title').value = block.title || '';
        document.getElementById('content_block_subtitle').value = block.subtitle || '';
        document.getElementById('content_block_description').value = block.description || '';
        document.getElementById('content_block_content').value = block.content || '';
        document.getElementById('content_block_icon').value = block.icon || '';
        document.getElementById('content_block_button_text').value = block.button_text || '';
        document.getElementById('content_block_button_link').value = block.button_link || '';
        document.getElementById('content_block_display_order').value = block.display_order || '0';
        document.getElementById('content_block_is_active').checked = block.is_active !== false;
        
        // Handle image_id
        const imageIdInput = document.getElementById('content_block_image_id');
        if (imageIdInput && block.image_id) {
            imageIdInput.value = block.image_id;
        }
        
        // Handle image_url
        const imageUrlInput = document.getElementById('content_block_image_url');
        if (imageUrlInput) {
            if (block.image_url) {
                imageUrlInput.value = block.image_url;
                const fullUrl = block.image_url.startsWith('http') ? block.image_url : '{{ asset("") }}' + block.image_url;
                const preview = document.getElementById('content_block_image_preview');
                if (preview) {
                    preview.innerHTML = `
                        <div class="border rounded p-2" style="max-width: 300px;">
                            <img src="${fullUrl}" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; padding: 20px; text-align: center; color: #999;">
                                <i class="ri-error-warning-line" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Invalid image URL</p>
                            </div>
                        </div>
                    `;
                }
            } else {
                imageUrlInput.value = '';
                const preview = document.getElementById('content_block_image_preview');
                if (preview) preview.innerHTML = '';
            }
        }
        
        contentBlockModal.show();
    }

    function deleteContentBlock(id) {
        const block = contentBlocksData.find(b => b.id === id);
        const blockName = block ? block.title : 'this content block';
        
        if (confirm(`Are you sure you want to delete "${blockName}"? This action cannot be undone.`)) {
            fetch(`/admin/about-page/content-blocks/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    showToast('success', data.message || 'Content block deleted successfully!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', data.message || 'Failed to delete content block');
                }
            })
            .catch(error => {
                showToast('error', 'An error occurred while deleting the content block');
                console.error('Error:', error);
            });
        }
    }

    function clearContentBlockImage() {
        document.getElementById('content_block_image_url').value = '';
        document.getElementById('content_block_image_id').value = '';
        const preview = document.getElementById('content_block_image_preview');
        if (preview) preview.innerHTML = '';
    }

    // Filter and Search Functions
    function filterWhyTravelWithUs() {
        const searchTerm = document.getElementById('whyTravelWithUsSearch').value.toLowerCase();
        const statusFilter = document.getElementById('whyTravelWithUsStatusFilter').value;
        const items = document.querySelectorAll('.why-travel-with-us-item');
        let visibleCount = 0;

        items.forEach(item => {
            const title = item.dataset.title || '';
            const description = item.dataset.description || '';
            const status = item.dataset.status || '';
            
            const matchesSearch = !searchTerm || title.includes(searchTerm) || description.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            
            if (matchesSearch && matchesStatus) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show message if no results
        const container = document.getElementById('whyTravelWithUsContainer');
        let noResultsMsg = container.querySelector('.no-results-message');
        if (visibleCount === 0 && items.length > 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'col-12 no-results-message';
                noResultsMsg.innerHTML = `
                    <div class="alert alert-warning text-center py-4">
                        <i class="ri-search-line" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No items match your search criteria</p>
                    </div>
                `;
                container.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    function sortWhyTravelWithUs() {
        const sortBy = document.getElementById('whyTravelWithUsSortFilter').value;
        const container = document.getElementById('whyTravelWithUsContainer');
        const items = Array.from(container.querySelectorAll('.why-travel-with-us-item'));

        items.sort((a, b) => {
            if (sortBy === 'title') {
                return a.dataset.title.localeCompare(b.dataset.title);
            } else if (sortBy === 'created') {
                return parseInt(b.dataset.id) - parseInt(a.dataset.id);
            } else {
                return parseInt(a.dataset.order) - parseInt(b.dataset.order);
            }
        });

        items.forEach(item => container.appendChild(item));
    }

    function previewImage(url, title) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${url}" alt="${title}" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    }

    // Bulk Actions Functions
    function toggleBulkActions(type) {
        const bar = document.getElementById(type + 'BulkActions');
        if (bar) {
            bar.classList.toggle('active');
            if (!bar.classList.contains('active')) {
                clearSelection(type);
            }
        }
    }

    function toggleSelectAll(type, checked) {
        const checkboxes = document.querySelectorAll('.' + type + '-checkbox');
        checkboxes.forEach(cb => cb.checked = checked);
        updateBulkActions(type);
    }

    function updateBulkActions(type) {
        const checkboxes = document.querySelectorAll('.' + type + '-checkbox:checked');
        const count = checkboxes.length;
        const countSpan = document.getElementById(type + 'SelectedCount');
        if (countSpan) {
            countSpan.textContent = count;
        }
        const bar = document.getElementById(type + 'BulkActions');
        if (bar) {
            if (count > 0) {
                bar.classList.add('active');
            } else {
                bar.classList.remove('active');
            }
        }
    }

    function clearSelection(type) {
        const checkboxes = document.querySelectorAll('.' + type + '-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        const selectAll = document.getElementById(type + 'SelectAll');
        if (selectAll) selectAll.checked = false;
        updateBulkActions(type);
    }

    async function bulkActivate(type) {
        const checkboxes = document.querySelectorAll('.' + type + '-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        if (ids.length === 0) {
            showToast('warning', 'Please select items first');
            return;
        }
        if (!confirm(`Activate ${ids.length} item(s)?`)) return;
        
        await bulkUpdateStatus(type, ids, true);
    }

    async function bulkDeactivate(type) {
        const checkboxes = document.querySelectorAll('.' + type + '-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        if (ids.length === 0) {
            showToast('warning', 'Please select items first');
            return;
        }
        if (!confirm(`Deactivate ${ids.length} item(s)?`)) return;
        
        await bulkUpdateStatus(type, ids, false);
    }

    async function bulkDelete(type) {
        const checkboxes = document.querySelectorAll('.' + type + '-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        if (ids.length === 0) {
            showToast('warning', 'Please select items first');
            return;
        }
        if (!confirm(`Delete ${ids.length} item(s)? This action cannot be undone.`)) return;
        
        try {
            const routeMap = {
                'team': (id) => `/admin/about-page/team-members/${id}`,
                'values': (id) => `/admin/about-page/values/${id}`,
                'recognitions': (id) => `/admin/about-page/recognitions/${id}`,
                'timeline': (id) => `/admin/about-page/timeline-items/${id}`,
                'statistics': (id) => `/admin/about-page/statistics/${id}`,
                'why-travel-with-us': (id) => `/admin/about-page/why-travel-with-us/${id}`
            };
            
            const promises = ids.map(id => {
                const routeFn = routeMap[type] || routeMap['team'];
                return fetch(routeFn(id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            });
            
            await Promise.all(promises);
            showToast('success', `${ids.length} item(s) deleted successfully!`);
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            showToast('error', 'Error deleting items. Please try again.');
            console.error('Error:', error);
        }
    }

    async function bulkUpdateStatus(type, ids, isActive) {
        try {
            const routeMap = {
                'team': '/admin/about-page/team-members',
                'values': '/admin/about-page/values',
                'recognitions': '/admin/about-page/recognitions',
                'timeline': '/admin/about-page/timeline-items',
                'statistics': '/admin/about-page/statistics',
                'why-travel-with-us': '/admin/about-page/why-travel-with-us'
            };
            
            const baseRoute = routeMap[type];
            if (!baseRoute) {
                showToast('error', 'Invalid type for bulk update');
                return;
            }
            
            const promises = ids.map(id => {
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('is_active', isActive ? '1' : '0');
                
                return fetch(`${baseRoute}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
            });
            
            const results = await Promise.all(promises);
            const jsonResults = await Promise.all(results.map(r => r.json()));
            
            const allSuccess = jsonResults.every(r => r.success !== false);
            if (allSuccess) {
                showToast('success', `${ids.length} item(s) ${isActive ? 'activated' : 'deactivated'} successfully!`);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', 'Some items could not be updated. Please try again.');
            }
        } catch (error) {
            showToast('error', 'Error updating items. Please try again.');
            console.error('Error:', error);
        }
    }

    // Drag and Drop Reordering (using SortableJS if available, otherwise manual)
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize drag and drop for sortable items
        const sortableContainers = document.querySelectorAll('.sortable-item');
        
        // Simple drag and drop implementation
        sortableContainers.forEach(item => {
            item.setAttribute('draggable', 'true');
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragend', handleDragEnd);
        });
    });

    let draggedElement = null;

    function handleDragStart(e) {
        draggedElement = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        if (draggedElement !== this) {
            const container = this.closest('tbody, .row');
            if (container) {
                const allItems = Array.from(container.querySelectorAll('.sortable-item'));
                const draggedIndex = allItems.indexOf(draggedElement);
                const targetIndex = allItems.indexOf(this);
                
                if (draggedIndex < targetIndex) {
                    container.insertBefore(draggedElement, this.nextSibling);
                } else {
                    container.insertBefore(draggedElement, this);
                }
                
                // Update display orders
                updateDisplayOrders(container);
            }
        }
        return false;
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        draggedElement = null;
    }

    async function updateDisplayOrders(container) {
        const items = container.querySelectorAll('.sortable-item');
        const updates = [];
        
        items.forEach((item, index) => {
            const id = item.dataset.id;
            const newOrder = index + 1;
            const type = container.closest('[id*="team"], [id*="values"], [id*="recognitions"], [id*="timeline"], [id*="statistics"], [id*="why-travel-with-us"], [id*="contentBlocks"]')?.id || 'team';
            
            // Determine the route based on container
            let route = '/admin/about-page/team-members';
            if (container.closest('#valuesContainer')) route = '/admin/about-page/values';
            else if (container.closest('#recognitionsContainer')) route = '/admin/about-page/recognitions';
            else if (container.closest('#timelineTableBody')) route = '/admin/about-page/timeline-items';
            else if (container.closest('#statisticsContainer')) route = '/admin/about-page/statistics';
            else if (container.closest('#whyTravelWithUsContainer')) route = '/admin/about-page/why-travel-with-us';
            else if (container.closest('#contentBlocksContainer')) route = '/admin/about-page/content-blocks';
            
            updates.push(
                fetch(`${route}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ display_order: newOrder })
                })
            );
        });
        
        try {
            await Promise.all(updates);
            showToast('success', 'Display order updated successfully!');
            // Update order badges
            items.forEach((item, index) => {
                const badge = item.querySelector('.badge.bg-label-info');
                if (badge) badge.textContent = `Order: ${index + 1}`;
                item.dataset.order = index + 1;
            });
        } catch (error) {
            showToast('error', 'Error updating display order. Please try again.');
            console.error('Error:', error);
        }
    }

    // Image preview for Why Travel With Us
    document.addEventListener('DOMContentLoaded', function() {
        const whyImageUrlInput = document.getElementById('why_travel_with_us_image_url');
        if (whyImageUrlInput) {
            whyImageUrlInput.addEventListener('input', function() {
                const url = this.value.trim();
                const preview = document.getElementById('why_travel_with_us_image_preview');
                if (url) {
                    const fullUrl = url.startsWith('http') ? url : '{{ asset("") }}' + url;
                    preview.innerHTML = `
                        <div class="border rounded p-2" style="max-width: 300px;">
                            <img src="${fullUrl}" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;" onclick="previewImage('${fullUrl}', 'Image Preview')" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
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
        }

        // Description character counter
        const whyDescriptionTextarea = document.getElementById('why_travel_with_us_description');
        if (whyDescriptionTextarea) {
            whyDescriptionTextarea.addEventListener('input', updateWhyTravelWithUsDescriptionCount);
            updateWhyTravelWithUsDescriptionCount();
        }

        // Image preview for Values
        const valueImageUrlInput = document.getElementById('value_image_url');
        if (valueImageUrlInput) {
            valueImageUrlInput.addEventListener('input', function() {
                const url = this.value.trim();
                const preview = document.getElementById('value_image_preview');
                if (url) {
                    const fullUrl = url.startsWith('http') ? url : '{{ asset("") }}' + url;
                    preview.innerHTML = `<img src="${fullUrl}" alt="Preview" class="img-thumbnail" style="max-width: 200px; margin-top: 10px;" onerror="this.style.display='none'">`;
                } else {
                    preview.innerHTML = '';
                }
            });
        }

        // Image preview for Content Blocks
        const contentBlockImageUrlInput = document.getElementById('content_block_image_url');
        if (contentBlockImageUrlInput) {
            contentBlockImageUrlInput.addEventListener('input', function() {
                const url = this.value.trim();
                const preview = document.getElementById('content_block_image_preview');
                if (url) {
                    const fullUrl = url.startsWith('http') ? url : '{{ asset("") }}' + url;
                    preview.innerHTML = `
                        <div class="border rounded p-2" style="max-width: 300px;">
                            <img src="${fullUrl}" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;" onclick="previewImage('${fullUrl}', 'Image Preview')" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
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
        }
    });
    
    // ========== CLOUDINARY PICKER FOR VALUES ==========
    let cloudinaryPickerTarget = null;
    let cloudinaryPickerModal = null;
    
    function openCloudinaryPickerForValue() {
        cloudinaryPickerTarget = 'value_image_url';
        if (!cloudinaryPickerModal) {
            cloudinaryPickerModal = new bootstrap.Modal(document.getElementById('cloudinaryPickerModal'));
        }
        loadCloudinaryAssets();
        cloudinaryPickerModal.show();
    }
    
    function loadCloudinaryAssets() {
        const grid = document.getElementById('cloudinaryPickerGrid');
        grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-warning"></div><p class="mt-2">Loading...</p></div>';
        
        fetch('{{ route("admin.cloudinary.assets") }}?max_results=100', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            grid.innerHTML = '';
            if (!data.success || !data.resources || data.resources.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No images found. Make sure CLOUDINARY_URL is set.</p></div>';
                return;
            }
            data.resources.forEach(asset => {
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6 mb-3';
                col.innerHTML = `
                    <div class="card h-100" style="cursor: pointer;" onclick="selectCloudinaryAsset('${asset.url.replace(/'/g, "\\'")}')">
                        <img src="${asset.url}" class="card-img-top" style="height: 120px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-truncate d-block">${asset.filename}</small>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        })
        .catch(err => {
            grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Failed to load Cloudinary assets</p></div>';
        });
    }
    
    function selectCloudinaryAsset(url) {
        if (cloudinaryPickerTarget) {
            document.getElementById(cloudinaryPickerTarget).value = url;
            // Trigger input event for preview
            document.getElementById(cloudinaryPickerTarget).dispatchEvent(new Event('input'));
        }
        cloudinaryPickerModal.hide();
    }
</script>

<!-- Cloudinary Picker Modal -->
<div class="modal fade" id="cloudinaryPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="ri-cloud-line me-2"></i>Select from Cloudinary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="cloudinaryPickerGrid" style="max-height: 500px; overflow-y: auto;">
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Click to load images...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection

