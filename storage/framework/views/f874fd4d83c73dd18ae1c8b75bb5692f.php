

<?php $__env->startSection('title', 'Edit Land - ' . $land->plot_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Land - <?php echo e($land->plot_number); ?>

                    </h5>
                    <a href="<?php echo e(route('chief.lands.show', $land)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Details
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('chief.lands.update', $land)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plot_number" class="form-label">Plot Number *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['plot_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="plot_number" name="plot_number" 
                                           value="<?php echo e(old('plot_number', $land->plot_number)); ?>" required>
                                    <?php $__errorArgs = ['plot_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="location" name="location" 
                                           value="<?php echo e(old('location', $land->location)); ?>" required>
                                    <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="area_acres" class="form-label">Area (Acres) *</label>
                                    <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['area_acres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="area_acres" name="area_acres" 
                                           value="<?php echo e(old('area_acres', $land->area_acres)); ?>" required>
                                    <?php $__errorArgs = ['area_acres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="area_hectares" class="form-label">Area (Hectares) *</label>
                                    <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['area_hectares'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="area_hectares" name="area_hectares" 
                                           value="<?php echo e(old('area_hectares', $land->area_hectares)); ?>" required>
                                    <?php $__errorArgs = ['area_hectares'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="land_use" class="form-label">Land Use *</label>
                                    <select class="form-control <?php $__errorArgs = ['land_use'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="land_use" name="land_use" required>
                                        <option value="">Select Land Use</option>
                                        <option value="residential" <?php echo e(old('land_use', $land->land_use) == 'residential' ? 'selected' : ''); ?>>Residential</option>
                                        <option value="commercial" <?php echo e(old('land_use', $land->land_use) == 'commercial' ? 'selected' : ''); ?>>Commercial</option>
                                        <option value="agricultural" <?php echo e(old('land_use', $land->land_use) == 'agricultural' ? 'selected' : ''); ?>>Agricultural</option>
                                        <option value="industrial" <?php echo e(old('land_use', $land->land_use) == 'industrial' ? 'selected' : ''); ?>>Industrial</option>
                                        <option value="recreational" <?php echo e(old('land_use', $land->land_use) == 'recreational' ? 'selected' : ''); ?>>Recreational</option>
                                    </select>
                                    <?php $__errorArgs = ['land_use'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₵)</label>
                                    <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="price" name="price" 
                                           value="<?php echo e(old('price', $land->price)); ?>">
                                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="landmark" class="form-label">Landmark</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['landmark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="landmark" name="landmark" 
                                   value="<?php echo e(old('landmark', $land->landmark)); ?>">
                            <?php $__errorArgs = ['landmark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" name="description" rows="3"><?php echo e(old('description', $land->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="coordinates" class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['coordinates'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="coordinates" name="coordinates" 
                                   value="<?php echo e(old('coordinates', $land->coordinates)); ?>" 
                                   placeholder="e.g., 5.6037, -0.1870">
                            <?php $__errorArgs = ['coordinates'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Current Status Display (Read-only) -->
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <div>
                                <?php
                                    $statusClass = match($land->ownership_status) {
                                        'vacant' => 'badge-success',
                                        'allocated' => 'badge-primary',
                                        'under_dispute' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo e($statusClass); ?> fs-6">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $land->ownership_status))); ?>

                                </span>
                                <small class="text-muted ms-2">
                                    <?php if($land->ownership_status === 'allocated'): ?>
                                        (Cannot change status while land is allocated)
                                    <?php elseif($land->ownership_status === 'under_dispute'): ?>
                                        (Cannot change status while land is under dispute)
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="<?php echo e(route('chief.lands.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <a href="<?php echo e(route('chief.lands.show', $land)); ?>" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Land
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with Land Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Land Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Plot Number:</strong>
                        <div class="text-muted"><?php echo e($land->plot_number); ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Current Location:</strong>
                        <div class="text-muted"><?php echo e($land->location); ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Area:</strong>
                        <div class="text-muted">
                            <?php echo e(number_format($land->area_acres, 2)); ?> acres
                            (<?php echo e(number_format($land->area_hectares, 2)); ?> ha)
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Land Use:</strong>
                        <div class="text-muted text-capitalize"><?php echo e(str_replace('_', ' ', $land->land_use)); ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <div class="text-muted"><?php echo e($land->created_at->format('M j, Y g:i A')); ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div class="text-muted"><?php echo e($land->updated_at->format('M j, Y g:i A')); ?></div>
                    </div>

                    <?php if($land->allocations()->exists()): ?>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> This land has existing allocations. 
                        Some changes may affect existing allocation records.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if($land->ownership_status === 'vacant'): ?>
                        <a href="<?php echo e(route('chief.allocations.create')); ?>?land_id=<?php echo e($land->id); ?>" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-handshake me-2"></i>Allocate This Land
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('chief.lands.documents', $land)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Manage Documents
                        </a>

                        <?php if($land->ownership_status === 'allocated'): ?>
                        <button type="button" class="btn btn-warning btn-sm" disabled>
                            <i class="fas fa-lock me-2"></i>Land is Allocated
                        </button>
                        <?php endif; ?>
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
        // Auto-calculate hectares when acres changes
        const acresInput = document.getElementById('area_acres');
        const hectaresInput = document.getElementById('area_hectares');
        
        if (acresInput && hectaresInput) {
            acresInput.addEventListener('input', function() {
                const acres = parseFloat(this.value) || 0;
                const hectares = acres * 0.404686;
                hectaresInput.value = hectares.toFixed(2);
            });
            
            hectaresInput.addEventListener('input', function() {
                const hectares = parseFloat(this.value) || 0;
                const acres = hectares / 0.404686;
                acresInput.value = acres.toFixed(2);
            });
        }

        // Form submission confirmation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const plotNumber = document.getElementById('plot_number').value;
            if (!confirm(`Are you sure you want to update land "${plotNumber}"?`)) {
                e.preventDefault();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/lands/edit.blade.php ENDPATH**/ ?>