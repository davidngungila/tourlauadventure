@extends('admin.layouts.app')

@section('title', 'Role Profile')
@section('description', 'Your role profile and permissions')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Role Profile</h4>
                        <p class="mb-0 text-body-secondary">Your current role and permissions overview</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics Cards -->
<div class="row g-6 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ri ri-user-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ auth()->user()->name }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Your Name</h6>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ri ri-shield-user-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ $userRole ?? 'No Role' }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Your Role</h6>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ri ri-key-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ $permissionsCount ?? 0 }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Total Permissions</h6>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-border-shadow-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ri ri-mail-line icon-24px"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ auth()->user()->email }}</h4>
                </div>
                <h6 class="mb-0 fw-normal">Email Address</h6>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    <!-- Role Information -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Role Information</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Role Name:</th>
                            <td><strong>{{ $userRole ?? 'No Role Assigned' }}</strong></td>
                        </tr>
                        <tr>
                            <th>User Name:</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Account Created:</th>
                            <td>{{ auth()->user()->created_at->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ auth()->user()->updated_at->format('F j, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Permissions -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Your Permissions</h5>
                    <span class="card-subtitle">Permissions assigned to your role</span>
                </div>
            </div>
            <div class="card-body">
                @if(isset($permissions) && $permissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                <th class="text-end">Module</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-info">{{ $permission->module ?? 'General' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">No permissions assigned</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection






