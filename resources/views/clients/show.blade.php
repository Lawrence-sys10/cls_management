@extends('layouts.app')

@section('title', 'Client: ' . $client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Client: {{ $client->full_name }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('clients.allocations', $client) }}" class="btn btn-info">
                    <i class="fas fa-landmark"></i> Allocations
                </a>
                <a href="{{ route('clients.documents', $client) }}" class="btn btn-secondary">
                    <i class="fas fa-file"></i> Documents
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Client Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Full Name:</th>
                            <td><strong>{{ $client->full_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $client->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $client->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>ID Type:</th>
                            <td>{{ ucfirst($client->id_type) }}</td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td>{{ $client->id_number }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ ucfirst($client->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>{{ $client->date_of_birth ? $client->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Occupation:</th>
                            <td>{{ $client->occupation ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $client->address ?? 'N/A' }}</td>
                        </tr>
                        @if($client->emergency_contact)
                        <tr>
                            <th>Emergency Contact:</th>
                            <td>{{ $client->emergency_contact }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Registered:</th>
                            <td>{{ $client->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Allocations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Land Allocations ({{ $client->allocations->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($client->allocations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Plot Number</th>
                                        <th>Location</th>
                                        <th>Allocation Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->allocations as $allocation)
                                        <tr>
                                            <td>
                                                <a href="{{ route('lands.show', $allocation->land_id) }}">
                                                    {{ $allocation->land->plot_number ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>{{ $allocation->land->location ?? 'N/A' }}</td>
                                            <td>{{ $allocation->allocation_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($allocation->approval_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No land allocations for this client.</p>
                        <a href="{{ route('allocations.create') }}" class="btn btn-sm btn-primary">
                            Create Allocation
                        </a>
                    @endif
                </div>
            </div>

            <!-- Documents Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Documents ({{ $client->documents->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($client->documents->count() > 0)
                        <div class="list-group">
                            @foreach($client->documents->take(3) as $document)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $document->document_type }}</h6>
                                            <small class="text-muted">{{ $document->file_name }}</small>
                                        </div>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($client->documents->count() > 3)
                            <div class="text-center mt-2">
                                <a href="{{ route('clients.documents', $client) }}" class="btn btn-sm btn-outline-primary">
                                    View All Documents
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No documents uploaded.</p>
                        <a href="{{ route('clients.documents', $client) }}" class="btn btn-sm btn-primary">
                            Upload Documents
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Client Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-0">{{ $client->allocations->count() }}</h4>
                                <small class="text-muted">Total Allocations</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-0">
                                    {{ $client->allocations->where('approval_status', 'approved')->count() }}
                                </h4>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection