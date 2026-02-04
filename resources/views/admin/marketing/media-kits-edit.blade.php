@extends('admin.layouts.app')

@section('title', 'Edit Media Kit')
@section('description', 'Edit media kit')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Media Kit
                    </h4>
                    <a href="{{ route('admin.marketing.media-kits') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.media-kits.update', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $mediaKit->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $mediaKit->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $mediaKit->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Information</label>
                                <textarea name="company_info" class="form-control @error('company_info') is-invalid @enderror" rows="4">{{ old('company_info', $mediaKit->company_info ?? '') }}</textarea>
                                @error('company_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Information (JSON)</label>
                                <textarea name="contact_info" class="form-control @error('contact_info') is-invalid @enderror" rows="4">{{ old('contact_info', isset($mediaKit->contact_info) ? json_encode($mediaKit->contact_info, JSON_PRETTY_PRINT) : '') }}</textarea>
                                @error('contact_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Download URL</label>
                                <input type="url" name="download_url" class="form-control @error('download_url') is-invalid @enderror" value="{{ old('download_url', $mediaKit->download_url ?? '') }}">
                                @error('download_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status', $mediaKit->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $mediaKit->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $mediaKit->status ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.marketing.media-kits') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Media Kit</button>
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






