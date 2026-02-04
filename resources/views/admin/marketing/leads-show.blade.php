@extends('admin.layouts.app')

@section('title', 'Lead Details')
@section('description', 'View lead details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-user-search-line me-2"></i>Lead Details
                    </h4>
                    <a href="{{ route('admin.marketing.leads') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Leads
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">
                                <i class="ri-user-line me-2"></i>Customer Information
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Name:</th>
                                        <td><strong>{{ $lead->customer_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $lead->customer_email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $lead->customer_phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country:</th>
                                        <td>{{ $lead->customer_country ?? 'N/A' }}</td>
                                    </tr>
                                    @if($lead->user)
                                    <tr>
                                        <th>User Account:</th>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $lead->user->id) }}" class="text-primary">
                                                {{ $lead->user->name }} ({{ $lead->user->email }})
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Booking Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">
                                <i class="ri-calendar-check-line me-2"></i>Booking Information
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Reference:</th>
                                        <td><strong>{{ $lead->booking_reference }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
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
                                    </tr>
                                    <tr>
                                        <th>Tour:</th>
                                        <td>
                                            @if($lead->tour)
                                                <strong>{{ $lead->tour->name }}</strong>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Departure Date:</th>
                                        <td>
                                            @if($lead->departure_date)
                                                {{ $lead->departure_date->format('F j, Y') }}
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Number of Guests:</th>
                                        <td>{{ $lead->number_of_guests ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $lead->created_at->format('F j, Y g:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">
                                <i class="ri-money-dollar-circle-line me-2"></i>Financial Information
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Total Cost:</th>
                                        <td><strong>{{ number_format($lead->total_cost, 2) }} {{ config('app.currency', 'TZS') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Deposit Amount:</th>
                                        <td>{{ number_format($lead->deposit_amount ?? 0, 2) }} {{ config('app.currency', 'TZS') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Balance Amount:</th>
                                        <td>{{ number_format($lead->balance_amount ?? 0, 2) }} {{ config('app.currency', 'TZS') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td>{{ ucfirst($lead->payment_method ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status:</th>
                                        <td>
                                            @if($lead->payment_status == 'paid')
                                                <span class="badge bg-label-success">Paid</span>
                                            @elseif($lead->payment_status == 'partial')
                                                <span class="badge bg-label-warning">Partial</span>
                                            @else
                                                <span class="badge bg-label-danger">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">
                                <i class="ri-information-line me-2"></i>Additional Information
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    @if($lead->special_requests)
                                    <tr>
                                        <th width="40%">Special Requests:</th>
                                        <td>{{ $lead->special_requests }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->notes)
                                    <tr>
                                        <th>Notes:</th>
                                        <td>{{ $lead->notes }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $lead->updated_at->format('F j, Y g:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.bookings.show', $lead->id) }}?edit=1" class="btn btn-primary">
                                    <i class="ri-edit-line me-1"></i>Edit Booking
                                </a>
                                <a href="{{ route('admin.marketing.leads') }}" class="btn btn-outline-secondary">
                                    <i class="ri-arrow-left-line me-1"></i>Back to Leads
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

