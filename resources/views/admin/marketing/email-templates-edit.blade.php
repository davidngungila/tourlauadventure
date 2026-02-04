@extends('admin.layouts.app')

@section('title', 'Edit Email Template')
@section('description', 'Edit email template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Email Template
                    </h4>
                    <a href="{{ route('admin.marketing.email-templates') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.email-templates.update', $id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Template Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $emailTemplate->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Template Key <span class="text-danger">*</span></label>
                                <input type="text" name="key" class="form-control @error('key') is-invalid @enderror" value="{{ old('key', $emailTemplate->key ?? '') }}" required readonly>
                                <small class="form-text text-muted">Key cannot be changed after creation</small>
                                @error('key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $emailTemplate->subject ?? '') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $emailTemplate->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">HTML Body</label>
                                <textarea name="body_html" id="body_html" class="form-control @error('body_html') is-invalid @enderror" rows="15">{{ old('body_html', $emailTemplate->body_html ?? '') }}</textarea>
                                <small class="form-text text-muted">HTML content for the email body</small>
                                @error('body_html')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Plain Text Body</label>
                                <textarea name="body_text" class="form-control @error('body_text') is-invalid @enderror" rows="8">{{ old('body_text', $emailTemplate->body_text ?? '') }}</textarea>
                                <small class="form-text text-muted">Plain text version of the email (fallback for email clients that don't support HTML)</small>
                                @error('body_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $emailTemplate->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Template Variables Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ri-code-s-slash-line me-2"></i>Available Template Variables
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="mb-3">User Variables</h6>
                                                <ul class="list-unstyled">
                                                    <li><code>{{ '{name}' }}</code> - User's full name</li>
                                                    <li><code>{{ '{email}' }}</code> - User's email address</li>
                                                    <li><code>{{ '{first_name}' }}</code> - User's first name</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="mb-3">System Variables</h6>
                                                <ul class="list-unstyled">
                                                    <li><code>{{ '{app_name}' }}</code> - Application name</li>
                                                    <li><code>{{ '{company_name}' }}</code> - Company name</li>
                                                    <li><code>{{ '{contact_email}' }}</code> - Contact email</li>
                                                    <li><code>{{ '{contact_phone}' }}</code> - Contact phone</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <h6 class="mb-3">Booking Variables</h6>
                                                <ul class="list-unstyled">
                                                    <li><code>{{ '{booking_reference}' }}</code> - Booking reference number</li>
                                                    <li><code>{{ '{tour_name}' }}</code> - Tour name</li>
                                                    <li><code>{{ '{departure_date}' }}</code> - Departure date</li>
                                                    <li><code>{{ '{total_price}' }}</code> - Total booking price</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <h6 class="mb-3">Campaign Variables</h6>
                                                <ul class="list-unstyled">
                                                    <li><code>{{ '{campaign_name}' }}</code> - Campaign name</li>
                                                    <li><code>{{ '{unsubscribe_link}' }}</code> - Unsubscribe link</li>
                                                    <li><code>{{ '{view_online_link}' }}</code> - View email online link</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.marketing.email-templates') }}" class="btn btn-outline-secondary">Cancel</a>
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
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('body_html', {
    height: 400,
    toolbar: [
        { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
        { name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
        '/',
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
        { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
        { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
        '/',
        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
        { name: 'about', items: ['About'] }
    ]
});
</script>
@endpush






