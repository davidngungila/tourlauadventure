@extends('admin.layouts.app')

@section('title', 'Edit Event')
@section('description', 'Edit event')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Event
                    </h4>
                    <a href="{{ route('admin.marketing.events') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.events.update', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $event->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                <select name="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
                                    <option value="conference" {{ old('event_type', $event->event_type ?? '') == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="workshop" {{ old('event_type', $event->event_type ?? '') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="exhibition" {{ old('event_type', $event->event_type ?? '') == 'exhibition' ? 'selected' : '' }}>Exhibition</option>
                                    <option value="networking" {{ old('event_type', $event->event_type ?? '') == 'networking' ? 'selected' : '' }}>Networking</option>
                                    <option value="webinar" {{ old('event_type', $event->event_type ?? '') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                    <option value="other" {{ old('event_type', $event->event_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('event_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', isset($event->event_date) ? $event->event_date->format('Y-m-d') : '') }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Time</label>
                                <input type="time" name="event_time" class="form-control @error('event_time') is-invalid @enderror" value="{{ old('event_time', $event->event_time ?? '') }}">
                                @error('event_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($event->end_date) ? $event->end_date->format('Y-m-d') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $event->end_time ?? '') }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $event->location ?? '') }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Venue</label>
                                <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror" value="{{ old('venue', $event->venue ?? '') }}">
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $event->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $event->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $event->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Organizer</label>
                                <input type="text" name="organizer" class="form-control @error('organizer') is-invalid @enderror" value="{{ old('organizer', $event->organizer ?? '') }}">
                                @error('organizer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $event->contact_email ?? '') }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $event->contact_phone ?? '') }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ticket Price</label>
                                <input type="number" name="ticket_price" step="0.01" class="form-control @error('ticket_price') is-invalid @enderror" value="{{ old('ticket_price', $event->ticket_price ?? '') }}">
                                @error('ticket_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Max Attendees</label>
                                <input type="number" name="max_attendees" class="form-control @error('max_attendees') is-invalid @enderror" value="{{ old('max_attendees', $event->max_attendees ?? '') }}">
                                @error('max_attendees')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ticket URL</label>
                                <input type="url" name="ticket_url" class="form-control @error('ticket_url') is-invalid @enderror" value="{{ old('ticket_url', $event->ticket_url ?? '') }}">
                                @error('ticket_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image</label>
                                @if(isset($event) && $event->featured_image)
                                    <div class="mb-2">
                                        <img src="{{ $event->featured_image }}" alt="Featured" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status', $event->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $event->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="cancelled" {{ old('status', $event->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ old('status', $event->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $event->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Featured Event</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.marketing.events') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content');
</script>
@endpush






