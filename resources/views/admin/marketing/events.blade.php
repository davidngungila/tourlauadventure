@extends('admin.layouts.app')

@section('title', 'Events')
@section('description', 'Manage events and activities')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-calendar-event-line me-2"></i>Events
                        </h4>
                        <p class="text-muted mb-0">Manage events, conferences, and activities</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.marketing.events.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Create Event
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
                    <form method="GET" action="{{ route('admin.marketing.events') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search events..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="event_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="conference" {{ request('event_type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="workshop" {{ request('event_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="exhibition" {{ request('event_type') == 'exhibition' ? 'selected' : '' }}>Exhibition</option>
                                    <option value="networking" {{ request('event_type') == 'networking' ? 'selected' : '' }}>Networking</option>
                                    <option value="webinar" {{ request('event_type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
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

    <!-- Events Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Type</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>Attendees</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events ?? [] as $event)
                                <tr>
                                    <td>
                                        <strong>{{ $event->title }}</strong>
                                        @if($event->is_featured)
                                            <span class="badge bg-label-primary ms-1">Featured</span>
                                        @endif
                                        @if($event->description)
                                        <br><small class="text-muted">{{ Str::limit($event->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ ucfirst($event->event_type) }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $event->event_date->format('M d, Y') }}</strong>
                                        @if($event->event_time)
                                        <br><small class="text-muted">{{ $event->event_time }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->location)
                                            {{ $event->location }}
                                            @if($event->venue)
                                                <br><small class="text-muted">{{ $event->venue }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ number_format($event->registered_attendees) }}
                                        @if($event->max_attendees)
                                            / {{ number_format($event->max_attendees) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->status == 'published')
                                            <span class="badge bg-label-success">Published</span>
                                        @elseif($event->status == 'cancelled')
                                            <span class="badge bg-label-danger">Cancelled</span>
                                        @elseif($event->status == 'completed')
                                            <span class="badge bg-label-secondary">Completed</span>
                                        @else
                                            <span class="badge bg-label-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.marketing.events.edit', $event->id) }}" class="btn btn-sm btn-icon">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="{{ route('admin.marketing.events.show', $event->id) }}" class="btn btn-sm btn-icon text-info" target="_blank" title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <form action="{{ route('admin.marketing.events.destroy', $event->id) }}" method="POST" class="delete-form">
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
                                            <i class="ri-calendar-event-line icon-48px mb-2 d-block"></i>
                                            <p>No events found</p>
                                            <a href="{{ route('admin.marketing.events.create') }}" class="btn btn-primary btn-sm">Create Your First Event</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($events) && $events->hasPages())
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






