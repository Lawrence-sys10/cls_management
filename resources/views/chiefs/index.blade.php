@extends('layouts.app')

@section('title', 'Chief Management')
@section('subtitle', 'Manage traditional chiefs and authorities')

@section('actions')
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import me-2"></i>Import
    </button>
    <a href="{{ route('chiefs.export') }}" class="btn btn-success">
        <i class="fas fa-file-export me-2"></i>Export
    </a>
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
    <a href="{{ route('chiefs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Chief
    </a>
    @endif
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Chiefs</h3>
                    <div class="stat-value">{{ $chiefs->total() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-crown"></i>
                        <span>Registered chiefs</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Active Chiefs</h3>
                    <div class="stat-value">{{ $chiefs->where('is_active', true)->count() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-check-circle"></i>
                        <span>Currently active</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Allocations</h3>
                    <div class="stat-value">{{ \App\Models\Allocation::whereHas('chief')->count() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Under management</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chiefs Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chief Records</h5>
            <div class="header-actions">
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-1"></i>Import
                </button>
                <a href="{{ route('chiefs.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i>Export
                </a>
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                <a href="{{ route('chiefs.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Chief
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search Chiefs</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Search by name, jurisdiction, phone, or email...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($chiefs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="chiefsTable">
                    <thead>
                        <tr>
                            <th>Chief</th>
                            <th>Jurisdiction</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Allocations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chiefs as $chief)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-crown text-warning fs-6"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark">{{ $chief->name }}</h6>
                                        <small class="text-muted">Since {{ $chief->created_at->format('M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <div class="fw-semibold">{{ $chief->jurisdiction }}</div>
                                    @if($chief->area_boundaries)
                                    <small class="text-muted">{{ Str::limit($chief->area_boundaries, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    @if($chief->phone)
                                    <div><i class="fas fa-phone text-muted me-2"></i>{{ $chief->phone }}</div>
                                    @endif
                                    @if($chief->email)
                                    <div class="mt-1"><i class="fas fa-envelope text-muted me-2"></i>{{ $chief->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($chief->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                                @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-map-marked-alt me-1"></i>
                                    {{ $chief->allocations_count ?? $chief->allocations()->count() }} allocation(s)
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('chiefs.show', $chief) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                                    <a href="{{ route('chiefs.edit', $chief) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('chiefs.destroy', $chief) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this chief?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
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
                    Showing {{ $chiefs->firstItem() }} to {{ $chiefs->lastItem() }} of {{ $chiefs->total() }} entries
                </div>
                {{ $chiefs->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No chiefs found</h5>
                <p class="text-muted">Get started by adding your first chief or importing data.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-2"></i>Import Data
                    </button>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                    <a href="{{ route('chiefs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Chief
                    </a>
                    @endif
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
                    <i class="fas fa-file-import me-2"></i>Import Chief Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('chiefs.import') }}" method="POST" enctype="multipart/form-data">
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
                                    <li>File should include columns: Name, Jurisdiction, Phone, Email, etc.</li>
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
name,jurisdiction,phone,email,region,area_boundaries,is_active<br>
Nana Kwame Asante,East Legon Traditional Area,+233201234567,nana.kwame@example.com,Greater Accra,"East Legon, Adjiringanor, Ogbojo",true<br>
Nana Adwoa Mensah,Airport Residential Area,+233241234568,nana.adwoa@example.com,Greater Accra,"Airport Residential, Cantonments",true<br>
Nana Yaw Boateng,Abuja Traditional Council,+233271234569,nana.yaw@example.com,Eastern,"Abuja, Nsawam Road",true
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
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid #4361ee;
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
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #212529;
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
        color: #4361ee;
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: #6c757d;
    }
    
    .trend-up {
        color: #28a745;
    }
    
    .trend-down {
        color: #dc3545;
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
        border-left: 3px solid #17a2b8;
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
        color: #212529;
        white-space: pre-wrap;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable if the table exists
        if ($.fn.DataTable.isDataTable('#chiefsTable')) {
            $('#chiefsTable').DataTable().destroy();
        }
        
        $('#chiefsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No chiefs found"
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

        // Auto-submit form when status changes
        $('#status').change(function() {
            $(this).closest('form').submit();
        });

        // Clear search when clear button is clicked
        $('input[name="search"]').on('input', function() {
            if (!this.value) {
                $(this).closest('form').submit();
            }
        });
    });

    function downloadSampleTemplate(format = 'csv') {
        // Create sample data for chiefs
        const headers = ['name', 'jurisdiction', 'phone', 'email', 'region', 'area_boundaries', 'is_active'];
        const sampleData = [
            ['Nana Kwame Asante', 'East Legon Traditional Area', '+233201234567', 'nana.kwame@example.com', 'Greater Accra', 'East Legon, Adjiringanor, Ogbojo', 'true'],
            ['Nana Adwoa Mensah', 'Airport Residential Area', '+233241234568', 'nana.adwoa@example.com', 'Greater Accra', 'Airport Residential, Cantonments', 'true'],
            ['Nana Yaw Boateng', 'Abuja Traditional Council', '+233271234569', 'nana.yaw@example.com', 'Eastern', 'Abuja, Nsawam Road', 'true']
        ];

        if (format === 'csv') {
            downloadCSV(headers, sampleData, 'chief-import-template.csv');
        } else {
            downloadExcel(headers, sampleData, 'chief-import-template.xlsx');
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