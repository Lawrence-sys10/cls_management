<?php $__env->startSection('title', $land->exists ? 'Edit Land' : 'Add New Land'); ?>
<?php $__env->startSection('header', $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="<?php echo e($land->exists ? route('lands.update', $land) : route('lands.store')); ?>">
                <?php echo csrf_field(); ?>
                <?php if($land->exists): ?>
                <?php echo method_field('PUT'); ?>
                <?php endif; ?>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Plot Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Plot Information</h3>
                        
                        <div>
                            <label for="plot_number" class="block text-sm font-medium text-gray-700">Plot Number *</label>
                            <input type="text" name="plot_number" id="plot_number" value="<?php echo e(old('plot_number', $land->plot_number)); ?>" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <?php $__errorArgs = ['plot_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                            <input type="text" name="location" id="location" value="<?php echo e(old('location', $land->location)); ?>" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="area_acres" class="block text-sm font-medium text-gray-700">Area (Acres) *</label>
                                <input type="number" step="0.01" name="area_acres" id="area_acres" value="<?php echo e(old('area_acres', $land->area_acres)); ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <?php $__errorArgs = ['area_acres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="area_hectares" class="block text-sm font-medium text-gray-700">Area (Hectares) *</label>
                                <input type="number" step="0.01" name="area_hectares" id="area_hectares" value="<?php echo e(old('area_hectares', $land->area_hectares)); ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <?php $__errorArgs = ['area_hectares'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief *</label>
                            <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Chief</option>
                                <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($chief->id); ?>" <?php echo e(old('chief_id', $land->chief_id) == $chief->id ? 'selected' : ''); ?>>
                                    <?php echo e($chief->name); ?> - <?php echo e($chief->jurisdiction); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['chief_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Land Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Land Details</h3>
                        
                        <div>
                            <label for="ownership_status" class="block text-sm font-medium text-gray-700">Ownership Status *</label>
                            <select name="ownership_status" id="ownership_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="vacant" <?php echo e(old('ownership_status', $land->ownership_status) == 'vacant' ? 'selected' : ''); ?>>Vacant</option>
                                <option value="allocated" <?php echo e(old('ownership_status', $land->ownership_status) == 'allocated' ? 'selected' : ''); ?>>Allocated</option>
                                <option value="under_dispute" <?php echo e(old('ownership_status', $land->ownership_status) == 'under_dispute' ? 'selected' : ''); ?>>Under Dispute</option>
                                <option value="reserved" <?php echo e(old('ownership_status', $land->ownership_status) == 'reserved' ? 'selected' : ''); ?>>Reserved</option>
                            </select>
                            <?php $__errorArgs = ['ownership_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="land_use" class="block text-sm font-medium text-gray-700">Land Use *</label>
                            <select name="land_use" id="land_use" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="residential" <?php echo e(old('land_use', $land->land_use) == 'residential' ? 'selected' : ''); ?>>Residential</option>
                                <option value="commercial" <?php echo e(old('land_use', $land->land_use) == 'commercial' ? 'selected' : ''); ?>>Commercial</option>
                                <option value="agricultural" <?php echo e(old('land_use', $land->land_use) == 'agricultural' ? 'selected' : ''); ?>>Agricultural</option>
                                <option value="industrial" <?php echo e(old('land_use', $land->land_use) == 'industrial' ? 'selected' : ''); ?>>Industrial</option>
                                <option value="mixed" <?php echo e(old('land_use', $land->land_use) == 'mixed' ? 'selected' : ''); ?>>Mixed</option>
                            </select>
                            <?php $__errorArgs = ['land_use'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="number" step="0.00000001" name="latitude" id="latitude" value="<?php echo e(old('latitude', $land->latitude)); ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="number" step="0.00000001" name="longitude" id="longitude" value="<?php echo e(old('longitude', $land->longitude)); ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (GHS)</label>
                            <input type="number" step="0.01" name="price" id="price" value="<?php echo e(old('price', $land->price)); ?>" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="boundary_description" class="block text-sm font-medium text-gray-700">Boundary Description</label>
                            <textarea name="boundary_description" id="boundary_description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"><?php echo e(old('boundary_description', $land->boundary_description)); ?></textarea>
                        </div>
                        <div>
                            <label for="registration_date" class="block text-sm font-medium text-gray-700">Registration Date *</label>
                            <input type="date" name="registration_date" id="registration_date" value="<?php echo e(old('registration_date', $land->registration_date?->format('Y-m-d'))); ?>" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <?php $__errorArgs = ['registration_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="<?php echo e(route('lands.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <?php echo e($land->exists ? 'Update Land' : 'Create Land'); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/lands/create.blade.php ENDPATH**/ ?>