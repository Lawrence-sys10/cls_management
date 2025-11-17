

<?php $__env->startSection('title', 'Allocations for ' . $client->full_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Allocations for <?php echo e($client->full_name); ?></h1>
            <p class="text-muted">Client ID: <?php echo e($client->id_number); ?> • Phone: <?php echo e($client->phone); ?></p>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('clients.show', $client)); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Client
            </a>
            <a href="<?php echo e(route('allocations.create')); ?>?client_id=<?php echo e($client->id); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Allocation
            </a>
        </div>
    </div>

    <!-- Client Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo e($allocations->total()); ?></h4>
                    <small>Total Allocations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo e($allocations->where('approval_status', 'approved')->count()); ?></h4>
                    <small>Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo e($allocations->where('approval_status', 'pending')->count()); ?></h4>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">GHS <?php echo e(number_format($allocations->sum('payment_amount'), 2)); ?></h4>
                    <small>Total Payments</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocations Table -->
    <div class="card">
        <div class="card-body">
            <?php if($allocations->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Allocation ID</th>
                                <th>Plot Number</th>
                                <th>Location</th>
                                <th>Allocation Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($allocation->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('lands.show', $allocation->land)); ?>">
                                            <?php echo e($allocation->land->plot_number); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($allocation->land->location); ?></td>
                                    <td><?php echo e($allocation->allocation_date->format('M d, Y')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst($allocation->approval_status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($allocation->payment_status == 'paid' ? 'success' : ($allocation->payment_status == 'partial' ? 'warning' : 'secondary')); ?>">
                                            <?php echo e(ucfirst($allocation->payment_status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($allocation->payment_amount): ?>
                                            GHS <?php echo e(number_format($allocation->payment_amount, 2)); ?>

                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('allocations.show', $allocation)); ?>" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('allocations.edit', $allocation)); ?>" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing <?php echo e($allocations->firstItem()); ?> to <?php echo e($allocations->lastItem()); ?> of <?php echo e($allocations->total()); ?> allocations
                    </div>
                    <?php echo e($allocations->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-landmark fa-4x text-muted mb-3"></i>
                    <h4>No Allocations Found</h4>
                    <p class="text-muted">This client doesn't have any land allocations yet.</p>
                    <a href="<?php echo e(route('allocations.create')); ?>?client_id=<?php echo e($client->id); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Allocation
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/clients/allocations.blade.php ENDPATH**/ ?>