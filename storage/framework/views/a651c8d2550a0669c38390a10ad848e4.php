

<?php $__env->startSection('title', 'Land Details - ' . $land->plot_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Land Details
                    </h5>
                    <div class="btn-group">
                        <a href="<?php echo e(route('chief.lands.edit', $land)); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="<?php echo e(route('chief.lands.index')); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Plot Number:</th>
                                    <td><?php echo e($land->plot_number); ?></td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td><?php echo e($land->location); ?></td>
                                </tr>
                                <tr>
                                    <th>Landmark:</th>
                                    <td><?php echo e($land->landmark ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Area:</th>
                                    <td><?php echo e(number_format($land->area_acres, 2)); ?> acres (<?php echo e(number_format($land->area_hectares, 2)); ?> ha)</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Land Use:</th>
                                    <td class="text-capitalize"><?php echo e(str_replace('_', ' ', $land->land_use)); ?></td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td><?php echo e($land->price ? '₵' . number_format($land->price) : 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php
                                            $statusClass = match($land->ownership_status) {
                                                'vacant' => 'badge-success',
                                                'allocated' => 'badge-primary',
                                                'under_dispute' => 'badge-warning',
                                                default => 'badge-secondary'
                                            };
                                        ?>
                                        <span class="badge <?php echo e($statusClass); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $land->ownership_status))); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Coordinates:</th>
                                    <td><?php echo e($land->coordinates ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($land->description): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Description</h6>
                            <p class="text-muted"><?php echo e($land->description); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($land->ownership_status === 'vacant'): ?>
                        <a href="<?php echo e(route('chief.allocations.create')); ?>?land_id=<?php echo e($land->id); ?>" 
                           class="btn btn-success">
                            <i class="fas fa-handshake me-2"></i>Allocate Land
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('chief.lands.documents', $land)); ?>" class="btn btn-info">
                            <i class="fas fa-file-alt me-2"></i>View Documents
                        </a>

                        <?php if($land->ownership_status === 'under_dispute'): ?>
                        <a href="<?php echo e(route('chief.disputes.index')); ?>?land_id=<?php echo e($land->id); ?>" class="btn btn-warning">
                            <i class="fas fa-gavel me-2"></i>View Dispute
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Land Information Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Land Information</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        <i class="fas fa-calendar me-1"></i>
                        Created: <?php echo e($land->created_at->format('M j, Y')); ?>

                    </small>
                    <small class="text-muted d-block">
                        <i class="fas fa-sync me-1"></i>
                        Updated: <?php echo e($land->updated_at->format('M j, Y')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/lands/show.blade.php ENDPATH**/ ?>