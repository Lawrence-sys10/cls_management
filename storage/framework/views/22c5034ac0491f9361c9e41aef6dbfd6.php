

<?php $__env->startSection('title', 'Allocations Report'); ?>
<?php $__env->startSection('subtitle', 'Comprehensive Allocations Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-check me-2"></i>Allocations Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="<?php echo e(route('reports.allocations')); ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                                    <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                                    <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" id="start_date" 
                                                       value="<?php echo e(request('start_date')); ?>" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" name="end_date" id="end_date" 
                                                       value="<?php echo e(request('end_date')); ?>" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="chief_id" class="form-label">Chief</label>
                                                <select name="chief_id" id="chief_id" class="form-select">
                                                    <option value="">All Chiefs</option>
                                                    <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($chief->id); ?>" <?php echo e(request('chief_id') == $chief->id ? 'selected' : ''); ?>>
                                                            <?php echo e($chief->full_name); ?> - <?php echo e($chief->community); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                                </button>
                                                <a href="<?php echo e(route('reports.allocations')); ?>" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="<?php echo e(route('reports.allocations.generate')); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                                                                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                                                                <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                                                                <input type="hidden" name="chief_id" value="<?php echo e(request('chief_id')); ?>">
                                                                <input type="hidden" name="format" value="pdf">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?php echo e(route('reports.allocations.generate')); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                                                                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                                                                <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                                                                <input type="hidden" name="chief_id" value="<?php echo e(request('chief_id')); ?>">
                                                                <input type="hidden" name="format" value="excel">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-excel me-2"></i>Export as Excel
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0"><?php echo e($allocations->count()); ?></h4>
                                            <p class="mb-0">Total Allocations</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0"><?php echo e($allocations->where('status', 'approved')->count()); ?></h4>
                                            <p class="mb-0">Approved</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0"><?php echo e($allocations->where('status', 'pending')->count()); ?></h4>
                                            <p class="mb-0">Pending</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0"><?php echo e($allocations->where('status', 'rejected')->count()); ?></h4>
                                            <p class="mb-0">Rejected</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocations Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Allocations Details</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if($allocations->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped mb-0" id="allocationsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="70" class="small fw-bold px-2 py-1">ID</th>
                                                <th width="140" class="small fw-bold px-2 py-1">Client</th>
                                                <th width="150" class="small fw-bold px-2 py-1">Land Details</th>
                                                <th width="140" class="small fw-bold px-2 py-1">Chief</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Allocation Date</th>
                                                <th width="90" class="small fw-bold px-2 py-1">Status</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Duration</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="small px-2 py-1">#<?php echo e($allocation->id); ?></td>
                                                    <td class="small px-2 py-1">
                                                        <?php if($allocation->client): ?>
                                                            <div class="fw-semibold text-truncate" title="<?php echo e($allocation->client->full_name); ?>">
                                                                <?php echo e($allocation->client->full_name); ?>

                                                            </div>
                                                            <small class="text-muted"><?php echo e($allocation->client->phone); ?></small>
                                                        <?php else: ?>
                                                            <span class="text-muted">No Client</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php if($allocation->land): ?>
                                                            <div class="fw-semibold text-truncate" title="<?php echo e($allocation->land->location); ?>">
                                                                <?php echo e($allocation->land->location); ?>

                                                            </div>
                                                            <small class="text-muted">Plot: <?php echo e($allocation->land->plot_number); ?></small>
                                                            <small class="text-muted d-block"><?php echo e(number_format($allocation->land->area_acres, 1)); ?> acres</small>
                                                        <?php else: ?>
                                                            <span class="text-muted">No Land</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php if($allocation->chief): ?>
                                                            <div class="text-truncate" title="<?php echo e($allocation->chief->full_name); ?>">
                                                                <?php echo e($allocation->chief->full_name); ?>

                                                            </div>
                                                            <small class="text-muted text-truncate d-block" title="<?php echo e($allocation->chief->community); ?>">
                                                                <?php echo e($allocation->chief->community); ?>

                                                            </small>
                                                        <?php else: ?>
                                                            <span class="text-muted">No Chief</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php echo e($allocation->allocation_date ? \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') : 'N/A'); ?>

                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge 
                                                            <?php if($allocation->status == 'approved'): ?> bg-success
                                                            <?php elseif($allocation->status == 'pending'): ?> bg-warning
                                                            <?php elseif($allocation->status == 'rejected'): ?> bg-danger
                                                            <?php elseif($allocation->status == 'completed'): ?> bg-info
                                                            <?php else: ?> bg-secondary <?php endif; ?>">
                                                            <?php echo e(ucfirst($allocation->status)); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <?php echo e($allocation->duration_years ?? 'N/A'); ?>

                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php echo e($allocation->created_at->format('M d, Y')); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-list-check fa-3x text-muted mb-3"></i>
                                    <h5>No allocations found</h5>
                                    <p class="text-muted">No allocation records match your current filters.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Compact table styling */
    .table-sm {
        font-size: 0.8rem;
    }
    
    .table-sm th,
    .table-sm td {
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.25em 0.4em;
    }
    
    .text-truncate {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Ensure table fits within container */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    /* Compact card body for table */
    .card-body.p-0 {
        padding: 0 !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-sm {
            font-size: 0.75rem;
        }
        
        .table-sm th,
        .table-sm td {
            padding: 0.3rem 0.4rem;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.2em 0.3em;
        }
    }
    
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.7rem;
        }
        
        .table-sm th,
        .table-sm td {
            padding: 0.25rem 0.3rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs
        const today = new Date().toISOString().split('T')[0];
        if (document.getElementById('end_date')) {
            document.getElementById('end_date').max = today;
        }
        
        // Add tooltips for truncated text
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/reports/allocations.blade.php ENDPATH**/ ?>