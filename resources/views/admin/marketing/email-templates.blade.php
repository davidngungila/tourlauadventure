@extends('admin.layouts.app')

@section('title', 'Email Templates')
@section('description', 'Manage email templates for marketing campaigns')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-mail-send-line me-2"></i>Email Templates
                        </h4>
                        <p class="text-muted mb-0">Manage email templates for marketing campaigns and communications</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.marketing.email-templates.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Create Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="ri-information-line me-2"></i>
                <strong>Note:</strong> Email templates are used in marketing campaigns. You can also manage system email templates in 
                <a href="{{ route('admin.settings.email-smtp') }}#email-templates" class="alert-link">Email Settings</a>.
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row g-4">
        <!-- Welcome Email Template -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-mail-open-line me-2"></i>Welcome Email
                    </h5>
                    <span class="badge bg-label-success">Active</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Sent to new subscribers when they join the newsletter.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.email-templates.edit', 1) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="previewTemplate(1)">
                            <i class="ri-eye-line me-1"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter Template -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-newspaper-line me-2"></i>Newsletter Template
                    </h5>
                    <span class="badge bg-label-success">Active</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Template for regular newsletter campaigns to subscribers.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.email-templates.edit', 2) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="previewTemplate(2)">
                            <i class="ri-eye-line me-1"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotional Email Template -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-price-tag-3-line me-2"></i>Promotional Email
                    </h5>
                    <span class="badge bg-label-success">Active</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Template for promotional campaigns and special offers.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.email-templates.edit', 3) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="previewTemplate(3)">
                            <i class="ri-eye-line me-1"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Confirmation Template -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-calendar-check-line me-2"></i>Booking Confirmation
                    </h5>
                    <span class="badge bg-label-success">Active</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Sent to customers when their booking is confirmed.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.email-templates.edit', 4) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="previewTemplate(4)">
                            <i class="ri-eye-line me-1"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Abandoned Cart Template -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-shopping-cart-line me-2"></i>Abandoned Booking
                    </h5>
                    <span class="badge bg-label-warning">Draft</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Reminder email for incomplete bookings or inquiries.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.email-templates.edit', 5) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="previewTemplate(5)">
                            <i class="ri-eye-line me-1"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow-up Template -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-customer-service-2-line me-2"></i>Follow-up Email
                    </h5>
                    <span class="badge bg-label-success">Active</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Follow-up emails after tour completion or inquiry.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.marketing.email-templates.edit', 6) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="previewTemplate(6)">
                            <i class="ri-eye-line me-1"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Variables Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-code-s-slash-line me-2"></i>Available Template Variables
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">User Variables</h6>
                            <ul class="list-unstyled">
                                <li><code>{{ '{' }}{{ 'name' }}{{ '}' }}</code> - User's full name</li>
                                <li><code>{{ '{' }}{{ 'email' }}{{ '}' }}</code> - User's email address</li>
                                <li><code>{{ '{' }}{{ 'first_name' }}{{ '}' }}</code> - User's first name</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">System Variables</h6>
                            <ul class="list-unstyled">
                                <li><code>{{ '{' }}{{ 'app_name' }}{{ '}' }}</code> - Application name</li>
                                <li><code>{{ '{' }}{{ 'company_name' }}{{ '}' }}</code> - Company name</li>
                                <li><code>{{ '{' }}{{ 'contact_email' }}{{ '}' }}</code> - Contact email</li>
                                <li><code>{{ '{' }}{{ 'contact_phone' }}{{ '}' }}</code> - Contact phone</li>
                            </ul>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h6 class="mb-3">Booking Variables</h6>
                            <ul class="list-unstyled">
                                <li><code>{{ '{' }}{{ 'booking_reference' }}{{ '}' }}</code> - Booking reference number</li>
                                <li><code>{{ '{' }}{{ 'tour_name' }}{{ '}' }}</code> - Tour name</li>
                                <li><code>{{ '{' }}{{ 'departure_date' }}{{ '}' }}</code> - Departure date</li>
                                <li><code>{{ '{' }}{{ 'total_price' }}{{ '}' }}</code> - Total booking price</li>
                            </ul>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h6 class="mb-3">Campaign Variables</h6>
                            <ul class="list-unstyled">
                                <li><code>{{ '{' }}{{ 'campaign_name' }}{{ '}' }}</code> - Campaign name</li>
                                <li><code>{{ '{' }}{{ 'unsubscribe_link' }}{{ '}' }}</code> - Unsubscribe link</li>
                                <li><code>{{ '{' }}{{ 'view_online_link' }}{{ '}' }}</code> - View email online link</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewTemplate(id) {
    // TODO: Implement template preview
    alert('Template preview functionality will be implemented soon.');
}
</script>
@endpush






