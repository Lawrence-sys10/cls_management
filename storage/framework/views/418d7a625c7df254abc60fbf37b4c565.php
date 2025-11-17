<?php $__env->startSection('title', 'Client: ' . $client->full_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Client: <?php echo e($client->full_name); ?></h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="<?php echo e(route('clients.edit', $client)); ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?php echo e(route('clients.allocations', $client)); ?>" class="btn btn-info">
                    <i class="fas fa-landmark"></i> Allocations
                </a>
                <a href="<?php echo e(route('clients.documents', $client)); ?>" class="btn btn-secondary">
                    <i class="fas fa-file"></i> Documents
                </a>
                <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Client Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Full Name:</th>
                            <td><strong><?php echo e($client->full_name); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><?php echo e($client->phone); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo e($client->email ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>ID Type:</th>
                            <td><?php echo e(ucfirst($client->id_type)); ?></td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td><?php echo e($client->id_number); ?></td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td><?php echo e(ucfirst($client->gender)); ?></td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td><?php echo e($client->date_of_birth ? $client->date_of_birth->format('M d, Y') : 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Occupation:</th>
                            <td><?php echo e($client->occupation ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td><?php echo e($client->address ?? 'N/A'); ?></td>
                        </tr>
                        <?php if($client->emergency_contact): ?>
                        <tr>
                            <th>Emergency Contact:</th>
                            <td><?php echo e($client->emergency_contact); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Registered:</th>
                            <td><?php echo e($client->created_at->format('M d, Y')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Allocations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Land Allocations (<?php echo e($client->allocations->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if($client->allocations->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Plot Number</th>
                                        <th>Location</th>
                                        <th>Allocation Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $client->allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('lands.show', $allocation->land_id)); ?>">
                                                    <?php echo e($allocation->land->plot_number ?? 'N/A'); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($allocation->land->location ?? 'N/A'); ?></td>
                                            <td><?php echo e($allocation->allocation_date->format('M d, Y')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger')); ?>">
                                                    <?php echo e(ucfirst($allocation->approval_status)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No land allocations for this client.</p>
                        <a href="<?php echo e(route('allocations.create')); ?>" class="btn btn-sm btn-primary">
                            Create Allocation
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documents Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Documents (<?php echo e($client->documents->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if($client->documents->count() > 0): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $client->documents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                        <?php if($client->documents->count() > 3): ?>
                            <div class="text-center mt-2">
                                <a href="<?php echo e(route('clients.documents', $client)); ?>" class="btn btn-sm btn-outline-primary">
                                    View All Documents
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">No documents uploaded.</p>
                        <a href="<?php echo e(route('clients.documents', $client)); ?>" class="btn btn-sm btn-primary">
                            Upload Documents
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Client Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-0"><?php echo e($client->allocations->count()); ?></h4>
                                <small class="text-muted">Total Allocations</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-0">
                                    <?php echo e($client->allocations->where('approval_status', 'approved')->count()); ?>

                                </h4>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/clients/show.blade.php ENDPATH**/ ?>