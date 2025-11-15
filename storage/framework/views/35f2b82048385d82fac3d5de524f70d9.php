

<?php $__env->startSection('title', 'Add New Chief'); ?>
<?php $__env->startSection('subtitle', 'Add New Chief'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Add New Chief
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('chiefs.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Personal Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="full_name" 
                                               value="<?php echo e(old('full_name')); ?>" 
                                               class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter chief's full name" required>
                                        <?php $__errorArgs = ['full_name'];
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
                                        <label for="title" class="form-label">Title/Rank <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" 
                                               value="<?php echo e(old('title')); ?>" 
                                               class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="e.g., Nana, Oba, Togbe, Chief" required>
                                        <?php $__errorArgs = ['title'];
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
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" 
                                               value="<?php echo e(old('phone')); ?>" 
                                               class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter phone number" required>
                                        <?php $__errorArgs = ['phone'];
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
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" id="email" 
                                               value="<?php echo e(old('email')); ?>" 
                                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter email address">
                                        <?php $__errorArgs = ['email'];
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
                                    <h5 class="text-primary mb-3">Chief Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="traditional_area" class="form-label">Traditional Area <span class="text-danger">*</span></label>
                                        <input type="text" name="traditional_area" id="traditional_area" 
                                               value="<?php echo e(old('traditional_area')); ?>" 
                                               class="form-control <?php $__errorArgs = ['traditional_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="e.g., Ashanti, Ga, Dagbon, Ewe" required>
                                        <?php $__errorArgs = ['traditional_area'];
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
                                        <label for="community" class="form-label">Community/Town <span class="text-danger">*</span></label>
                                        <input type="text" name="community" id="community" 
                                               value="<?php echo e(old('community')); ?>" 
                                               class="form-control <?php $__errorArgs = ['community'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter community or town name" required>
                                        <?php $__errorArgs = ['community'];
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
                                        <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                        <select name="region" id="region" class="form-select <?php $__errorArgs = ['region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select Region</option>
                                            <option value="Greater Accra" <?php echo e(old('region') == 'Greater Accra' ? 'selected' : ''); ?>>Greater Accra</option>
                                            <option value="Ashanti" <?php echo e(old('region') == 'Ashanti' ? 'selected' : ''); ?>>Ashanti</option>
                                            <option value="Western" <?php echo e(old('region') == 'Western' ? 'selected' : ''); ?>>Western</option>
                                            <option value="Western North" <?php echo e(old('region') == 'Western North' ? 'selected' : ''); ?>>Western North</option>
                                            <option value="Eastern" <?php echo e(old('region') == 'Eastern' ? 'selected' : ''); ?>>Eastern</option>
                                            <option value="Central" <?php echo e(old('region') == 'Central' ? 'selected' : ''); ?>>Central</option>
                                            <option value="Volta" <?php echo e(old('region') == 'Volta' ? 'selected' : ''); ?>>Volta</option>
                                            <option value="Oti" <?php echo e(old('region') == 'Oti' ? 'selected' : ''); ?>>Oti</option>
                                            <option value="Bono" <?php echo e(old('region') == 'Bono' ? 'selected' : ''); ?>>Bono</option>
                                            <option value="Bono East" <?php echo e(old('region') == 'Bono East' ? 'selected' : ''); ?>>Bono East</option>
                                            <option value="Ahafo" <?php echo e(old('region') == 'Ahafo' ? 'selected' : ''); ?>>Ahafo</option>
                                            <option value="Northern" <?php echo e(old('region') == 'Northern' ? 'selected' : ''); ?>>Northern</option>
                                            <option value="North East" <?php echo e(old('region') == 'North East' ? 'selected' : ''); ?>>North East</option>
                                            <option value="Savannah" <?php echo e(old('region') == 'Savannah' ? 'selected' : ''); ?>>Savannah</option>
                                            <option value="Upper East" <?php echo e(old('region') == 'Upper East' ? 'selected' : ''); ?>>Upper East</option>
                                            <option value="Upper West" <?php echo e(old('region') == 'Upper West' ? 'selected' : ''); ?>>Upper West</option>
                                        </select>
                                        <?php $__errorArgs = ['region'];
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
                                        <label for="rank_level" class="form-label">Rank Level</label>
                                        <select name="rank_level" id="rank_level" class="form-select <?php $__errorArgs = ['rank_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="">Select Rank Level</option>
                                            <option value="paramount" <?php echo e(old('rank_level') == 'paramount' ? 'selected' : ''); ?>>Paramount Chief</option>
                                            <option value="divisional" <?php echo e(old('rank_level') == 'divisional' ? 'selected' : ''); ?>>Divisional Chief</option>
                                            <option value="sub-chief" <?php echo e(old('rank_level') == 'sub-chief' ? 'selected' : ''); ?>>Sub-Chief</option>
                                            <option value="queen_mother" <?php echo e(old('rank_level') == 'queen_mother' ? 'selected' : ''); ?>>Queen Mother</option>
                                            <option value="elder" <?php echo e(old('rank_level') == 'elder' ? 'selected' : ''); ?>>Elder</option>
                                            <option value="other" <?php echo e(old('rank_level') == 'other' ? 'selected' : ''); ?>>Other</option>
                                        </select>
                                        <?php $__errorArgs = ['rank_level'];
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
                            <h5 class="text-primary mb-3">Address Information</h5>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" rows="3" 
                                          class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          placeholder="Enter chief's palace address or residence..." required><?php echo e(old('address')); ?></textarea>
                                <?php $__errorArgs = ['address'];
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
                                        <label for="city" class="form-label">City/Town</label>
                                        <input type="text" name="city" id="city" 
                                               value="<?php echo e(old('city')); ?>" 
                                               class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter city or town">
                                        <?php $__errorArgs = ['city'];
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
                                        <label for="landmarks" class="form-label">Landmarks</label>
                                        <input type="text" name="landmarks" id="landmarks" 
                                               value="<?php echo e(old('landmarks')); ?>" 
                                               class="form-control <?php $__errorArgs = ['landmarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter nearby landmarks">
                                        <?php $__errorArgs = ['landmarks'];
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

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="mb-3">
                                <label for="years_of_service" class="form-label">Years of Service</label>
                                <input type="number" name="years_of_service" id="years_of_service" 
                                       value="<?php echo e(old('years_of_service')); ?>" 
                                       class="form-control <?php $__errorArgs = ['years_of_service'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       placeholder="Enter number of years as chief" min="0" max="100">
                                <?php $__errorArgs = ['years_of_service'];
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
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          placeholder="Enter any additional notes about the chief..."><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
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

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">
                                    Active Chief
                                </label>
                            </div>
                            <small class="text-muted">Uncheck to mark chief as inactive</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="<?php echo e(route('chiefs.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Chiefs
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Create Chief
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
        // Phone number formatting (similar to your client form)
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    value = '+233' + value.substring(1);
                }
                if (value.startsWith('233')) {
                    value = '+' + value;
                }
                e.target.value = value;
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value;
            const title = document.getElementById('title').value;
            const phone = document.getElementById('phone').value;
            const traditionalArea = document.getElementById('traditional_area').value;
            const community = document.getElementById('community').value;
            const region = document.getElementById('region').value;
            const address = document.getElementById('address').value;
            
            if (!fullName || !title || !phone || !traditionalArea || !community || !region || !address) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
            
            // Validate phone number format
            const phoneRegex = /^\+233[0-9]{9}$/;
            if (phone && !phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid Ghanaian phone number (e.g., +233XXXXXXXXX)');
                return false;
            }
        });

        // Auto-fill city based on community
        const communityInput = document.getElementById('community');
        const cityInput = document.getElementById('city');
        
        if (communityInput && cityInput) {
            communityInput.addEventListener('blur', function() {
                if (communityInput.value && !cityInput.value) {
                    cityInput.value = communityInput.value;
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/create.blade.php ENDPATH**/ ?>