<?php $__env->startSection('title', 'Chief Management'); ?>
<?php $__env->startSection('subtitle', 'Manage traditional chiefs and authorities'); ?>

<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('chiefs.export')); ?>" class="btn btn-success">
        <i class="fas fa-file-export me-2"></i>Export
    </a>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
    <a href="<?php echo e(route('chiefs.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Chief
    </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Chiefs</h3>
                    <div class="stat-value"><?php echo e($chiefs->total()); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-crown"></i>
                        <span>Registered chiefs</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Active Chiefs</h3>
                    <div class="stat-value"><?php echo e($chiefs->where('is_active', true)->count()); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-check-circle"></i>
                        <span>Currently active</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Lands</h3>
                    <div class="stat-value"><?php echo e($totalLands ?? 0); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Under management</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chiefs Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chief Records</h5>
            <div class="header-actions">
                <a href="<?php echo e(route('chiefs.export')); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i>Export
                </a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                <a href="<?php echo e(route('chiefs.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Chief
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search Chiefs</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                               class="form-control"
                               placeholder="Search by name, title, or region...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <?php if($chiefs->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover" id="chiefsTable">
                    <thead>
                        <tr>
                            <th>Chief</th>
                            <th>Title & Region</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Lands Managed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-crown text-warning fs-6"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-dark"><?php echo e($chief->name); ?></h6>
                                        <small class="text-muted">Since <?php echo e($chief->created_at->format('M Y')); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <div class="fw-semibold"><?php echo e($chief->title ?? 'Traditional Leader'); ?></div>
                                    <small class="text-muted"><?php echo e($chief->jurisdiction ?? $chief->region ?? 'Not specified'); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">
                                    <?php if($chief->phone): ?>
                                    <div><i class="fas fa-phone text-muted me-2"></i><?php echo e($chief->phone); ?></div>
                                    <?php endif; ?>
                                    <?php if($chief->email): ?>
                                    <div class="mt-1"><i class="fas fa-envelope text-muted me-2"></i><?php echo e($chief->email); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if($chief->is_active): ?>
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                                <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-map-marked-alt me-1"></i>
                                    <?php echo e($chief->lands_count ?? 0); ?> land(s)
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('chiefs.show', $chief)); ?>" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                                    <a href="<?php echo e(route('chiefs.edit', $chief)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
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
                    Showing <?php echo e($chiefs->firstItem()); ?> to <?php echo e($chiefs->lastItem()); ?> of <?php echo e($chiefs->total()); ?> entries
                </div>
                <?php echo e($chiefs->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No chiefs found</h5>
                <p class="text-muted">Get started by adding your first chief.</p>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                <a href="<?php echo e(route('chiefs.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Chief
                </a>
                <?php endif; ?>
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
        $('#chiefsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No chiefs found"
            },
            columnDefs: [
                { orderable: false, targets: [5] } // Disable sorting for actions column
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/index.blade.php ENDPATH**/ ?>