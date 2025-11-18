@extends('layouts.app')

@section('title', 'Reports')
@section('subtitle', 'Generate and view system reports')

@section('actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
        <i class="fas fa-plus me-2"></i>Generate Report
    </button>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Reports</h3>
                    <div class="stat-value">{{ $reports->total() ?? 0 }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-file-alt me-1"></i>
                        <span>Generated reports</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>This Month</h3>
                    <div class="stat-value">{{ $reportsThisMonth ?? 0 }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-chart-line me-1"></i>
                        <span>Monthly reports</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Ready to Export</h3>
                    <div class="stat-value">{{ $readyForExport ?? 0 }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-download me-1"></i>
                        <span>Available for download</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Report Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Reports</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2 col-sm-4">
                            <a href="{{ route('reports.lands') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-landmark me-2"></i>Lands
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="{{ route('reports.allocations') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-handshake me-2"></i>Allocations
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="{{ route('reports.clients') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>Clients
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="{{ route('reports.chiefs') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-crown me-2"></i>Chiefs
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="{{ route('reports.system') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-cog me-2"></i>System
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                                <i class="fas fa-plus me-2"></i>Custom
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Generated Reports</h5>
            <div class="header-actions">
                <!-- Simplified Export Button - Single CSV Option -->
                <form action="{{ route('reports.export') }}" method="POST" class="d-inline me-2">
                    @csrf
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="format" value="csv">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i>Export CSV
                    </button>
                </form>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                    <i class="fas fa-plus me-1"></i>Generate Report
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Reports</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Search by report name or type...">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Report Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="lands" {{ request('type') == 'lands' ? 'selected' : '' }}>Lands Report</option>
                            <option value="allocations" {{ request('type') == 'allocations' ? 'selected' : '' }}>Allocations Report</option>
                            <option value="clients" {{ request('type') == 'clients' ? 'selected' : '' }}>Clients Report</option>
                            <option value="chiefs" {{ request('type') == 'chiefs' ? 'selected' : '' }}>Chiefs Report</option>
                            <option value="financial" {{ request('type') == 'financial' ? 'selected' : '' }}>Financial Report</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($reports->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="reportsTable">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Generated By</th>
                            <th>Date Generated</th>
                            <th>Status</th>
                            <th>File Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-file-alt text-primary fs-6"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark">{{ $report->name ?? 'Unnamed Report' }}</h6>
                                        <small class="text-muted">ID: {{ $report->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($report->type == 'lands') bg-success
                                    @elseif($report->type == 'allocations') bg-primary
                                    @elseif($report->type == 'clients') bg-info
                                    @elseif($report->type == 'chiefs') bg-warning
                                    @else bg-secondary
                                    @endif bg-opacity-10 text-capitalize">
                                    {{ $report->type ?? 'general' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark">{{ $report->generated_by ?? 'System' }}</div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <div>{{ $report->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($report->status == 'completed') bg-success
                                    @elseif($report->status == 'processing') bg-warning
                                    @else bg-danger
                                    @endif">
                                    <i class="fas 
                                        @if($report->status == 'completed') fa-check-circle
                                        @elseif($report->status == 'processing') fa-spinner fa-spin
                                        @else fa-times-circle
                                        @endif me-1"></i>
                                    {{ ucfirst($report->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark">{{ $report->file_size ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if(($report->status == 'completed') && ($report->file_path ?? false))
                                    <a href="{{ route('reports.download', $report) }}" class="btn btn-sm btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @endif
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="View Details" onclick="showReportDetails({{ $report->id }})">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(($report->status != 'processing') && ($report->deleted_at === null))
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this report?')">
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
                    Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} entries
                </div>
                {{ $reports->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No reports generated yet</h5>
                <p class="text-muted">Get started by generating your first report using the quick links above.</p>
                <div class="mt-3">
                    <a href="{{ route('reports.lands') }}" class="btn btn-primary me-2">
                        <i class="fas fa-landmark me-1"></i>Lands Report
                    </a>
                    <a href="{{ route('reports.allocations') }}" class="btn btn-success">
                        <i class="fas fa-handshake me-1"></i>Allocations Report
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateReportModalLabel">Generate Custom Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="customReportForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="report_name" class="form-label">Report Name</label>
                            <input type="text" class="form-control" id="report_name" name="report_name" required 
                                   placeholder="Enter report name">
                        </div>
                        <div class="col-md-6">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-control" id="report_type" name="report_type" required>
                                <option value="">Select Report Type</option>
                                <option value="lands">Lands Report</option>
                                <option value="allocations">Allocations Report</option>
                                <option value="clients">Clients Report</option>
                                <option value="chiefs">Chiefs Report</option>
                                <option value="comprehensive">Comprehensive Report</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="col-12">
                            <label for="format" class="form-label">Export Format</label>
                            <select class="form-control" id="format" name="format" required>
                                <option value="pdf">PDF Document</option>
                                <option value="csv">CSV File</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_charts" name="include_charts">
                                <label class="form-check-label" for="include_charts">
                                    Include charts and graphs
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cog me-2"></i>Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Report Details Modal -->
<div class="modal fade" id="reportDetailsModal" tabindex="-1" aria-labelledby="reportDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportDetailsModalLabel">Report Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reportDetailsContent">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
    
    .header-actions .btn-group .btn {
        padding: 0.375rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#reportsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No reports found"
            },
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting for actions column
            ]
        });

        // Set default dates in modal
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
        document.getElementById('end_date').value = today.toISOString().split('T')[0];

        // Handle custom report form submission
        $('#customReportForm').on('submit', function(e) {
            e.preventDefault();
            
            const reportType = $('#report_type').val();
            const reportName = $('#report_name').val();
            
            if (!reportType || !reportName) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Redirect to the appropriate report generation page
            switch(reportType) {
                case 'lands':
                    window.location.href = "{{ route('reports.lands') }}";
                    break;
                case 'allocations':
                    window.location.href = "{{ route('reports.allocations') }}";
                    break;
                case 'clients':
                    window.location.href = "{{ route('reports.clients') }}";
                    break;
                case 'chiefs':
                    window.location.href = "{{ route('reports.chiefs') }}";
                    break;
                case 'comprehensive':
                    window.location.href = "{{ route('reports.system') }}";
                    break;
                default:
                    alert('Please select a valid report type.');
            }
            
            $('#generateReportModal').modal('hide');
        });
    });

    // Show report details (demo function)
    function showReportDetails(reportId) {
        const detailsContent = `
            <div class="report-details">
                <h6>Report Information</h6>
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Report ID:</strong> ${reportId}
                    </div>
                    <div class="col-6">
                        <strong>Status:</strong> <span class="badge bg-success">Completed</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Type:</strong> Lands Report
                    </div>
                    <div class="col-6">
                        <strong>Format:</strong> PDF
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Description:</strong> Comprehensive land allocation report
                    </div>
                </div>
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-2"></i>
                        Report details functionality would be implemented here to show specific report information.
                    </small>
                </div>
            </div>
        `;
        
        $('#reportDetailsContent').html(detailsContent);
        $('#reportDetailsModal').modal('show');
    }
</script>
@endpush