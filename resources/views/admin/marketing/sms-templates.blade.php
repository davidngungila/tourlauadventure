@extends('admin.layouts.app')

@section('title', 'SMS Templates')
@section('description', 'Manage SMS templates for marketing campaigns')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-message-3-line me-2"></i>SMS Templates
                        </h4>
                        <p class="text-muted mb-0">Manage SMS templates for marketing campaigns and notifications</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.marketing.sms-templates.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Create Template
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
                    <form method="GET" action="{{ route('admin.marketing.sms-templates') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search templates..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.marketing.sms-templates') }}" class="btn btn-outline-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Key</th>
                                    <th>Message Preview</th>
                                    <th>Variables</th>
                                    <th>Status</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($smsTemplates ?? [] as $template)
                                <tr>
                                    <td>
                                        <strong>{{ $template->name }}</strong>
                                        @if($template->description)
                                        <br><small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $template->key }}</code></td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($template->message, 60) }}</span>
                                        <br><small class="text-info">{{ Str::length($template->message) }}/160 characters</small>
                                    </td>
                                    <td>
                                        @if($template->variables && count($template->variables) > 0)
                                            @foreach(array_slice($template->variables, 0, 3) as $var)
                                                <span class="badge bg-label-info me-1">{{ '{' . $var . '}' }}</span>
                                            @endforeach
                                            @if(count($template->variables) > 3)
                                                <span class="text-muted">+{{ count($template->variables) - 3 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->is_active)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $template->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.marketing.sms-templates.edit', $template->id) }}" class="btn btn-sm btn-icon">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button class="btn btn-sm btn-icon text-info" onclick="previewTemplate({{ $template->id }})" title="Preview">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <form action="{{ route('admin.marketing.sms-templates.destroy', $template->id) }}" method="POST" class="delete-form">
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
                                            <i class="ri-message-3-line icon-48px mb-2 d-block"></i>
                                            <p>No SMS templates found</p>
                                            <a href="{{ route('admin.marketing.sms-templates.create') }}" class="btn btn-primary btn-sm">Create Your First Template</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($smsTemplates) && $smsTemplates->hasPages())
                    <div class="mt-4">
                        {{ $smsTemplates->links() }}
                    </div>
                    @endif
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
                                <li><code>{{ '{name}' }}</code> - User's full name</li>
                                <li><code>{{ '{email}' }}</code> - User's email address</li>
                                <li><code>{{ '{first_name}' }}</code> - User's first name</li>
                                <li><code>{{ '{phone}' }}</code> - User's phone number</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Booking Variables</h6>
                            <ul class="list-unstyled">
                                <li><code>{{ '{booking_reference}' }}</code> - Booking reference number</li>
                                <li><code>{{ '{tour_name}' }}</code> - Tour name</li>
                                <li><code>{{ '{departure_date}' }}</code> - Departure date</li>
                                <li><code>{{ '{total_price}' }}</code> - Total booking price</li>
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
    // TODO: Implement template preview modal
    alert('Template preview functionality will be implemented soon.');
}
</script>
@endpush






