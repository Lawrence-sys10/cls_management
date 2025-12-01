<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Allocations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
        }
        .bg-primary, .bg-success, .bg-warning, .bg-danger {
            color: white !important;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #6e707e;
        }
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    

    <?php $__env->startSection('title', 'Client Allocations'); ?>

    <?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Allocations for <?php echo e($client->full_name); ?></h4>
                            <p class="card-subtitle">All land allocations for this client</p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Clients
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Client Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Client Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>Name:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1"><?php echo e($client->full_name); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>Email:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1"><?php echo e($client->email); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>Phone:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1"><?php echo e($client->phone ?? 'Not provided'); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>ID Number:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1"><?php echo e($client->id_number ?? 'Not provided'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Allocation Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Total Allocations:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><?php echo e($stats['total_allocations'] ?? 0); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Active Allocations:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><?php echo e($stats['active_allocations'] ?? 0); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Pending Approval:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><?php echo e($stats['pending_allocations'] ?? 0); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Rejected/Cancelled:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><?php echo e($stats['rejected_allocations'] ?? 0); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo e($stats['total_allocations'] ?? 0); ?></h3>
                                                <p>Total Allocations</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-file-contract fa-2x"></i>
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
                                                <h3><?php echo e($stats['approved_allocations'] ?? 0); ?></h3>
                                                <p>Approved</p>
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
                                                <h3><?php echo e($stats['pending_allocations'] ?? 0); ?></h3>
                                                <p>Pending</p>
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
                                                <h3><?php echo e($stats['rejected_allocations'] ?? 0); ?></h3>
                                                <p>Rejected</p>
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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Land Plot</th>
                                        <th>Allocation Date</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($allocation->land->plot_number); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($allocation->land->location); ?></small>
                                            </td>
                                            <td><?php echo e($allocation->allocation_date->format('M j, Y')); ?></td>
                                            <td><?php echo e(Str::limit($allocation->purpose, 50)); ?></td>
                                            <td>
                                                <?php if($allocation->approval_status == 'approved'): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php elseif($allocation->approval_status == 'pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo e(route('allocations.show', $allocation)); ?>" class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if(auth()->user()->can('edit_allocations') && $allocation->approval_status == 'pending'): ?>
                                                        <a href="<?php echo e(route('allocations.edit', $allocation)); ?>" class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if(auth()->user()->can('delete_allocations')): ?>
                                                        <form action="<?php echo e(route('allocations.destroy', $allocation)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this allocation?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-file-contract fa-2x mb-3"></i>
                                                    <p>No allocations found for this client</p>
                                                    <?php if(auth()->user()->can('create_allocations')): ?>
                                                        <a href="<?php echo e(route('allocations.create', ['client_id' => $client->id])); ?>" class="btn btn-primary mt-2">
                                                            <i class="fas fa-plus me-1"></i> Create New Allocation
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if($allocations->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-3">
                                <?php echo e($allocations->links()); ?>

                            </div>
                        <?php endif; ?>

                        <!-- Create New Allocation Button -->
                        <?php if(auth()->user()->can('create_allocations') && $allocations->count() > 0): ?>
                            <div class="mt-4 text-center">
                                <a href="<?php echo e(route('allocations.create', ['client_id' => $client->id])); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create New Allocation for This Client
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/clients/allocations.blade.php ENDPATH**/ ?>