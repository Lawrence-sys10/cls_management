<?php $__env->startSection('title', 'Land Allocations'); ?>
<?php $__env->startSection('subtitle', 'Manage land allocation records'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Allocations</h3>
                    <div class="stat-value"><?php echo e($allocations->total()); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>Active allocations</span>
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
                    <h3>Pending Approvals</h3>
                    <div class="stat-value"><?php echo e($allocations->where('approval_status', 'pending')->count()); ?></div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Needs attention</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Approved</h3>
                    <div class="stat-value"><?php echo e($allocations->where('approval_status', 'approved')->count()); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-check-circle"></i>
                        <span>Completed allocations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocations Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Land Allocations</h5>
            <a href="<?php echo e(route('allocations.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Allocation
            </a>
        </div>
        <div class="card-body">
            <?php if($allocations->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Plot Number</th>
                            <th>Client</th>
                            <th>Chief</th>
                            <th>Status</th>
                            <th>Allocation Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($allocation->land->plot_number ?? 'N/A'); ?></td>
                            <td><?php echo e($allocation->client->full_name ?? 'N/A'); ?></td>
                            <td><?php echo e($allocation->chief->name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge 
                                    <?php if($allocation->approval_status == 'approved'): ?> bg-success
                                    <?php elseif($allocation->approval_status == 'pending'): ?> bg-warning
                                    <?php else: ?> bg-danger
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($allocation->approval_status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($allocation->created_at->format('M d, Y')); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('allocations.show', $allocation)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('allocations.edit', $allocation)); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('allocations.destroy', $allocation)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
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
                    Showing <?php echo e($allocations->firstItem()); ?> to <?php echo e($allocations->lastItem()); ?> of <?php echo e($allocations->total()); ?> entries
                </div>
                <?php echo e($allocations->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No allocations found</h5>
                <p class="text-muted">Get started by creating your first land allocation.</p>
                <a href="<?php echo e(route('allocations.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Allocation
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
    }
    
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--primary);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
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
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: var(--danger);
    }
    
    .footer {
        text-align: center;
        padding: 1.5rem;
        color: var(--gray-600);
        font-size: 0.85rem;
        border-top: 1px solid var(--gray-200);
        margin-top: 2rem;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/allocations/index.blade.php ENDPATH**/ ?>