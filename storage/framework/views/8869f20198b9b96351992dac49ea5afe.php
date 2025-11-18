

<?php $__env->startSection('title', 'Clients Report'); ?>
<?php $__env->startSection('subtitle', 'Comprehensive Clients Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Clients Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="<?php echo e(route('reports.clients')); ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
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
                                                <label for="id_type" class="form-label">ID Type</label>
                                                <select name="id_type" id="id_type" class="form-select">
                                                    <option value="">All ID Types</option>
                                                    <option value="ghanacard" <?php echo e(request('id_type') == 'ghanacard' ? 'selected' : ''); ?>>Ghana Card</option>
                                                    <option value="passport" <?php echo e(request('id_type') == 'passport' ? 'selected' : ''); ?>>Passport</option>
                                                    <option value="drivers_license" <?php echo e(request('id_type') == 'drivers_license' ? 'selected' : ''); ?>>Driver's License</option>
                                                    <option value="voters_id" <?php echo e(request('id_type') == 'voters_id' ? 'selected' : ''); ?>>Voter's ID</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                                </button>
                                                <a href="<?php echo e(route('reports.clients')); ?>" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="<?php echo e(route('reports.clients.generate')); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                                                                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                                                                <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                                                                <input type="hidden" name="id_type" value="<?php echo e(request('id_type')); ?>">
                                                                <input type="hidden" name="format" value="pdf">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?php echo e(route('reports.clients.generate')); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                                                                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                                                                <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                                                                <input type="hidden" name="id_type" value="<?php echo e(request('id_type')); ?>">
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
                                            <h4 class="mb-0"><?php echo e($clients->count()); ?></h4>
                                            <p class="mb-0">Total Clients</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
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
                                            <h4 class="mb-0"><?php echo e($clients->where('is_active', true)->count()); ?></h4>
                                            <p class="mb-0">Active Clients</p>
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
                                            <h4 class="mb-0"><?php echo e($clients->where('is_active', false)->count()); ?></h4>
                                            <p class="mb-0">Inactive Clients</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-slash fa-2x"></i>
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
                                            <h4 class="mb-0"><?php echo e($clients->whereNotNull('allocations')->count()); ?></h4>
                                            <p class="mb-0">With Allocations</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clients Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Clients Details</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if($clients->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped mb-0" id="clientsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="70" class="small fw-bold px-2 py-1">ID</th>
                                                <th width="150" class="small fw-bold px-2 py-1">Full Name</th>
                                                <th width="110" class="small fw-bold px-2 py-1">Phone</th>
                                                <th width="140" class="small fw-bold px-2 py-1">Email</th>
                                                <th width="90" class="small fw-bold px-2 py-1">ID Type</th>
                                                <th width="120" class="small fw-bold px-2 py-1">ID Number</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Occupation</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Status</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Allocations</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Registered</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="small px-2 py-1">#<?php echo e($client->id); ?></td>
                                                    <td class="small px-2 py-1">
                                                        <div class="fw-semibold text-truncate" title="<?php echo e($client->full_name); ?>">
                                                            <?php echo e($client->full_name); ?>

                                                        </div>
                                                        <?php if($client->date_of_birth): ?>
                                                            <small class="text-muted">Age: <?php echo e(\Carbon\Carbon::parse($client->date_of_birth)->age); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small px-2 py-1"><?php echo e($client->phone); ?></td>
                                                    <td class="small px-2 py-1 text-truncate" title="<?php echo e($client->email ?? 'N/A'); ?>">
                                                        <?php echo e($client->email ?? 'N/A'); ?>

                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <span class="badge bg-secondary">
                                                            <?php
                                                                $idType = $client->id_type;
                                                                if($idType == 'ghanacard') echo 'Ghana Card';
                                                                elseif($idType == 'passport') echo 'Passport';
                                                                elseif($idType == 'drivers_license') echo 'Driver License';
                                                                elseif($idType == 'voters_id') echo 'Voter ID';
                                                                else echo ucfirst($idType);
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="<?php echo e($client->id_number); ?>">
                                                        <?php echo e($client->id_number); ?>

                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="<?php echo e($client->occupation ?? 'N/A'); ?>">
                                                        <?php echo e($client->occupation ?? 'N/A'); ?>

                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge <?php echo e($client->is_active ? 'bg-success' : 'bg-danger'); ?>">
                                                            <?php echo e($client->is_active ? 'Active' : 'Inactive'); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge bg-primary">
                                                            <?php echo e($client->allocations_count ?? $client->allocations->count()); ?>

                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1"><?php echo e($client->created_at->format('M d, Y')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No clients found</h5>
                                    <p class="text-muted">No client records match your current filters.</p>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/reports/clients.blade.php ENDPATH**/ ?>