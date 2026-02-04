@extends('admin.layouts.app')

@section('title', 'Lead Management')
@section('description', 'Manage marketing leads from bookings and inquiries')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ri-user-search-line me-2"></i>Lead Management
                        </h4>
                        <p class="text-muted mb-0">Manage leads from bookings and inquiries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="ri-user-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Total Leads</div>
                                <h5 class="mb-0">{{ number_format($totalLeads) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded">
                                    <i class="ri-time-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Pending</div>
                                <h5 class="mb-0">{{ number_format($leads->where('status', 'pending')->count()) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="ri-question-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Inquiries</div>
                                <h5 class="mb-0">{{ number_format($leads->where('status', 'inquiry')->count()) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="ri-money-dollar-circle-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">Pending Payment</div>
                                <h5 class="mb-0">{{ number_format($leads->where('status', 'pending_payment')->count()) }}</h5>
                            </div>
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
                    <form method="GET" action="{{ route('admin.marketing.leads') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or reference..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inquiry" {{ request('status') == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                                    <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.marketing.leads') }}" class="btn btn-outline-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Customer</th>
                                    <th>Tour</th>
                                    <th>Departure Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leads as $lead)
                                <tr>
                                    <td>
                                        <strong>{{ $lead->booking_reference }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $lead->customer_name }}</strong>
                                            <br><small class="text-muted">{{ $lead->customer_email }}</small>
                                            @if($lead->customer_phone)
                                            <br><small class="text-muted">{{ $lead->customer_phone }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($lead->tour)
                                            <strong>{{ $lead->tour->name }}</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lead->departure_date)
                                            {{ $lead->departure_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($lead->total_cost, 2) }} {{ config('app.currency', 'TZS') }}</strong>
                                    </td>
                                    <td>
                                        @if($lead->status == 'pending')
                                            <span class="badge bg-label-warning">Pending</span>
                                        @elseif($lead->status == 'inquiry')
                                            <span class="badge bg-label-info">Inquiry</span>
                                        @elseif($lead->status == 'pending_payment')
                                            <span class="badge bg-label-success">Pending Payment</span>
                                        @else
                                            <span class="badge bg-label-secondary">{{ ucfirst($lead->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $lead->created_at->diffForHumans() }}</small>
                                        <br><small class="text-muted">{{ $lead->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.marketing.leads.show', $lead->id) }}" class="btn btn-sm btn-icon text-info" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.bookings.show', $lead->id) }}?edit=1" class="btn btn-sm btn-icon" title="Edit Booking">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-user-search-line icon-48px mb-2 d-block"></i>
                                            <p>No leads found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($leads->hasPages())
                    <div class="mt-4">
                        {{ $leads->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

