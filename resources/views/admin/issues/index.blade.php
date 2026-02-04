@extends('admin.layouts.app')

@section('title', 'Website Issues Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="ri-bug-line me-2"></i>Website Issues Dashboard
                    </h4>
                    <p class="text-muted mb-0">Unified view of all website issues, support tickets, queries, and system health</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.queries.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>New Query
                    </a>
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-success">
                        <i class="ri-customer-service-2-line me-1"></i>New Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Issues Alert -->
    @if($activeIssues > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="ri-alert-line me-2"></i>
        <strong>{{ $activeIssues }} Active Issue(s)</strong> require your attention.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Customer Queries -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Customer Queries</h6>
                            <h3 class="mb-1">{{ $stats['customer_queries']['total'] }}</h3>
                            <div class="d-flex gap-2 mt-3">
                                <span class="badge bg-label-info">{{ $stats['customer_queries']['new'] }} New</span>
                                @if($stats['customer_queries']['urgent'] > 0)
                                    <span class="badge bg-label-danger">{{ $stats['customer_queries']['urgent'] }} Urgent</span>
                                @endif
                            </div>
                        </div>
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="ri-question-answer-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.queries.index') }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                        <i class="ri-arrow-right-line me-1"></i>View All
                    </a>
                </div>
            </div>
        </div>

        <!-- Support Tickets -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Support Tickets</h6>
                            <h3 class="mb-1">{{ $stats['support_tickets']['total'] }}</h3>
                            <div class="d-flex gap-2 mt-3">
                                <span class="badge bg-label-warning">{{ $stats['support_tickets']['open'] }} Open</span>
                                @if($stats['support_tickets']['urgent'] > 0)
                                    <span class="badge bg-label-danger">{{ $stats['support_tickets']['urgent'] }} Urgent</span>
                                @endif
                            </div>
                        </div>
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="ri-customer-service-2-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-success w-100 mt-3">
                        <i class="ri-arrow-right-line me-1"></i>View All
                    </a>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 {{ $stats['system_health']['database'] === 'healthy' ? 'border-success' : 'border-danger' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">System Health</h6>
                            <h3 class="mb-1">
                                <span class="badge {{ $stats['system_health']['database'] === 'healthy' ? 'bg-label-success' : 'bg-label-danger' }}">
                                    {{ ucfirst($stats['system_health']['database']) }}
                                </span>
                            </h3>
                            <div class="mt-3">
                                <small class="text-muted d-block">PHP {{ $stats['system_health']['php_version'] }}</small>
                                <small class="text-muted d-block">Laravel {{ $stats['system_health']['laravel_version'] }}</small>
                                @if($stats['system_health']['log_warning'])
                                    <span class="badge bg-label-warning mt-2">Log: {{ $stats['system_health']['log_size_mb'] }}MB</span>
                                @endif
                            </div>
                        </div>
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial {{ $stats['system_health']['database'] === 'healthy' ? 'bg-label-success' : 'bg-label-danger' }} rounded">
                                <i class="ri-heart-pulse-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.settings.system-health') }}" class="btn btn-sm btn-outline-info w-100 mt-3">
                        <i class="ri-arrow-right-line me-1"></i>View Details
                    </a>
                </div>
            </div>
        </div>

        <!-- System Issues -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 {{ count($systemIssues) > 0 ? 'border-warning' : 'border-success' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">System Issues</h6>
                            <h3 class="mb-1">{{ count($systemIssues) }}</h3>
                            <div class="mt-3">
                                @if(count($systemIssues) === 0)
                                    <span class="badge bg-label-success">All Systems Operational</span>
                                @else
                                    @php
                                        $critical = collect($systemIssues)->where('type', 'critical')->count();
                                        $warnings = collect($systemIssues)->where('type', 'warning')->count();
                                    @endphp
                                    @if($critical > 0)
                                        <span class="badge bg-label-danger">{{ $critical }} Critical</span>
                                    @endif
                                    @if($warnings > 0)
                                        <span class="badge bg-label-warning">{{ $warnings }} Warnings</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial {{ count($systemIssues) > 0 ? 'bg-label-warning' : 'bg-label-success' }} rounded">
                                <i class="ri-alert-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.settings.system-logs') }}" class="btn btn-sm btn-outline-warning w-100 mt-3">
                        <i class="ri-arrow-right-line me-1"></i>View Logs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Issues -->
    <div class="row g-4">
        <!-- Recent Customer Queries -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-question-answer-line me-2"></i>Recent Customer Queries
                    </h5>
                    <a href="{{ route('admin.queries.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentQueries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentQueries as $query)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $query->name }}</strong>
                                                <br><small class="text-muted">{{ $query->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.queries.show', $query->id) }}">
                                                {{ Str::limit($query->subject ?? 'No Subject', 40) }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'new' => 'warning',
                                                    'read' => 'info',
                                                    'replied' => 'primary',
                                                    'resolved' => 'success'
                                                ];
                                                $color = $statusColors[$query->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-label-{{ $color }}">
                                                {{ ucfirst($query->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $query->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-inbox-line ri-48px text-muted"></i>
                            <p class="text-muted mt-2">No recent queries</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Support Tickets -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-customer-service-2-line me-2"></i>Recent Support Tickets
                    </h5>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-success">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentTickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ticket #</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTickets as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}">
                                                <strong>{{ $ticket->ticket_number }}</strong>
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'open' => 'warning',
                                                    'in_progress' => 'info',
                                                    'resolved' => 'success',
                                                    'closed' => 'secondary'
                                                ];
                                                $color = $statusColors[$ticket->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-label-{{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $priorityColors = [
                                                    'urgent' => 'danger',
                                                    'high' => 'warning',
                                                    'normal' => 'info',
                                                    'low' => 'secondary'
                                                ];
                                                $pColor = $priorityColors[$ticket->priority] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-label-{{ $pColor }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-inbox-line ri-48px text-muted"></i>
                            <p class="text-muted mt-2">No recent tickets</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Issues List -->
    @if(count($systemIssues) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-alert-line me-2"></i>System Issues Detected
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($systemIssues as $issue)
                        <div class="list-group-item">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <div class="avatar-initial {{ $issue['type'] === 'critical' ? 'bg-label-danger' : 'bg-label-warning' }} rounded">
                                        <i class="{{ $issue['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $issue['title'] }}
                                        <span class="badge {{ $issue['type'] === 'critical' ? 'bg-label-danger' : 'bg-label-warning' }} ms-2">
                                            {{ ucfirst($issue['type']) }}
                                        </span>
                                    </h6>
                                    <p class="mb-0 text-muted">{{ $issue['message'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-flashlight-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.queries.index') }}" class="btn btn-outline-primary w-100">
                                <i class="ri-question-answer-line me-2"></i>All Queries
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-success w-100">
                                <i class="ri-customer-service-2-line me-2"></i>All Tickets
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.settings.system-health') }}" class="btn btn-outline-info w-100">
                                <i class="ri-heart-pulse-line me-2"></i>System Health
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.settings.system-logs') }}" class="btn btn-outline-warning w-100">
                                <i class="ri-file-list-3-line me-2"></i>System Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

