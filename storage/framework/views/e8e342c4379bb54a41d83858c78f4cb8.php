<?php $__env->startSection('title', isset($land) && $land->exists ? 'Edit Land' : 'Add New Land'); ?>
<?php $__env->startSection('subtitle', isset($land) && $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <?php echo e(isset($land) && $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land'); ?>

                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(isset($land) && $land->exists ? route('lands.update', $land) : route('lands.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($land) && $land->exists): ?>
                            <?php echo method_field('PUT'); ?>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Plot Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="plot_number" class="form-label">Plot Number <span class="text-danger">*</span></label>
                                        <input type="text" name="plot_number" id="plot_number" 
                                               value="<?php echo e(old('plot_number', $land->plot_number ?? '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['plot_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter plot number" required>
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

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" name="location" id="location" 
                                               value="<?php echo e(old('location', $land->location ?? '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter location" required>
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="area_acres" class="form-label">Area (Acres) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="area_acres" id="area_acres" 
                                                       value="<?php echo e(old('area_acres', $land->area_acres ?? '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['area_acres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="0.00" min="0.01" required>
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
                                                <label for="area_hectares" class="form-label">Area (Hectares) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="area_hectares" id="area_hectares" 
                                                       value="<?php echo e(old('area_hectares', $land->area_hectares ?? '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['area_hectares'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="0.00" min="0.01" required>
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

                                    <div class="mb-3">
                                        <label for="chief_id" class="form-label">Chief <span class="text-danger">*</span></label>
                                        <select name="chief_id" id="chief_id" class="form-select <?php $__errorArgs = ['chief_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select Chief...</option>
                                            <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($chief->id); ?>" <?php echo e(old('chief_id', $land->chief_id ?? '') == $chief->id ? 'selected' : ''); ?>>
                                                <?php echo e($chief->name); ?> - <?php echo e($chief->jurisdiction); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['chief_id'];
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

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Land Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="ownership_status" class="form-label">Ownership Status <span class="text-danger">*</span></label>
                                        <select name="ownership_status" id="ownership_status" class="form-select <?php $__errorArgs = ['ownership_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="vacant" <?php echo e(old('ownership_status', $land->ownership_status ?? '') == 'vacant' ? 'selected' : ''); ?>>Vacant</option>
                                            <option value="allocated" <?php echo e(old('ownership_status', $land->ownership_status ?? '') == 'allocated' ? 'selected' : ''); ?>>Allocated</option>
                                            <option value="under_dispute" <?php echo e(old('ownership_status', $land->ownership_status ?? '') == 'under_dispute' ? 'selected' : ''); ?>>Under Dispute</option>
                                            <option value="reserved" <?php echo e(old('ownership_status', $land->ownership_status ?? '') == 'reserved' ? 'selected' : ''); ?>>Reserved</option>
                                        </select>
                                        <?php $__errorArgs = ['ownership_status'];
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
                                        <label for="land_use" class="form-label">Land Use <span class="text-danger">*</span></label>
                                        <select name="land_use" id="land_use" class="form-select <?php $__errorArgs = ['land_use'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="residential" <?php echo e(old('land_use', $land->land_use ?? '') == 'residential' ? 'selected' : ''); ?>>Residential</option>
                                            <option value="commercial" <?php echo e(old('land_use', $land->land_use ?? '') == 'commercial' ? 'selected' : ''); ?>>Commercial</option>
                                            <option value="agricultural" <?php echo e(old('land_use', $land->land_use ?? '') == 'agricultural' ? 'selected' : ''); ?>>Agricultural</option>
                                            <option value="industrial" <?php echo e(old('land_use', $land->land_use ?? '') == 'industrial' ? 'selected' : ''); ?>>Industrial</option>
                                            <option value="mixed" <?php echo e(old('land_use', $land->land_use ?? '') == 'mixed' ? 'selected' : ''); ?>>Mixed</option>
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="latitude" class="form-label">Latitude</label>
                                                <input type="number" step="0.00000001" name="latitude" id="latitude" 
                                                       value="<?php echo e(old('latitude', $land->latitude ?? '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="0.000000">
                                                <?php $__errorArgs = ['latitude'];
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
                                                <label for="longitude" class="form-label">Longitude</label>
                                                <input type="number" step="0.00000001" name="longitude" id="longitude" 
                                                       value="<?php echo e(old('longitude', $land->longitude ?? '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="0.000000">
                                                <?php $__errorArgs = ['longitude'];
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
                                        <label for="price" class="form-label">Price (GHS)</label>
                                        <input type="number" step="0.01" name="price" id="price" 
                                               value="<?php echo e(old('price', $land->price ?? '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="0.00" min="0">
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
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="boundary_description" class="form-label">Boundary Description</label>
                                        <textarea name="boundary_description" id="boundary_description" rows="3" 
                                                  class="form-control <?php $__errorArgs = ['boundary_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                  placeholder="Describe the land boundaries..."><?php echo e(old('boundary_description', $land->boundary_description ?? '')); ?></textarea>
                                        <?php $__errorArgs = ['boundary_description'];
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
                                        <label for="registration_date" class="form-label">Registration Date <span class="text-danger">*</span></label>
                                        <input type="date" name="registration_date" id="registration_date" 
                                               value="<?php echo e(old('registration_date', isset($land) && $land->registration_date ? $land->registration_date->format('Y-m-d') : now()->format('Y-m-d'))); ?>" 
                                               class="form-control <?php $__errorArgs = ['registration_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <?php $__errorArgs = ['registration_date'];
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
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="<?php echo e(route('lands.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Lands
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                <?php echo e(isset($land) && $land->exists ? 'Update Land' : 'Create Land'); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('registration_date').min = today;
        
        // Auto-convert between acres and hectares
        const acresInput = document.getElementById('area_acres');
        const hectaresInput = document.getElementById('area_hectares');
        
        function convertAcresToHectares(acres) {
            return acres * 0.404686;
        }
        
        function convertHectaresToAcres(hectares) {
            return hectares * 2.47105;
        }
        
        if (acresInput && hectaresInput) {
            acresInput.addEventListener('input', function() {
                const acres = parseFloat(this.value) || 0;
                if (acres > 0) {
                    hectaresInput.value = convertAcresToHectares(acres).toFixed(2);
                }
            });
            
            hectaresInput.addEventListener('input', function() {
                const hectares = parseFloat(this.value) || 0;
                if (hectares > 0) {
                    acresInput.value = convertHectaresToAcres(hectares).toFixed(2);
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const plotNumber = document.getElementById('plot_number').value;
            const location = document.getElementById('location').value;
            const areaAcres = document.getElementById('area_acres').value;
            const areaHectares = document.getElementById('area_hectares').value;
            const chiefId = document.getElementById('chief_id').value;
            
            if (!plotNumber || !location || !areaAcres || !areaHectares || !chiefId) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/lands/create.blade.php ENDPATH**/ ?>