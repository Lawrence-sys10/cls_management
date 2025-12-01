

<?php $__env->startSection('title', 'Land Allocations'); ?>
<?php $__env->startSection('subtitle', 'Manage your land allocations and assignments'); ?>

<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('chief.allocations.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Allocation
    </a>
<?php $__env->stopSection(); ?>

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
                        <i class="fas fa-handshake"></i>
                        <span>All land allocations</span>
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
                    <h3>Active</h3>
                    <?php
                        $activeAllocations = $allocations->filter(function($allocation) {
                            return $allocation->status == 'active' && 
                                   !$allocation->allocation_date->addYears($allocation->duration_years)->isPast();
                        })->count();
                    ?>
                    <div class="stat-value"><?php echo e($activeAllocations); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-check-circle"></i>
                        <span>Current allocations</span>
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
                    <h3>Expired</h3>
                    <?php
                        $expiredAllocations = $allocations->filter(function($allocation) {
                            return $allocation->allocation_date->addYears($allocation->duration_years)->isPast();
                        })->count();
                    ?>
                    <div class="stat-value"><?php echo e($expiredAllocations); ?></div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-clock"></i>
                        <span>Expired allocations</span>
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
                    <h3>This Month</h3>
                    <?php
                        $thisMonthAllocations = $allocations->filter(function($allocation) {
                            return $allocation->created_at->greaterThanOrEqualTo(now()->startOfMonth());
                        })->count();
                    ?>
                    <div class="stat-value"><?php echo e($thisMonthAllocations); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-chart-line"></i>
                        <span>New this month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocations Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Land Allocation Records</h5>
            <div class="header-actions">
                <a href="<?php echo e(route('chief.allocations.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>New Allocation
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                               class="form-control"
                               placeholder="Client name, plot number, location...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="all" <?php echo e(request('status') == 'all' || !request('status') ? 'selected' : ''); ?>>All Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active Only</option>
                            <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expired Only</option>
                            <option value="terminated" <?php echo e(request('status') == 'terminated' ? 'selected' : ''); ?>>Terminated</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="duration" class="form-label">Duration</label>
                        <select name="duration" id="duration" class="form-control">
                            <option value="">All Durations</option>
                            <option value="1-5" <?php echo e(request('duration') == '1-5' ? 'selected' : ''); ?>>1-5 Years</option>
                            <option value="6-10" <?php echo e(request('duration') == '6-10' ? 'selected' : ''); ?>>6-10 Years</option>
                            <option value="11+" <?php echo e(request('duration') == '11+' ? 'selected' : ''); ?>>11+ Years</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <?php if($allocations->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover" id="allocationsTable">
                    <thead>
                        <tr>
                            <th>Allocation Details</th>
                            <th>Client</th>
                            <th>Land Details</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Expiry Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $expiryDate = $allocation->allocation_date->addYears($allocation->duration_years);
                            $isExpired = $expiryDate->isPast();
                            $daysUntilExpiry = $expiryDate->isFuture() ? $expiryDate->diffInDays(now()) : 0;
                            $isExpiringSoon = !$isExpired && $daysUntilExpiry <= 180; // 6 months
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="allocation-avatar me-3">
                                        <div class="avatar-circle bg-primary text-white">
                                            <?php echo e(substr($allocation->client->full_name, 0, 1)); ?>

                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">
                                            <?php echo e(\Carbon\Carbon::parse($allocation->allocation_date)->format('M j, Y')); ?>

                                        </div>
                                        <small class="text-muted">Allocation Date</small>
                                        <?php if($allocation->purpose): ?>
                                        <br>
                                        <small class="text-muted"><?php echo e(Str::limit($allocation->purpose, 30)); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo e($allocation->client->full_name); ?></div>
                                <small class="text-muted"><?php echo e($allocation->client->id_number); ?></small>
                                <?php if($allocation->client->phone): ?>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i><?php echo e($allocation->client->phone); ?>

                                </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    Plot <?php echo e($allocation->land->plot_number); ?>

                                </div>
                                <small class="text-muted"><?php echo e(Str::limit($allocation->land->location, 30)); ?></small>
                                <?php if($allocation->land->size): ?>
                                <br>
                                <small class="text-muted"><?php echo e($allocation->land->size); ?> acres</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="fw-bold text-dark"><?php echo e($allocation->duration_years); ?></div>
                                    <small class="text-muted">Years</small>
                                </div>
                                <?php if($allocation->rent_amount): ?>
                                <div class="text-center mt-1">
                                    <small class="text-success">
                                        <?php echo e(number_format($allocation->rent_amount)); ?> / <?php echo e($allocation->payment_frequency); ?>

                                    </small>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($isExpired): ?>
                                    <span class="badge badge-danger">Expired</span>
                                <?php elseif($allocation->status == 'terminated'): ?>
                                    <span class="badge badge-secondary">Terminated</span>
                                <?php elseif($allocation->status == 'inactive'): ?>
                                    <span class="badge badge-warning">Inactive</span>
                                <?php elseif($isExpiringSoon): ?>
                                    <span class="badge badge-warning">Expiring Soon</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Active</span>
                                <?php endif; ?>
                                
                                <?php if($isExpiringSoon && !$isExpired): ?>
                                <br>
                                <small class="text-warning mt-1">
                                    <?php echo e($daysUntilExpiry); ?> days left
                                </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-dark"><?php echo e($expiryDate->format('M j, Y')); ?></div>
                                <small class="text-muted">
                                    <?php if($isExpired): ?>
                                    <span class="text-danger">Expired <?php echo e($expiryDate->diffForHumans()); ?></span>
                                    <?php else: ?>
                                    <?php echo e($expiryDate->diffForHumans()); ?>

                                    <?php endif; ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('chief.allocations.show', $allocation)); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('chief.allocations.edit', $allocation)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit Allocation">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo e(route('chief.allocations.generateAllocationLetter', $allocation)); ?>" class="btn btn-sm btn-outline-info" title="Generate Letter">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    <?php if(!$isExpired && $allocation->status == 'active'): ?>
                                    <a href="<?php echo e(route('chief.clients.show', $allocation->client)); ?>" class="btn btn-sm btn-outline-success" title="View Client">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('chief.allocations.delete', $allocation)); ?>" class="btn btn-sm btn-outline-danger" title="Delete Allocation">
                                        <i class="fas fa-trash"></i>
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
                <div class="text-muted">
                    Showing <?php echo e($allocations->firstItem()); ?> to <?php echo e($allocations->lastItem()); ?> of <?php echo e($allocations->total()); ?> entries
                </div>
                <?php echo e($allocations->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No allocation records found</h5>
                <p class="text-muted">
                    <?php if(request('search') || request('status') || request('duration')): ?>
                        No allocations match your search criteria.
                    <?php else: ?>
                        Get started by creating your first land allocation.
                    <?php endif; ?>
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php echo e(route('chief.allocations.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Allocation
                    </a>
                    <?php if(request('search') || request('status') || request('duration')): ?>
                    <a href="<?php echo e(route('chief.allocations.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <?php endif; ?>
                </div>
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
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid #667eea;
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
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #343a40;
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
        color: #667eea;
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stat-card:nth-child(4) .stat-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: #6c757d;
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: #dc3545;
    }
    
    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .badge-secondary {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
    }
    
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .header-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    /* Allocation avatar styles */
    .allocation-avatar .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }
    
    /* Custom styling for chief-specific elements */
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white;
    }
    
    .card-header h5 {
        color: white;
        margin-bottom: 0;
    }
    
    .stat-card {
        border-left-color: #667eea;
    }
    
    /* Table row hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
    }
    
    /* Status indicator styles */
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    
    .status-active { background-color: #10b981; }
    .status-expired { background-color: #dc3545; }
    .status-warning { background-color: #f59e0b; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#allocationsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [[5, 'asc']], // Default sort by expiry date
            language: {
                emptyTable: "No allocation records found"
            },
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting for actions column
            ]
        });

        // Auto-submit form when filters change
        $('#status, #duration').change(function() {
            if($(this).val()) {
                $(this).closest('form').submit();
            }
        });

        // Quick status update
        function updateAllocationStatus(allocationId, status) {
            if(confirm('Are you sure you want to update the allocation status?')) {
                // This would typically be an AJAX call to update the status
                console.log(`Updating allocation ${allocationId} to status: ${status}`);
                // Implement AJAX call here
            }
        }
    });

    // Enhanced delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('a[href*="delete"]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const clientName = this.closest('tr').querySelector('.fw-semibold').textContent;
                const plotNumber = this.closest('tr').querySelectorAll('.fw-semibold')[1].textContent;
                const href = this.getAttribute('href');
                
                if(confirm(`Are you sure you want to delete the allocation for "${clientName}" on "${plotNumber}"? This will make the land available for reallocation.`)) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/allocations/index.blade.php ENDPATH**/ ?>