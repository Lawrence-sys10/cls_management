@extends('layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">General Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update.general') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', 'Land Allocation System') }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_email" class="form-label">Site Email</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email" value="{{ old('site_email', 'admin@landallocation.com') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="UTC">UTC</option>
                                        <option value="Africa/Accra">Africa/Accra</option>
                                        <option value="America/New_York">America/New_York</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Date Format</label>
                                    <select class="form-control" id="date_format" name="date_format">
                                        <option value="Y-m-d">YYYY-MM-DD</option>
                                        <option value="d/m/Y">DD/MM/YYYY</option>
                                        <option value="m/d/Y">MM/DD/YYYY</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ old('site_description', 'Land Allocation Management System') }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save General Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection