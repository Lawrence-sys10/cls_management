

<?php $__env->startSection('title', 'Allocation Details: ' . $allocation->land->plot_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Allocation Details</h1>
            <p class="text-muted">Plot: <?php echo e($allocation->land->plot_number); ?> • Client: <?php echo e($allocation->client->full_name); ?></p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="<?php echo e(route('allocations.edit', $allocation)); ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?php echo e(route('allocations.allocation-letter', $allocation)); ?>" class="btn btn-info">
                    <i class="fas fa-file-pdf"></i> Allocation Letter
                </a>
                <a href="<?php echo e(route('allocations.certificate', $allocation)); ?>" class="btn btn-success">
                    <i class="fas fa-certificate"></i> Certificate
                </a>
                <a href="<?php echo e(route('allocations.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Allocation Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Allocation Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Allocation ID:</th>
                            <td><strong>#<?php echo e($allocation->id); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Allocation Date:</th>
                            <td><?php echo e($allocation->allocation_date->format('M d, Y')); ?></td>
                        </tr>
                        <tr>
                            <th>Approval Status:</th>
                            <td>
                                <span class="badge bg-<?php echo e($allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($allocation->approval_status)); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                <span class="badge bg-<?php echo e($allocation->payment_status == 'paid' ? 'success' : ($allocation->payment_status == 'partial' ? 'warning' : 'secondary')); ?>">
                                    <?php echo e(ucfirst($allocation->payment_status)); ?>

                                </span>
                            </td>
                        </tr>
                        <?php if($allocation->payment_amount): ?>
                        <tr>
                            <th>Payment Amount:</th>
                            <td>GHS <?php echo e(number_format($allocation->payment_amount, 2)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($allocation->payment_date): ?>
                        <tr>
                            <th>Payment Date:</th>
                            <td><?php echo e($allocation->payment_date->format('M d, Y')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($allocation->chief_approval_date): ?>
                        <tr>
                            <th>Chief Approval:</th>
                            <td><?php echo e($allocation->chief_approval_date->format('M d, Y')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($allocation->registrar_approval_date): ?>
                        <tr>
                            <th>Registrar Approval:</th>
                            <td><?php echo e($allocation->registrar_approval_date->format('M d, Y')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Finalized:</th>
                            <td>
                                <?php if($allocation->is_finalized): ?>
                                    <span class="badge bg-success">Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">No</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if($allocation->purpose): ?>
                        <tr>
                            <th>Purpose:</th>
                            <td><?php echo e($allocation->purpose); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($allocation->notes): ?>
                        <tr>
                            <th>Notes:</th>
                            <td><?php echo e($allocation->notes); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Approval Actions -->
            <?php if(!$allocation->is_finalized): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Approval Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($allocation->approval_status != 'approved'): ?>
                        <form action="<?php echo e(route('allocations.approve', $allocation)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this allocation?')">
                                <i class="fas fa-check"></i> Approve Allocation
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if($allocation->approval_status != 'rejected'): ?>
                        <form action="<?php echo e(route('allocations.reject', $allocation)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this allocation?')">
                                <i class="fas fa-times"></i> Reject Allocation
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if($allocation->approval_status != 'pending'): ?>
                        <form action="<?php echo e(route('allocations.pending', $allocation)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Mark this allocation as pending?')">
                                <i class="fas fa-clock"></i> Mark as Pending
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Related Information -->
        <div class="col-md-6">
            <!-- Land Information -->
            <div class="card">
                <div class="card-header">
                    <h5>Land Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Plot Number:</th>
                            <td>
                                <a href="<?php echo e(route('lands.show', $allocation->land)); ?>">
                                    <?php echo e($allocation->land->plot_number); ?>

                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td><?php echo e($allocation->land->location); ?></td>
                        </tr>
                        <tr>
                            <th>Area:</th>
                            <td><?php echo e(number_format($allocation->land->area_acres, 2)); ?> acres</td>
                        </tr>
                        <tr>
                            <th>Chief:</th>
                            <td><?php echo e($allocation->land->chief->name); ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-<?php echo e($allocation->land->ownership_status == 'available' ? 'success' : ($allocation->land->ownership_status == 'allocated' ? 'primary' : 'warning')); ?>">
                                    <?php echo e(ucfirst($allocation->land->ownership_status)); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Land Use:</th>
                            <td><?php echo e(ucfirst($allocation->land->land_use)); ?></td>
                        </tr>
                        <?php if($allocation->land->price): ?>
                        <tr>
                            <th>Price:</th>
                            <td>GHS <?php echo e(number_format($allocation->land->price, 2)); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Client Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Client Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Name:</th>
                            <td>
                                <a href="<?php echo e(route('clients.show', $allocation->client)); ?>">
                                    <?php echo e($allocation->client->full_name); ?>

                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><?php echo e($allocation->client->phone); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo e($allocation->client->email ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>ID Type:</th>
                            <td><?php echo e(ucfirst($allocation->client->id_type)); ?></td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td><?php echo e($allocation->client->id_number); ?></td>
                        </tr>
                        <tr>
                            <th>Occupation:</th>
                            <td><?php echo e($allocation->client->occupation ?? 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Processing Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Processing Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="120">Processed By:</th>
                            <td><?php echo e($allocation->processor->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Chief:</th>
                            <td><?php echo e($allocation->chief->name); ?></td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td><?php echo e($allocation->created_at->format('M d, Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td><?php echo e($allocation->updated_at->format('M d, Y H:i')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Documents Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Documents (<?php echo e($allocation->documents->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if($allocation->documents->count() > 0): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $allocation->documents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($document->document_type); ?></h6>
                                            <small class="text-muted"><?php echo e($document->file_name); ?></small>
                                        </div>
                                        <a href="<?php echo e(Storage::url($document->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($allocation->documents->count() > 3): ?>
                            <div class="text-center mt-2">
                                <a href="<?php echo e(route('documents.index')); ?>?allocation_id=<?php echo e($allocation->id); ?>" class="btn btn-sm btn-outline-primary">
                                    View All Documents
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">No documents uploaded for this allocation.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table-borderless td, .table-borderless th {
        border: none;
        padding: 0.5rem 0.25rem;
    }
    .table-sm td, .table-sm th {
        padding: 0.25rem;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/allocations/show.blade.php ENDPATH**/ ?>