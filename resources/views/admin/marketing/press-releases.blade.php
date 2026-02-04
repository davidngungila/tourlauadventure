@extends('admin.layouts.app')

@section('title', 'Press Releases')
@section('description', 'Manage press releases and media announcements')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-newspaper-line me-2"></i>Press Releases
                        </h4>
                        <p class="text-muted mb-0">Manage press releases and media announcements</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.marketing.press-releases.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Create Press Release
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
                    <form method="GET" action="{{ route('admin.marketing.press-releases') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search press releases..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" placeholder="From Date" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" placeholder="To Date" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Press Releases Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Release Date</th>
                                    <th>Author</th>
                                    <th>Views</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pressReleases ?? [] as $release)
                                <tr>
                                    <td>
                                        <strong>{{ $release->title }}</strong>
                                        @if($release->excerpt)
                                        <br><small class="text-muted">{{ Str::limit($release->excerpt, 80) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($release->category)
                                            <span class="badge bg-label-info">{{ $release->category }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $release->release_date->format('M d, Y') }}</td>
                                    <td>{{ $release->author ?? 'N/A' }}</td>
                                    <td>{{ number_format($release->views) }}</td>
                                    <td>
                                        @if($release->status == 'published')
                                            <span class="badge bg-label-success">Published</span>
                                        @elseif($release->status == 'archived')
                                            <span class="badge bg-label-secondary">Archived</span>
                                        @else
                                            <span class="badge bg-label-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.marketing.press-releases.edit', $release->id) }}" class="btn btn-sm btn-icon">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="{{ route('admin.marketing.press-releases.show', $release->id) }}" class="btn btn-sm btn-icon text-info" target="_blank" title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <form action="{{ route('admin.marketing.press-releases.destroy', $release->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon text-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-newspaper-line icon-48px mb-2 d-block"></i>
                                            <p>No press releases found</p>
                                            <a href="{{ route('admin.marketing.press-releases.create') }}" class="btn btn-primary btn-sm">Create Your First Press Release</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($pressReleases) && $pressReleases->hasPages())
                    <div class="mt-4">
                        {{ $pressReleases->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






