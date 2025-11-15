@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profile Settings</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Account Settings</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Manage your account settings and preferences.</p>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <a href="{{ route('profile.password.update') }}" class="text-decoration-none">
                                                <i class="fas fa-lock me-2"></i>Change Password
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <a href="#" class="text-decoration-none text-muted">
                                                <i class="fas fa-bell me-2"></i>Notification Settings
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Security</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Manage your security preferences and account access.</p>
                                    <div class="alert alert-warning">
                                        <small>
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            For security reasons, please keep your password confidential and enable two-factor authentication if available.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection