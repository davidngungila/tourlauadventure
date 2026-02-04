@extends('admin.layouts.app')

@section('title', 'Edit SMS Template')
@section('description', 'Edit SMS template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit SMS Template
                    </h4>
                    <a href="{{ route('admin.marketing.sms-templates') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.sms-templates.update', $id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Template Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $smsTemplate->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Template Key <span class="text-danger">*</span></label>
                                <input type="text" name="key" class="form-control @error('key') is-invalid @enderror" value="{{ old('key', $smsTemplate->key ?? '') }}" required readonly>
                                <small class="form-text text-muted">Key cannot be changed after creation</small>
                                @error('key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="4" maxlength="160" required>{{ old('message', $smsTemplate->message ?? '') }}</textarea>
                                <small class="form-text text-muted">
                                    <span id="charCount">{{ Str::length(old('message', $smsTemplate->message ?? '')) }}</span>/160 characters (SMS limit)
                                </small>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $smsTemplate->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $smsTemplate->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.marketing.sms-templates') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Template</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.querySelector('textarea[name="message"]');
    const charCount = document.getElementById('charCount');
    
    if (messageTextarea && charCount) {
        function updateCharCount() {
            const length = messageTextarea.value.length;
            charCount.textContent = length;
            if (length > 160) {
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-danger');
            }
        }
        
        messageTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }
});
</script>
@endpush






