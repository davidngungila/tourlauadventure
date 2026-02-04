@extends('admin.layouts.app')

@section('title', 'Media Kits')
@section('description', 'Manage media kits and press resources')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-folder-media-line me-2"></i>Media Kits
                        </h4>
                        <p class="text-muted mb-0">Manage media kits and press resources for journalists and partners</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.marketing.media-kits.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Create Media Kit
                        </a>
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
                    <form method="GET" action="{{ route('admin.marketing.media-kits') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search media kits..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.marketing.media-kits') }}" class="btn btn-outline-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Kits Grid -->
    <div class="row g-4">
        @forelse($mediaKits ?? [] as $kit)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $kit->title }}</h5>
                    @if($kit->status == 'published')
                        <span class="badge bg-label-success">Published</span>
                    @elseif($kit->status == 'archived')
                        <span class="badge bg-label-secondary">Archived</span>
                    @else
                        <span class="badge bg-label-warning">Draft</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($kit->description)
                    <p class="text-muted mb-3">{{ Str::limit($kit->description, 100) }}</p>
                    @endif
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="ri-download-line me-1"></i>{{ number_format($kit->downloads) }} downloads
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.media-kits.edit', $kit->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        @if($kit->download_url)
                        <a href="{{ $kit->download_url }}" class="btn btn-sm btn-outline-info" target="_blank">
                            <i class="ri-download-line me-1"></i>Download
                        </a>
                        @endif
                        <form action="{{ route('admin.marketing.media-kits.destroy', $kit->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="ri-folder-media-line icon-48px mb-2 d-block"></i>
                        <p>No media kits found</p>
                        <a href="{{ route('admin.marketing.media-kits.create') }}" class="btn btn-primary btn-sm">Create Your First Media Kit</a>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    @if(isset($mediaKits) && $mediaKits->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            {{ $mediaKits->links() }}
        </div>
    </div>
    @endif
</div>
@endsection






