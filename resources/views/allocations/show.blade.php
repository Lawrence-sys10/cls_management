@extends('layouts.app')

@section('title', 'Allocation Details: ' . $allocation->land->plot_number)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Allocation Details</h1>
            <p class="text-muted">Plot: {{ $allocation->land->plot_number }} • Client: {{ $allocation->client->full_name }}</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('allocations.allocation-letter', $allocation) }}" class="btn btn-info">
                    <i class="fas fa-file-pdf"></i> Allocation Letter
                </a>
                <a href="{{ route('allocations.certificate', $allocation) }}" class="btn btn-success">
                    <i class="fas fa-certificate"></i> Certificate
                </a>
                <a href="{{ route('allocations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Allocation Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Allocation Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Allocation ID:</th>
                            <td><strong>#{{ $allocation->id }}</strong></td>
                        </tr>
                        <tr>
                            <th>Allocation Date:</th>
                            <td>{{ $allocation->allocation_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Approval Status:</th>
                            <td>
                                <span class="badge bg-{{ $allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($allocation->approval_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                <span class="badge bg-{{ $allocation->payment_status == 'paid' ? 'success' : ($allocation->payment_status == 'partial' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($allocation->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @if($allocation->payment_amount)
                        <tr>
                            <th>Payment Amount:</th>
                            <td>GHS {{ number_format($allocation->payment_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($allocation->payment_date)
                        <tr>
                            <th>Payment Date:</th>
                            <td>{{ $allocation->payment_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        @if($allocation->chief_approval_date)
                        <tr>
                            <th>Chief Approval:</th>
                            <td>{{ $allocation->chief_approval_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        @if($allocation->registrar_approval_date)
                        <tr>
                            <th>Registrar Approval:</th>
                            <td>{{ $allocation->registrar_approval_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Finalized:</th>
                            <td>
                                @if($allocation->is_finalized)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-warning">No</span>
                                @endif
                            </td>
                        </tr>
                        @if($allocation->purpose)
                        <tr>
                            <th>Purpose:</th>
                            <td>{{ $allocation->purpose }}</td>
                        </tr>
                        @endif
                        @if($allocation->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $allocation->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Approval Actions -->
            @if(!$allocation->is_finalized)
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Approval Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($allocation->approval_status != 'approved')
                        <form action="{{ route('allocations.approve', $allocation) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this allocation?')">
                                <i class="fas fa-check"></i> Approve Allocation
                            </button>
                        </form>
                        @endif

                        @if($allocation->approval_status != 'rejected')
                        <form action="{{ route('allocations.reject', $allocation) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this allocation?')">
                                <i class="fas fa-times"></i> Reject Allocation
                            </button>
                        </form>
                        @endif

                        @if($allocation->approval_status != 'pending')
                        <form action="{{ route('allocations.pending', $allocation) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Mark this allocation as pending?')">
                                <i class="fas fa-clock"></i> Mark as Pending
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Related Information -->
        <div class="col-md-6">
            <!-- Land Information -->
            <div class="card">
                <div class="card-header">
                    <h5>Land Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Plot Number:</th>
                            <td>
                                <a href="{{ route('lands.show', $allocation->land) }}">
                                    {{ $allocation->land->plot_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td>{{ $allocation->land->location }}</td>
                        </tr>
                        <tr>
                            <th>Area:</th>
                            <td>{{ number_format($allocation->land->area_acres, 2) }} acres</td>
                        </tr>
                        <tr>
                            <th>Chief:</th>
                            <td>{{ $allocation->land->chief->name }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $allocation->land->ownership_status == 'available' ? 'success' : ($allocation->land->ownership_status == 'allocated' ? 'primary' : 'warning') }}">
                                    {{ ucfirst($allocation->land->ownership_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Land Use:</th>
                            <td>{{ ucfirst($allocation->land->land_use) }}</td>
                        </tr>
                        @if($allocation->land->price)
                        <tr>
                            <th>Price:</th>
                            <td>GHS {{ number_format($allocation->land->price, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Client Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Client Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Name:</th>
                            <td>
                                <a href="{{ route('clients.show', $allocation->client) }}">
                                    {{ $allocation->client->full_name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $allocation->client->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $allocation->client->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>ID Type:</th>
                            <td>{{ ucfirst($allocation->client->id_type) }}</td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td>{{ $allocation->client->id_number }}</td>
                        </tr>
                        <tr>
                            <th>Occupation:</th>
                            <td>{{ $allocation->client->occupation ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Processing Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Processing Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Processed By:</th>
                            <td>{{ $allocation->processor->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Chief:</th>
                            <td>{{ $allocation->chief->name }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $allocation->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $allocation->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Documents Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Documents ({{ $allocation->documents->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($allocation->documents->count() > 0)
                        <div class="list-group">
                            @foreach($allocation->documents->take(3) as $document)
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
                        @if($allocation->documents->count() > 3)
                            <div class="text-center mt-2">
                                <a href="{{ route('documents.index') }}?allocation_id={{ $allocation->id }}" class="btn btn-sm btn-outline-primary">
                                    View All Documents
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No documents uploaded for this allocation.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-borderless td, .table-borderless th {
        border: none;
        padding: 0.5rem 0.25rem;
    }
    .table-sm td, .table-sm th {
        padding: 0.25rem;
    }
</style>
@endpush