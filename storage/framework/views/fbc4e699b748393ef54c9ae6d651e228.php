

<?php $__env->startSection('title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">User Management</h4>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form id="bulk-action-form" action="<?php echo e(route('admin.users.bulk-actions')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="input-group">
                                    <select name="action" class="form-select" required>
                                        <option value="">Bulk Actions</option>
                                        <option value="activate">Activate</option>
                                        <option value="deactivate">Deactivate</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-primary">Apply</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form action="<?php echo e(route('admin.users.index')); ?>" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo e(request('search')); ?>">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="users[]" value="<?php echo e($user->id); ?>" class="user-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-primary rounded-circle text-white">
                                                        <?php echo e(substr($user->name, 0, 1)); ?>

                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($user->name); ?></h6>
                                                    <small class="text-muted"><?php echo e($user->user_type); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                                        <td>
                                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-secondary me-1"><?php echo e($role->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php if($user->is_active): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never'); ?>

                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($user->is_active): ?>
                                                    <form action="<?php echo e(route('admin.users.deactivate', $user)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-secondary" title="Deactivate">
                                                            <i class="fas fa-toggle-off"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form action="<?php echo e(route('admin.users.activate', $user)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                                            <i class="fas fa-toggle-on"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-2x mb-3"></i>
                                                <p>No users found</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($users->hasPages()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing <?php echo e($users->firstItem()); ?> to <?php echo e($users->lastItem()); ?> of <?php echo e($users->total()); ?> entries
                            </div>
                            <?php echo e($users->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        const selectAll = document.getElementById('select-all');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        
        selectAll.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Bulk action form submission
        const bulkForm = document.getElementById('bulk-action-form');
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one user.');
                return false;
            }
            
            const action = this.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action.');
                return false;
            }
            
            if (action === 'delete' && !confirm('Are you sure you want to delete the selected users?')) {
                e.preventDefault();
                return false;
            }
            
            // Add selected users to form
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'users[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/admin/users/index.blade.php ENDPATH**/ ?>