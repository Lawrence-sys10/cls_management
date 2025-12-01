

<?php $__env->startSection('title', 'Delete Land - ' . $land->plot_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Land
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-circle me-2"></i>Warning: This action cannot be undone!
                        </h6>
                        <p class="mb-0">You are about to permanently delete this land record and all associated data.</p>
                    </div>

                    <!-- Land Details -->
                    <div class="land-details mb-4">
                        <h6 class="text-muted mb-3">Land to be deleted:</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Plot Number:</strong>
                                        <div class="text-dark"><?php echo e($land->plot_number); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Location:</strong>
                                        <div class="text-dark"><?php echo e($land->location); ?></div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Area:</strong>
                                        <div class="text-dark">
                                            <?php echo e(number_format($land->area_acres, 2)); ?> acres
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <div class="text-dark text-capitalize">
                                            <?php echo e(str_replace('_', ' ', $land->ownership_status)); ?>

                                        </div>
                                    </div>
                                </div>
                                <?php if($land->description): ?>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <strong>Description:</strong>
                                        <div class="text-dark"><?php echo e($land->description); ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Dependencies Check -->
                    <?php if($land->allocations()->exists() || $land->documents()->exists()): ?>
                    <div class="dependencies-check mb-4">
                        <h6 class="text-muted mb-3">Associated Data:</h6>
                        <div class="card border-warning">
                            <div class="card-body">
                                <?php if($land->allocations()->exists()): ?>
                                <div class="alert alert-warning mb-3">
                                    <i class="fas fa-handshake me-2"></i>
                                    <strong>Allocations Found:</strong> 
                                    This land has <?php echo e($land->allocations()->count()); ?> allocation record(s).
                                    <br>
                                    <small class="text-muted">
                                        You must delete all allocations before you can delete this land.
                                    </small>
                                </div>
                                <?php endif; ?>

                                <?php if($land->documents()->exists()): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-file-alt me-2"></i>
                                    <strong>Documents Found:</strong> 
                                    This land has <?php echo e($land->documents()->count()); ?> document(s) attached.
                                    <br>
                                    <small class="text-muted">
                                        All documents will be permanently deleted along with the land record.
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?php echo e(route('chief.lands.show', $land)); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Details
                            </a>
                            <a href="<?php echo e(route('chief.lands.index')); ?>" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-list me-2"></i>All Lands
                            </a>
                        </div>
                        
                        <div>
                            <?php if($land->allocations()->exists()): ?>
                                <!-- Disabled delete button if allocations exist -->
                                <button type="button" class="btn btn-danger" disabled 
                                        data-bs-toggle="tooltip" 
                                        title="Cannot delete land with existing allocations">
                                    <i class="fas fa-trash me-2"></i>Delete Land
                                </button>
                                <div class="text-danger small mt-1">
                                    Remove allocations first
                                </div>
                            <?php else: ?>
                                <!-- Delete confirmation form -->
                                <form action="<?php echo e(route('chief.lands.destroy', $land)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn btn-danger" 
                                            onclick="confirmDelete(this)">
                                        <i class="fas fa-trash me-2"></i>Delete Land
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Additional Warning for Allocated Lands -->
                    <?php if($land->ownership_status === 'allocated'): ?>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Status Restriction:</strong> 
                        This land is currently allocated and cannot be deleted. 
                        You must first change the land status to vacant.
                    </div>
                    <?php endif; ?>

                    <!-- Alternative Actions -->
                    <?php if($land->allocations()->exists() || $land->ownership_status === 'allocated'): ?>
                    <div class="alternative-actions mt-4">
                        <h6 class="text-muted mb-3">Alternative Actions:</h6>
                        <div class="d-grid gap-2">
                            <?php if($land->allocations()->exists()): ?>
                            <a href="<?php echo e(route('chief.allocations.index')); ?>?land_id=<?php echo e($land->id); ?>" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-handshake me-2"></i>View Allocations for This Land
                            </a>
                            <?php endif; ?>
                            
                            <?php if($land->ownership_status === 'allocated'): ?>
                            <a href="<?php echo e(route('chief.lands.edit', $land)); ?>" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-edit me-2"></i>Edit Land Details Instead
                            </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('chief.lands.documents', $land)); ?>" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-file-alt me-2"></i>Manage Documents
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .card.border-danger {
        border-width: 2px;
    }
    
    .land-details .card {
        border-left: 4px solid #dc3545;
    }
    
    .dependencies-check .card {
        border-left: 4px solid #ffc107;
    }
    
    .btn:disabled {
        cursor: not-allowed;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function confirmDelete(button) {
        const landPlotNumber = "<?php echo e($land->plot_number); ?>";
        const landLocation = "<?php echo e($land->location); ?>";
        
        Swal.fire({
            title: 'Are you sure?',
            html: `
                <div class="text-start">
                    <p>You are about to permanently delete:</p>
                    <div class="alert alert-danger">
                        <strong>${landPlotNumber}</strong><br>
                        <small>${landLocation}</small>
                    </div>
                    <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                button.closest('form').submit();
            }
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/lands/delete.blade.php ENDPATH**/ ?>