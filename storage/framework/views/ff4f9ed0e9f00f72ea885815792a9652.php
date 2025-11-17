<?php $__env->startSection('title', 'Client Management'); ?>
<?php $__env->startSection('subtitle', 'Manage client records and information'); ?>

<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('clients.export')); ?>" class="btn btn-success">
        <i class="fas fa-file-export me-2"></i>Export
    </a>
    <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Client
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Clients</h3>
                    <div class="stat-value"><?php echo e($clients->total()); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-users"></i>
                        <span>Registered clients</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Active Allocations</h3>
                    <div class="stat-value"><?php echo e($totalAllocations ?? 0); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-handshake"></i>
                        <span>Current allocations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>New This Month</h3>
                    <div class="stat-value"><?php echo e($newThisMonth ?? 0); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-user-plus"></i>
                        <span>Recent registrations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Client Records</h5>
            <div class="header-actions">
                <a href="<?php echo e(route('clients.export')); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i>Export
                </a>
                <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Client
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="search" class="form-label">Search Clients</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="form-control"
                               placeholder="Search by name, phone, email, or ID number...">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>

            <?php if($clients->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover" id="clientsTable">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>ID Information</th>
                            <th>Allocations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-primary fs-6"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark"><?php echo e($client->full_name); ?></h6>
                                        <small class="text-muted"><?php echo e($client->occupation ?? 'Not specified'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <div><i class="fas fa-phone text-muted me-2"></i><?php echo e($client->phone); ?></div>
                                    <?php if($client->email): ?>
                                    <div class="mt-1"><i class="fas fa-envelope text-muted me-2"></i><?php echo e($client->email); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-capitalize">
                                        <?php echo e(str_replace('_', ' ', $client->id_type)); ?>

                                    </span>
                                    <div class="mt-1 small text-muted"><?php echo e($client->id_number); ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-handshake me-1"></i>
                                    <?php echo e($client->allocations_count); ?> allocation(s)
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('clients.show', $client)); ?>" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('clients.edit', $client)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('clients.destroy', $client)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this client?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing <?php echo e($clients->firstItem()); ?> to <?php echo e($clients->lastItem()); ?> of <?php echo e($clients->total()); ?> entries
                </div>
                <?php echo e($clients->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No clients found</h5>
                <p class="text-muted">Get started by adding your first client.</p>
                <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Client
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--primary);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .stat-info h3 {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card:nth-child(1) .stat-icon {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: var(--gray-600);
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: var(--danger);
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 6px;
    }
    
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#clientsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No clients found"
            },
            columnDefs: [
                { orderable: false, targets: [4] } // Disable sorting for actions column
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/clients/index.blade.php ENDPATH**/ ?>