

<?php $__env->startSection('title', 'Chiefs Report'); ?>
<?php $__env->startSection('subtitle', 'Comprehensive Chiefs Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-crown me-2"></i>Chiefs Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="<?php echo e(route('reports.chiefs')); ?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="region" class="form-label">Region</label>
                                                <select name="region" id="region" class="form-select">
                                                    <option value="">All Regions</option>
                                                    <option value="Greater Accra" <?php echo e(request('region') == 'Greater Accra' ? 'selected' : ''); ?>>Greater Accra</option>
                                                    <option value="Ashanti" <?php echo e(request('region') == 'Ashanti' ? 'selected' : ''); ?>>Ashanti</option>
                                                    <option value="Western" <?php echo e(request('region') == 'Western' ? 'selected' : ''); ?>>Western</option>
                                                    <option value="Eastern" <?php echo e(request('region') == 'Eastern' ? 'selected' : ''); ?>>Eastern</option>
                                                    <option value="Central" <?php echo e(request('region') == 'Central' ? 'selected' : ''); ?>>Central</option>
                                                    <option value="Volta" <?php echo e(request('region') == 'Volta' ? 'selected' : ''); ?>>Volta</option>
                                                    <option value="Northern" <?php echo e(request('region') == 'Northern' ? 'selected' : ''); ?>>Northern</option>
                                                    <option value="Upper East" <?php echo e(request('region') == 'Upper East' ? 'selected' : ''); ?>>Upper East</option>
                                                    <option value="Upper West" <?php echo e(request('region') == 'Upper West' ? 'selected' : ''); ?>>Upper West</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" id="start_date" 
                                                       value="<?php echo e(request('start_date')); ?>" class="form-control">
                                            </div>
                                            <div class="col-md-4">
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
                                                <a href="<?php echo e(route('reports.chiefs')); ?>" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="<?php echo e(route('reports.chiefs.generate')); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                                                                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                                                                <input type="hidden" name="region" value="<?php echo e(request('region')); ?>">
                                                                <input type="hidden" name="format" value="pdf">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?php echo e(route('reports.chiefs.generate')); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                                                                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                                                                <input type="hidden" name="region" value="<?php echo e(request('region')); ?>">
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
                                            <h4 class="mb-0"><?php echo e($chiefs->count()); ?></h4>
                                            <p class="mb-0">Total Chiefs</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-crown fa-2x"></i>
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
                                            <h4 class="mb-0"><?php echo e($chiefs->where('is_active', true)->count()); ?></h4>
                                            <p class="mb-0">Active Chiefs</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0"><?php echo e($chiefs->sum('lands_count')); ?></h4>
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
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0"><?php echo e($chiefs->sum(function($chief) { return $chief->lands->where('ownership_status', 'allocated')->count(); })); ?></h4>
                                            <p class="mb-0">Allocated Lands</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chiefs Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Chiefs Details</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if($chiefs->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped mb-0" id="chiefsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="70" class="small fw-bold px-2 py-1">ID</th>
                                                <th width="140" class="small fw-bold px-2 py-1">Full Name</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Title</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Traditional Area</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Community</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Region</th>
                                                <th width="110" class="small fw-bold px-2 py-1">Phone</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Status</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Lands</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Years</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Registered</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="small px-2 py-1">#<?php echo e($chief->id); ?></td>
                                                    <td class="small px-2 py-1">
                                                        <div class="fw-semibold text-truncate" title="<?php echo e($chief->full_name); ?>">
                                                            <?php echo e($chief->full_name); ?>

                                                        </div>
                                                        <?php if($chief->email): ?>
                                                            <small class="text-muted text-truncate d-block" title="<?php echo e($chief->email); ?>">
                                                                <?php echo e($chief->email); ?>

                                                            </small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="<?php echo e($chief->title); ?>">
                                                        <?php echo e($chief->title); ?>

                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="<?php echo e($chief->traditional_area); ?>">
                                                        <?php echo e($chief->traditional_area); ?>

                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="<?php echo e($chief->community); ?>">
                                                        <?php echo e($chief->community); ?>

                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <span class="badge bg-secondary">
                                                            <?php echo e($chief->region); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1"><?php echo e($chief->phone); ?></td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge <?php echo e($chief->is_active ? 'bg-success' : 'bg-danger'); ?>">
                                                            <?php echo e($chief->is_active ? 'Active' : 'Inactive'); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge bg-primary">
                                                            <?php echo e($chief->lands_count ?? $chief->lands->count()); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <?php echo e($chief->years_of_service ?? 'N/A'); ?>

                                                    </td>
                                                    <td class="small px-2 py-1"><?php echo e($chief->created_at->format('M d, Y')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                                    <h5>No chiefs found</h5>
                                    <p class="text-muted">No chief records match your current filters.</p>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/reports/chiefs.blade.php ENDPATH**/ ?>