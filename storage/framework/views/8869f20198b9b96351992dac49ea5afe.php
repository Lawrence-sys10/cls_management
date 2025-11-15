

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
                        <div class="card-body">
                            <?php if($clients->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="clientsTable">
                                        <thead>
                                            <tr>
                                                <th>Client ID</th>
                                                <th>Full Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>ID Type</th>
                                                <th>ID Number</th>
                                                <th>Occupation</th>
                                                <th>Status</th>
                                                <th>Total Allocations</th>
                                                <th>Registered Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>#<?php echo e($client->id); ?></td>
                                                    <td>
                                                        <strong><?php echo e($client->full_name); ?></strong>
                                                        <?php if($client->date_of_birth): ?>
                                                            <br><small class="text-muted">Age: <?php echo e(\Carbon\Carbon::parse($client->date_of_birth)->age); ?> years</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($client->phone); ?></td>
                                                    <td><?php echo e($client->email ?? 'N/A'); ?></td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?php echo e(ucfirst(str_replace('_', ' ', $client->id_type))); ?>

                                                        </span>
                                                    </td>
                                                    <td><?php echo e($client->id_number); ?></td>
                                                    <td><?php echo e($client->occupation ?? 'N/A'); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo e($client->is_active ? 'bg-success' : 'bg-danger'); ?>">
                                                            <?php echo e($client->is_active ? 'Active' : 'Inactive'); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?php echo e($client->allocations_count ?? $client->allocations->count()); ?>

                                                        </span>
                                                    </td>
                                                    <td><?php echo e($client->created_at->format('M d, Y')); ?></td>
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

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs
        const today = new Date().toISOString().split('T')[0];
        if (document.getElementById('end_date')) {
            document.getElementById('end_date').max = today;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/reports/clients.blade.php ENDPATH**/ ?>