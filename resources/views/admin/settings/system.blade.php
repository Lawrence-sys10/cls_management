@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">System Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update.system') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Maintenance Mode</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                        <label class="form-check-label" for="maintenance_mode">Enable Maintenance Mode</label>
                                    </div>
                                    <small class="form-text text-muted">When enabled, only administrators can access the system.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">User Registration</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user_registration" name="user_registration" checked>
                                        <label class="form-check-label" for="user_registration">Allow New User Registration</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" class="form-control" id="session_timeout" name="session_timeout" value="120" min="5">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="records_per_page" class="form-label">Records Per Page</label>
                                    <input type="number" class="form-control" id="records_per_page" name="records_per_page" value="15" min="5" max="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Notifications</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_allocations" name="email_allocations" checked>
                                <label class="form-check-label" for="email_allocations">Allocation Approvals</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_payments" name="email_payments" checked>
                                <label class="form-check-label" for="email_payments">Payment Notifications</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save System Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection