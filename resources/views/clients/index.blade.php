@extends('layouts.app')

@section('title', 'Client Management')
@section('subtitle', 'Manage client records and information')

@section('actions')
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import me-2"></i>Import
    </button>
    <a href="{{ route('clients.export') }}" class="btn btn-success">
        <i class="fas fa-file-export me-2"></i>Export
    </a>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Client
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Clients</h3>
                    <div class="stat-value">{{ $clients->total() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-users"></i>
                        <span>Registered clients</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Active Allocations</h3>
                    <div class="stat-value">{{ $totalAllocations ?? 0 }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-handshake"></i>
                        <span>Current allocations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>New This Month</h3>
                    <div class="stat-value">{{ $newThisMonth ?? 0 }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-user-plus"></i>
                        <span>Recent registrations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Client Records</h5>
            <div class="header-actions">
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-1"></i>Import
                </button>
                <a href="{{ route('clients.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i>Export
                </a>
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Client
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="search" class="form-label">Search Clients</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Search by name, phone, email, or ID number...">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>

            @if($clients->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="clientsTable">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>ID Information</th>
                            <th>Allocations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-primary fs-6"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark">{{ $client->full_name }}</h6>
                                        <small class="text-muted">{{ $client->occupation ?? 'Not specified' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <div><i class="fas fa-phone text-muted me-2"></i>{{ $client->phone }}</div>
                                    @if($client->email)
                                    <div class="mt-1"><i class="fas fa-envelope text-muted me-2"></i>{{ $client->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-capitalize">
                                        {{ str_replace('_', ' ', $client->id_type) }}
                                    </span>
                                    <div class="mt-1 small text-muted">{{ $client->id_number }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-handshake me-1"></i>
                                    {{ $client->allocations_count }} allocation(s)
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this client?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} entries
                </div>
                {{ $clients->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No clients found</h5>
                <p class="text-muted">Get started by adding your first client or importing data.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-2"></i>Import Data
                    </button>
                    <a href="{{ route('clients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Client
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-import me-2"></i>Import Client Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Select CSV/Excel File</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                <div class="form-text">
                                    Supported formats: CSV, Excel (.xlsx, .xls)
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6 class="alert-heading mb-2">
                                    <i class="fas fa-info-circle me-2"></i>Import Instructions
                                </h6>
                                <ul class="mb-0 small">
                                    <li>File should include columns: Full Name, Phone, Email, ID Type, ID Number, etc.</li>
                                    <li>First row should contain column headers</li>
                                    <li>Ensure data follows the correct format</li>
                                    <li>Maximum file size: 10MB</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="template-section">
                                <h6 class="mb-3">Download Sample Template</h6>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-download-template" onclick="downloadSampleTemplate()">
                                        <i class="fas fa-download me-2"></i>Download CSV Template
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-download-template" onclick="downloadSampleTemplate('excel')">
                                        <i class="fas fa-download me-2"></i>Download Excel Template
                                    </button>
                                </div>
                                
                                <div class="mt-3">
                                    <h6 class="small">Template Structure:</h6>
                                    <div class="template-preview bg-light p-2 small border rounded">
                                        <code>
full_name,phone,email,id_type,id_number,occupation,address,date_of_birth,gender,is_active<br>
John Doe,+233201234567,john.doe@email.com,ghana_card,GHA-123456789,Engineer,123 Main St Accra,1985-05-15,male,true<br>
Jane Smith,+233241234568,jane.smith@email.com,passport,PS12345678,Doctor,456 Oak Ave Kumasi,1990-08-20,female,true
                                        </code>
                                    </div>
                                    <p class="small text-muted mt-2">Use the downloaded template and fill with your data.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--primary);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .stat-info h3 {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card:nth-child(1) .stat-icon {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: var(--gray-600);
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: var(--danger);
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 6px;
    }
    
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .header-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .template-section {
        border-left: 3px solid var(--info);
        padding-left: 1rem;
    }
    
    .btn-download-template {
        padding: 0.75rem 1rem;
        text-align: left;
    }
    
    .template-preview {
        max-height: 120px;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
    }
    
    .template-preview code {
        background: transparent;
        color: var(--dark);
        white-space: pre-wrap;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#clientsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No clients found"
            },
            columnDefs: [
                { orderable: false, targets: [4] } // Disable sorting for actions column
            ]
        });

        // Show file name when selected
        $('#file').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $(this).next('.form-text').html(`Selected file: <strong>${fileName}</strong>`);
            }
        });
    });

    function downloadSampleTemplate(format = 'csv') {
        // Create sample data for clients
        const headers = ['full_name', 'phone', 'email', 'id_type', 'id_number', 'occupation', 'address', 'date_of_birth', 'gender', 'is_active'];
        const sampleData = [
            ['John Doe', '+233201234567', 'john.doe@email.com', 'ghana_card', 'GHA-123456789', 'Engineer', '123 Main St Accra', '1985-05-15', 'male', 'true'],
            ['Jane Smith', '+233241234568', 'jane.smith@email.com', 'passport', 'PS12345678', 'Doctor', '456 Oak Ave Kumasi', '1990-08-20', 'female', 'true'],
            ['Kwame Asante', '+233271234569', 'kwame.asante@email.com', 'voters_id', 'VOT1234567', 'Teacher', '789 Pine Rd Takoradi', '1988-12-10', 'male', 'true']
        ];

        if (format === 'csv') {
            downloadCSV(headers, sampleData, 'client-import-template.csv');
        } else {
            downloadExcel(headers, sampleData, 'client-import-template.xlsx');
        }
    }

    function downloadCSV(headers, data, filename) {
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add headers
        csvContent += headers.join(',') + '\r\n';
        
        // Add data rows
        data.forEach(row => {
            csvContent += row.join(',') + '\r\n';
        });
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        
        // Trigger download
        link.click();
        document.body.removeChild(link);
    }

    function downloadExcel(headers, data, filename) {
        // For Excel, we'll create a simple CSV but with .xlsx extension
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add headers
        csvContent += headers.join(',') + '\r\n';
        
        // Add data rows
        data.forEach(row => {
            csvContent += row.join(',') + '\r\n';
        });
        
        // Create download link with .xlsx extension
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        
        // Trigger download
        link.click();
        document.body.removeChild(link);
        
        // Show info message for Excel
        setTimeout(() => {
            alert('Note: For full Excel support, please use the CSV template or install proper Excel export functionality.');
        }, 1000);
    }
</script>
@endpush