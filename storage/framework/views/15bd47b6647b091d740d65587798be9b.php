

<?php $__env->startSection('title', 'Lands Report'); ?>
<?php $__env->startSection('subtitle', 'Comprehensive Lands Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Lands Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="<?php echo e(route('reports.lands')); ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="available" <?php echo e(request('status') == 'available' ? 'selected' : ''); ?>>Available</option>
                                                    <option value="occupied" <?php echo e(request('status') == 'occupied' ? 'selected' : ''); ?>>Occupied</option>
                                                    <option value="disputed" <?php echo e(request('status') == 'disputed' ? 'selected' : ''); ?>>Disputed</option>
                                                    <option value="sold" <?php echo e(request('status') == 'sold' ? 'selected' : ''); ?>>Sold</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="chief_id" class="form-label">Chief</label>
                                                <select name="chief_id" id="chief_id" class="form-select select2">
                                                    <option value="">All Chiefs</option>
                                                    <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($chief->id); ?>" <?php echo e(request('chief_id') == $chief->id ? 'selected' : ''); ?>>
                                                            <?php echo e($chief->full_name); ?> - <?php echo e($chief->community); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                                </button>
                                                <a href="<?php echo e(route('reports.lands')); ?>" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="<?php echo e(route('reports.lands.generate')); ?>" method="POST" class="d-inline">
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
                                                            <form action="<?php echo e(route('reports.lands.generate')); ?>" method="POST" class="d-inline">
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
                                            <h4 class="mb-0"><?php echo e($lands->count()); ?></h4>
                                            <p class="mb-0">Total Lands</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-map fa-2x"></i>
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
                                            <h4 class="mb-0"><?php echo e($lands->where('status', 'available')->count()); ?></h4>
                                            <p class="mb-0">Available</p>
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
                                            <h4 class="mb-0"><?php echo e($lands->where('status', 'occupied')->count()); ?></h4>
                                            <p class="mb-0">Occupied</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-home fa-2x"></i>
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
                                            <h4 class="mb-0"><?php echo e($lands->where('status', 'disputed')->count()); ?></h4>
                                            <p class="mb-0">Disputed</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lands Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lands Details</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if($lands->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped mb-0" id="landsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="70" class="small fw-bold px-2 py-1">ID</th>
                                                <th width="150" class="small fw-bold px-2 py-1">Location</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Size</th>
                                                <th width="90" class="small fw-bold px-2 py-1">Status</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Client</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Chief</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Price</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $lands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $land): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="small px-2 py-1">#<?php echo e($land->id); ?></td>
                                                    <td class="small px-2 py-1">
                                                        <div class="fw-semibold text-truncate" title="<?php echo e($land->location); ?>">
                                                            <?php echo e($land->location); ?>

                                                        </div>
                                                        <?php if($land->landmarks): ?>
                                                            <small class="text-muted text-truncate d-block" title="<?php echo e($land->landmarks); ?>">
                                                                <?php echo e($land->landmarks); ?>

                                                            </small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <?php echo e(number_format($land->size, 1)); ?>

                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge 
                                                            <?php if($land->status == 'available'): ?> bg-success
                                                            <?php elseif($land->status == 'occupied'): ?> bg-warning
                                                            <?php elseif($land->status == 'disputed'): ?> bg-danger
                                                            <?php elseif($land->status == 'sold'): ?> bg-info
                                                            <?php else: ?> bg-secondary <?php endif; ?>">
                                                            <?php echo e(ucfirst($land->status)); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php if($land->client): ?>
                                                            <div class="text-truncate" title="<?php echo e($land->client->full_name); ?>">
                                                                <?php echo e($land->client->full_name); ?>

                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted">No Client</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php if($land->chief): ?>
                                                            <div class="text-truncate" title="<?php echo e($land->chief->full_name); ?>">
                                                                <?php echo e($land->chief->full_name); ?>

                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted">No Chief</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <?php if($land->price): ?>
                                                            <?php echo e(number_format($land->price, 0)); ?>

                                                        <?php else: ?>
                                                            <span class="text-muted">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <?php echo e($land->created_at->format('M d, Y')); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                    <h5>No lands found</h5>
                                    <p class="text-muted">No land records match your current filters.</p>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Select2 Styling */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 4px 12px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
    }

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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for chief dropdown only
        $('#chief_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select Chief...',
            allowClear: true,
            width: '100%'
        });

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/reports/lands.blade.php ENDPATH**/ ?>