

<?php $__env->startSection('title', 'Disputes'); ?>
<?php $__env->startSection('subtitle', 'Manage land allocation disputes and resolutions'); ?>

<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('chief.disputes.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Dispute
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Disputes</h3>
                    <div class="stat-value"><?php echo e($disputes->total()); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-gavel"></i>
                        <span>All disputes</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-gavel"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Pending</h3>
                    <?php
                        $pendingDisputes = $disputes->where('status', 'pending')->count();
                    ?>
                    <div class="stat-value"><?php echo e($pendingDisputes); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-clock"></i>
                        <span>Require attention</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>In Progress</h3>
                    <?php
                        $inProgressDisputes = $disputes->whereIn('status', ['investigation', 'hearing'])->count();
                    ?>
                    <div class="stat-value"><?php echo e($inProgressDisputes); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-spinner"></i>
                        <span>Under investigation</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Resolved</h3>
                    <?php
                        $resolvedDisputes = $disputes->where('status', 'resolved')->count();
                    ?>
                    <div class="stat-value"><?php echo e($resolvedDisputes); ?></div>
                    <div class="stat-trend">
                        <i class="fas fa-check-circle"></i>
                        <span>Successfully closed</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Disputes Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dispute Records</h5>
            <div class="header-actions">
                <a href="<?php echo e(route('chief.disputes.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>New Dispute
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                               class="form-control"
                               placeholder="Case number, description, plot number, client name...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="all" <?php echo e(request('status') == 'all' || !request('status') ? 'selected' : ''); ?>>All Status</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="investigation" <?php echo e(request('status') == 'investigation' ? 'selected' : ''); ?>>Investigation</option>
                            <option value="hearing" <?php echo e(request('status') == 'hearing' ? 'selected' : ''); ?>>Hearing</option>
                            <option value="resolved" <?php echo e(request('status') == 'resolved' ? 'selected' : ''); ?>>Resolved</option>
                            <option value="closed" <?php echo e(request('status') == 'closed' ? 'selected' : ''); ?>>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="">All Priorities</option>
                            <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>Low</option>
                            <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>Medium</option>
                            <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>High</option>
                            <option value="critical" <?php echo e(request('priority') == 'critical' ? 'selected' : ''); ?>>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <?php if($disputes->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover" id="disputesTable">
                    <thead>
                        <tr>
                            <th>Case Details</th>
                            <th>Parties</th>
                            <th>Land Details</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Filed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $disputes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dispute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $statusColors = [
                                'pending' => 'warning',
                                'investigation' => 'info', 
                                'hearing' => 'primary',
                                'resolved' => 'success',
                                'closed' => 'secondary'
                            ];
                            
                            $priorityColors = [
                                'low' => 'success',
                                'medium' => 'info',
                                'high' => 'warning',
                                'critical' => 'danger'
                            ];
                            
                            $statusColor = $statusColors[$dispute->status] ?? 'secondary';
                            $priorityColor = $priorityColors[$dispute->severity] ?? 'secondary';
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="dispute-avatar me-3">
                                        <div class="avatar-circle bg-<?php echo e($priorityColor); ?> text-white">
                                            <?php echo e(substr($dispute->case_number, 0, 1)); ?>

                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">
                                            <?php echo e($dispute->case_number); ?>

                                        </div>
                                        <div class="text-muted small text-capitalize">
                                            <?php echo e(str_replace('_', ' ', $dispute->dispute_type)); ?>

                                        </div>
                                        <?php if($dispute->description): ?>
                                        <small class="text-muted"><?php echo e(Str::limit($dispute->description, 50)); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small">Complainant:</div>
                                <div class="text-dark"><?php echo e($dispute->complainant->full_name ?? 'N/A'); ?></div>
                                
                                <?php if($dispute->respondent): ?>
                                <div class="fw-semibold text-dark small mt-1">Respondent:</div>
                                <div class="text-dark"><?php echo e($dispute->respondent->full_name); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($dispute->land): ?>
                                <div class="fw-semibold text-dark">
                                    Plot <?php echo e($dispute->land->plot_number); ?>

                                </div>
                                <small class="text-muted"><?php echo e(Str::limit($dispute->land->location, 25)); ?></small>
                                <?php if($dispute->land->size): ?>
                                <br>
                                <small class="text-muted"><?php echo e($dispute->land->size); ?> acres</small>
                                <?php endif; ?>
                                <?php else: ?>
                                <span class="text-muted">Land not found</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($priorityColor); ?>">
                                    <i class="fas fa-flag me-1"></i><?php echo e(ucfirst($dispute->severity)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($statusColor); ?>">
                                    <?php if($dispute->status == 'pending'): ?>
                                        <i class="fas fa-clock me-1"></i>
                                    <?php elseif($dispute->status == 'investigation'): ?>
                                        <i class="fas fa-search me-1"></i>
                                    <?php elseif($dispute->status == 'hearing'): ?>
                                        <i class="fas fa-gavel me-1"></i>
                                    <?php elseif($dispute->status == 'resolved'): ?>
                                        <i class="fas fa-check me-1"></i>
                                    <?php else: ?>
                                        <i class="fas fa-times me-1"></i>
                                    <?php endif; ?>
                                    <?php echo e(ucfirst($dispute->status)); ?>

                                </span>
                                
                                <?php if($dispute->status == 'pending' && $dispute->filing_date->diffInDays(now()) > 7): ?>
                                <br>
                                <small class="text-warning mt-1">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-dark"><?php echo e($dispute->filing_date->format('M j, Y')); ?></div>
                                <small class="text-muted"><?php echo e($dispute->filing_date->diffForHumans()); ?></small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('chief.disputes.show', $dispute)); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('chief.disputes.edit', $dispute)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit Dispute">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($dispute->status != 'resolved' && $dispute->status != 'closed'): ?>
                                    <a href="<?php echo e(route('chief.disputes.resolve', $dispute)); ?>" class="btn btn-sm btn-outline-success" title="Resolve Dispute">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if($dispute->status == 'resolved'): ?>
                                    <form action="<?php echo e(route('chief.disputes.close', $dispute)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Close Dispute">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($dispute->status == 'closed'): ?>
                                    <form action="<?php echo e(route('chief.disputes.reopen', $dispute)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Reopen Dispute">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('chief.disputes.destroy', $dispute)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Dispute" onclick="return confirm('Are you sure you want to delete this dispute?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                    Showing <?php echo e($disputes->firstItem()); ?> to <?php echo e($disputes->lastItem()); ?> of <?php echo e($disputes->total()); ?> entries
                </div>
                <?php echo e($disputes->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No dispute records found</h5>
                <p class="text-muted">
                    <?php if(request('search') || request('status') || request('priority')): ?>
                        No disputes match your search criteria.
                    <?php else: ?>
                        No disputes have been recorded yet.
                    <?php endif; ?>
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php echo e(route('chief.disputes.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Dispute
                    </a>
                    <?php if(request('search') || request('status') || request('priority')): ?>
                    <a href="<?php echo e(route('chief.disputes.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
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
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid #667eea;
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
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
        color: #343a40;
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
        color: #667eea;
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stat-card:nth-child(4) .stat-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: #6c757d;
    }
    
    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .badge-info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    
    .badge-primary {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }
    
    .badge-secondary {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
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
    
    .dispute-avatar .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white;
    }
    
    .card-header h5 {
        color: white;
        margin-bottom: 0;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
    }
    
    .avatar-circle.bg-danger { background: linear-gradient(135deg, #dc3545, #c82333) !important; }
    .avatar-circle.bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800) !important; }
    .avatar-circle.bg-info { background: linear-gradient(135deg, #17a2b8, #138496) !important; }
    .avatar-circle.bg-success { background: linear-gradient(135deg, #28a745, #218838) !important; }
    .avatar-circle.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#disputesTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [[5, 'desc']],
            language: {
                emptyTable: "No dispute records found"
            },
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });

        // Auto-submit form when filters change
        $('#status, #priority').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/disputes/index.blade.php ENDPATH**/ ?>