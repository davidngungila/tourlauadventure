@extends('admin.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Homepage Activities - Lau Paradise Adventures')
@section('description', 'Manage homepage activities')

@push('styles')
<style>
    .activity-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ri-grid-line me-2"></i>Homepage Activities</h4>
                    <a href="{{ route('admin.homepage.activities.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Add Activity
                    </a>
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
                                <i class="ri-grid-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                            <small class="text-muted">Total Activities</small>
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
                            <h5 class="mb-0">{{ $stats['active'] ?? 0 }}</h5>
                            <small class="text-muted">Active Activities</small>
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
                                <i class="ri-eye-off-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $stats['inactive'] ?? 0 }}</h5>
                            <small class="text-muted">Inactive Activities</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.homepage.activities') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search activities..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line me-1"></i>Filter
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.homepage.activities') }}" class="btn btn-label-secondary w-100">
                                    <i class="ri-refresh-line me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Icon</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                            <tr>
                                <td>
                                    @if($activity->display_image_url)
                                    <img src="{{ $activity->display_image_url }}" alt="{{ $activity->name }}" class="activity-image">
                                    @else
                                    <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td><strong>{{ $activity->name }}</strong></td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($activity->description ?? 'No description', 60) }}</small>
                                </td>
                                <td>
                                    @if($activity->icon)
                                    <i class="{{ $activity->icon }}" style="font-size: 1.5rem;"></i>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $activity->display_order }}</td>
                                <td>
                                    @if($activity->is_active)
                                    <span class="badge bg-label-success">Active</span>
                                    @else
                                    <span class="badge bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.homepage.activities.edit', $activity->id) }}">
                                                <i class="ri-pencil-line me-2"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.homepage.activities.destroy', $activity->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this activity?')">
                                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted">No activities found. <a href="{{ route('admin.homepage.activities.create') }}">Create one</a></p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($activities->hasPages())
                <div class="card-footer">
                    {{ $activities->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

