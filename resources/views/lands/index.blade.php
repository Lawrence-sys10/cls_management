@extends('layouts.app')

@section('title', 'Land Management')
@section('subtitle', 'Manage land records and allocations')

@section('actions')
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import me-2"></i>Import
    </button>
    <a href="{{ route('lands.export') }}" class="btn btn-success">
        <i class="fas fa-file-export me-2"></i>Export
    </a>
    <a href="{{ route('lands.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Land
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Lands</h3>
                    <div class="stat-value">{{ $lands->total() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Registered lands</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Vacant Lands</h3>
                    <div class="stat-value">{{ $lands->where('ownership_status', 'vacant')->count() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-landmark"></i>
                        <span>Available for allocation</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-landmark"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Allocated</h3>
                    <div class="stat-value">{{ $lands->where('ownership_status', 'allocated')->count() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-handshake"></i>
                        <span>Currently allocated</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lands Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Land Records</h5>
            <div class="header-actions">
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-1"></i>Import
                </button>
                <a href="{{ route('lands.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i>Export
                </a>
                <a href="{{ route('lands.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Land
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Plot number or location...">
                    </div>
                    <div class="col-md-3">
                        <label for="chief_id" class="form-label">Chief</label>
                        <select name="chief_id" id="chief_id" class="form-control">
                            <option value="">All Chiefs</option>
                            @foreach($chiefs as $chief)
                            <option value="{{ $chief->id }}" {{ request('chief_id') == $chief->id ? 'selected' : '' }}>
                                {{ $chief->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="vacant" {{ request('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                            <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                            <option value="under_dispute" {{ request('status') == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($lands->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="landsTable">
                    <thead>
                        <tr>
                            <th>Plot Number</th>
                            <th>Location</th>
                            <th>Area (Acres)</th>
                            <th>Chief</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lands as $land)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $land->plot_number }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $land->location }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ number_format($land->area_acres, 2) }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $land->chief->name }}</div>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($land->ownership_status) {
                                        'vacant' => 'badge-success',
                                        'allocated' => 'badge-primary',
                                        'under_dispute' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $land->ownership_status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('lands.show', $land) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lands.edit', $land) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('lands.destroy', $land) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this land?')">
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
                    Showing {{ $lands->firstItem() }} to {{ $lands->lastItem() }} of {{ $lands->total() }} entries
                </div>
                {{ $lands->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No land records found</h5>
                <p class="text-muted">Get started by adding your first land record or importing data.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-2"></i>Import Data
                    </button>
                    <a href="{{ route('lands.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Land
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
                    <i class="fas fa-file-import me-2"></i>Import Land Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lands.import') }}" method="POST" enctype="multipart/form-data">
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
                                    <li>File should include columns: Plot Number, Location, Area (Acres), Chief ID, etc.</li>
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
Plot Number,Location,Area (Acres),Area (Hectares),Chief ID,Ownership Status,Land Use,Price,Registration Date<br>
PLOT-001,East Legon,2.5,1.01,1,vacant,residential,50000,2024-01-15<br>
PLOT-002,Airport Residential,3.0,1.21,2,allocated,commercial,75000,2024-01-20
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
    
    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .badge-primary {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
        border: 1px solid rgba(67, 97, 238, 0.2);
    }
    
    .badge-warning {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
        border: 1px solid rgba(247, 37, 133, 0.2);
    }
    
    .badge-secondary {
        background: rgba(108, 117, 125, 0.1);
        color: var(--gray-600);
        border: 1px solid rgba(108, 117, 125, 0.2);
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
        $('#landsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No land records found"
            },
            columnDefs: [
                { orderable: false, targets: [5] } // Disable sorting for actions column
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
        // Create sample data
        const headers = ['Plot Number', 'Location', 'Area (Acres)', 'Area (Hectares)', 'Chief ID', 'Ownership Status', 'Land Use', 'Price', 'Registration Date'];
        const sampleData = [
            ['PLOT-001', 'East Legon', '2.5', '1.01', '1', 'vacant', 'residential', '50000', '2024-01-15'],
            ['PLOT-002', 'Airport Residential', '3.0', '1.21', '2', 'allocated', 'commercial', '75000', '2024-01-20'],
            ['PLOT-003', 'Cantonments', '1.5', '0.61', '3', 'vacant', 'residential', '35000', '2024-01-25']
        ];

        if (format === 'csv') {
            downloadCSV(headers, sampleData);
        } else {
            downloadExcel(headers, sampleData);
        }
    }

    function downloadCSV(headers, data) {
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
        link.setAttribute("download", "land-import-template.csv");
        document.body.appendChild(link);
        
        // Trigger download
        link.click();
        document.body.removeChild(link);
    }

    function downloadExcel(headers, data) {
        // For Excel, we'll create a simple CSV but with .xlsx extension
        // In a real application, you might want to use a library like SheetJS
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
        link.setAttribute("download", "land-import-template.xlsx");
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