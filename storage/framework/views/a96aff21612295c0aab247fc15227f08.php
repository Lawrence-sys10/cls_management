<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('subtitle', 'Generate and view system reports'); ?>

<?php $__env->startSection('actions'); ?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
        <i class="fas fa-plus me-2"></i>Generate Report
    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Reports</h3>
                    <div class="stat-value"><?php echo e($reports->total() ?? 0); ?></div>
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
                    <div class="stat-value"><?php echo e($reportsThisMonth ?? 0); ?></div>
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
                    <div class="stat-value"><?php echo e($readyForExport ?? 0); ?></div>
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
                            <a href="<?php echo e(route('reports.lands')); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-landmark me-2"></i>Lands
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="<?php echo e(route('reports.allocations')); ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-handshake me-2"></i>Allocations
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="<?php echo e(route('reports.clients')); ?>" class="btn btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>Clients
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="<?php echo e(route('reports.chiefs')); ?>" class="btn btn-outline-warning w-100">
                                <i class="fas fa-crown me-2"></i>Chiefs
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <a href="<?php echo e(route('reports.system')); ?>" class="btn btn-outline-secondary w-100">
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
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                               class="form-control"
                               placeholder="Search by report name or type...">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Report Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="lands" <?php echo e(request('type') == 'lands' ? 'selected' : ''); ?>>Lands Report</option>
                            <option value="allocations" <?php echo e(request('type') == 'allocations' ? 'selected' : ''); ?>>Allocations Report</option>
                            <option value="clients" <?php echo e(request('type') == 'clients' ? 'selected' : ''); ?>>Clients Report</option>
                            <option value="chiefs" <?php echo e(request('type') == 'chiefs' ? 'selected' : ''); ?>>Chiefs Report</option>
                            <option value="financial" <?php echo e(request('type') == 'financial' ? 'selected' : ''); ?>>Financial Report</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                            <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
                            <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <?php if($reports->count() > 0): ?>
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
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-file-alt text-primary fs-6"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark"><?php echo e($report->name ?? 'Unnamed Report'); ?></h6>
                                        <small class="text-muted">ID: <?php echo e($report->id); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    <?php if($report->type == 'lands'): ?> bg-success
                                    <?php elseif($report->type == 'allocations'): ?> bg-primary
                                    <?php elseif($report->type == 'clients'): ?> bg-info
                                    <?php elseif($report->type == 'chiefs'): ?> bg-warning
                                    <?php else: ?> bg-secondary
                                    <?php endif; ?> bg-opacity-10 text-capitalize">
                                    <?php echo e($report->type ?? 'general'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="text-dark"><?php echo e($report->generated_by ?? 'System'); ?></div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <div><?php echo e($report->created_at->format('M d, Y')); ?></div>
                                    <small class="text-muted"><?php echo e($report->created_at->format('h:i A')); ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    <?php if($report->status == 'completed'): ?> bg-success
                                    <?php elseif($report->status == 'processing'): ?> bg-warning
                                    <?php else: ?> bg-danger
                                    <?php endif; ?>">
                                    <i class="fas 
                                        <?php if($report->status == 'completed'): ?> fa-check-circle
                                        <?php elseif($report->status == 'processing'): ?> fa-spinner fa-spin
                                        <?php else: ?> fa-times-circle
                                        <?php endif; ?> me-1"></i>
                                    <?php echo e(ucfirst($report->status ?? 'unknown')); ?>

                                </span>
                            </td>
                            <td>
                                <div class="text-dark"><?php echo e($report->file_size ?? 'N/A'); ?></div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <?php if(($report->status == 'completed') && ($report->file_path ?? false)): ?>
                                    <a href="<?php echo e(route('reports.download', $report)); ?>" class="btn btn-sm btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="View Details" onclick="showReportDetails(<?php echo e($report->id); ?>)">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if(($report->status != 'processing') && ($report->deleted_at === null)): ?>
                                    <form action="<?php echo e(route('reports.destroy', $report)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this report?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing <?php echo e($reports->firstItem()); ?> to <?php echo e($reports->lastItem()); ?> of <?php echo e($reports->total()); ?> entries
                </div>
                <?php echo e($reports->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No reports generated yet</h5>
                <p class="text-muted">Get started by generating your first report using the quick links above.</p>
                <div class="mt-3">
                    <a href="<?php echo e(route('reports.lands')); ?>" class="btn btn-primary me-2">
                        <i class="fas fa-landmark me-1"></i>Lands Report
                    </a>
                    <a href="<?php echo e(route('reports.allocations')); ?>" class="btn btn-success">
                        <i class="fas fa-handshake me-1"></i>Allocations Report
                    </a>
                </div>
            </div>
            <?php endif; ?>
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
                <?php echo csrf_field(); ?>
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
                                <option value="excel">Excel Spreadsheet</option>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
                    window.location.href = "<?php echo e(route('reports.lands')); ?>";
                    break;
                case 'allocations':
                    window.location.href = "<?php echo e(route('reports.allocations')); ?>";
                    break;
                case 'clients':
                    window.location.href = "<?php echo e(route('reports.clients')); ?>";
                    break;
                case 'chiefs':
                    window.location.href = "<?php echo e(route('reports.chiefs')); ?>";
                    break;
                case 'comprehensive':
                    window.location.href = "<?php echo e(route('reports.system')); ?>";
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/reports/index.blade.php ENDPATH**/ ?>