

<?php $__env->startSection('title', 'User Details - ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">User Details</h4>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- User Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Full Name:</strong></td>
                                                    <td><?php echo e($user->name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td><?php echo e($user->email); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone:</strong></td>
                                                    <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>User Type:</strong></td>
                                                    <td>
                                                        <span class="badge bg-primary"><?php echo e(ucfirst($user->user_type)); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>
                                                        <?php if($user->is_active): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Last Login:</strong></td>
                                                    <td>
                                                        <?php echo e($user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never'); ?>

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Roles & Permissions -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">Roles & Permissions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Assigned Roles:</strong>
                                        <div class="mt-2">
                                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-secondary me-2 mb-2 p-2">
                                                    <?php echo e($role->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($user->roles->isEmpty()): ?>
                                                <span class="text-muted">No roles assigned</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Staff Information (if applicable) -->
                            <?php if($user->staff): ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title">Staff Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Department:</strong></td>
                                                        <td><?php echo e($user->staff->department); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Employee ID:</strong></td>
                                                        <td><?php echo e($user->staff->employee_id); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Date Joined:</strong></td>
                                                        <td><?php echo e($user->staff->date_joined->format('M j, Y')); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Assigned Area:</strong></td>
                                                        <td><?php echo e($user->staff->assigned_area ?? 'N/A'); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sidebar Actions -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit User
                                        </a>
                                        
                                        <?php if($user->is_active): ?>
                                            <form action="<?php echo e(route('admin.users.deactivate', $user)); ?>" method="POST" class="d-grid">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-secondary">
                                                    <i class="fas fa-toggle-off me-2"></i>Deactivate
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('admin.users.activate', $user)); ?>" method="POST" class="d-grid">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-toggle-on me-2"></i>Activate
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <!-- Password Reset Form -->
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                            <i class="fas fa-key me-2"></i>Reset Password
                                        </button>

                                        <!-- Impersonate Button (Admins only) -->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                                            <form action="<?php echo e(route('admin.users.impersonate', $user)); ?>" method="POST" class="d-grid">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-primary" 
                                                        onclick="return confirm('Are you sure you want to impersonate this user?')">
                                                    <i class="fas fa-user-secret me-2"></i>Impersonate
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-grid">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                <i class="fas fa-trash me-2"></i>Delete User
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- User Stats -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">User Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-user-circle fa-3x text-primary"></i>
                                        </div>
                                        <p class="mb-1"><strong>Member Since:</strong></p>
                                        <p class="text-muted"><?php echo e($user->created_at->format('M j, Y')); ?></p>
                                        <p class="mb-1"><strong>Last Updated:</strong></p>
                                        <p class="text-muted"><?php echo e($user->updated_at->format('M j, Y')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password for <?php echo e($user->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.users.reset-password', $user)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/admin/users/show.blade.php ENDPATH**/ ?>