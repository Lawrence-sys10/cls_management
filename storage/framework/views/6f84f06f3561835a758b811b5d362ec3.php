<?php $__env->startSection('title', isset($client) && $client->exists ? 'Edit Client' : 'Add New Client'); ?>
<?php $__env->startSection('subtitle', isset($client) && $client->exists ? 'Edit Client: ' . $client->full_name : 'Add New Client'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <?php echo e(isset($client) && $client->exists ? 'Edit Client: ' . $client->full_name : 'Add New Client'); ?>

                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(isset($client) && $client->exists ? route('clients.update', $client) : route('clients.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($client) && $client->exists): ?>
                            <?php echo method_field('PUT'); ?>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Personal Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="full_name" 
                                               value="<?php echo e(old('full_name', $client->full_name ?? '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter full name" required>
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
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" 
                                               value="<?php echo e(old('phone', $client->phone ?? '')); ?>" 
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
                                               value="<?php echo e(old('email', $client->email ?? '')); ?>" 
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="id_type" class="form-label">ID Type <span class="text-danger">*</span></label>
                                                <select name="id_type" id="id_type" class="form-select <?php $__errorArgs = ['id_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                                    <option value="ghanacard" <?php echo e(old('id_type', $client->id_type ?? '') == 'ghanacard' ? 'selected' : ''); ?>>Ghana Card</option>
                                                    <option value="passport" <?php echo e(old('id_type', $client->id_type ?? '') == 'passport' ? 'selected' : ''); ?>>Passport</option>
                                                    <option value="drivers_license" <?php echo e(old('id_type', $client->id_type ?? '') == 'drivers_license' ? 'selected' : ''); ?>>Driver's License</option>
                                                    <option value="voters_id" <?php echo e(old('id_type', $client->id_type ?? '') == 'voters_id' ? 'selected' : ''); ?>>Voter's ID</option>
                                                </select>
                                                <?php $__errorArgs = ['id_type'];
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
                                                <label for="id_number" class="form-label">ID Number <span class="text-danger">*</span></label>
                                                <input type="text" name="id_number" id="id_number" 
                                                       value="<?php echo e(old('id_number', $client->id_number ?? '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['id_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="Enter ID number" required>
                                                <?php $__errorArgs = ['id_number'];
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
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Additional Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="occupation" class="form-label">Occupation <span class="text-danger">*</span></label>
                                        <input type="text" name="occupation" id="occupation" 
                                               value="<?php echo e(old('occupation', $client->occupation ?? '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['occupation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter occupation" required>
                                        <?php $__errorArgs = ['occupation'];
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
                                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                <input type="date" name="date_of_birth" id="date_of_birth" 
                                                       value="<?php echo e(old('date_of_birth', isset($client) && $client->date_of_birth ? $client->date_of_birth->format('Y-m-d') : '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php $__errorArgs = ['date_of_birth'];
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
                                                <label for="gender" class="form-label">Gender</label>
                                                <select name="gender" id="gender" class="form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" <?php echo e(old('gender', $client->gender ?? '') == 'male' ? 'selected' : ''); ?>>Male</option>
                                                    <option value="female" <?php echo e(old('gender', $client->gender ?? '') == 'female' ? 'selected' : ''); ?>>Female</option>
                                                    <option value="other" <?php echo e(old('gender', $client->gender ?? '') == 'other' ? 'selected' : ''); ?>>Other</option>
                                                </select>
                                                <?php $__errorArgs = ['gender'];
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
                                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" id="emergency_contact" 
                                               value="<?php echo e(old('emergency_contact', $client->emergency_contact ?? '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter emergency contact">
                                        <?php $__errorArgs = ['emergency_contact'];
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
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <input type="text" name="nationality" id="nationality" 
                                               value="<?php echo e(old('nationality', $client->nationality ?? 'Ghanaian')); ?>" 
                                               class="form-control <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               placeholder="Enter nationality">
                                        <?php $__errorArgs = ['nationality'];
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
                                          placeholder="Enter full address..." required><?php echo e(old('address', $client->address ?? '')); ?></textarea>
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
                                               value="<?php echo e(old('city', $client->city ?? '')); ?>" 
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
                                        <label for="region" class="form-label">Region</label>
                                        <select name="region" id="region" class="form-select <?php $__errorArgs = ['region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="">Select Region</option>
                                            <option value="Greater Accra" <?php echo e(old('region', $client->region ?? '') == 'Greater Accra' ? 'selected' : ''); ?>>Greater Accra</option>
                                            <option value="Ashanti" <?php echo e(old('region', $client->region ?? '') == 'Ashanti' ? 'selected' : ''); ?>>Ashanti</option>
                                            <option value="Western" <?php echo e(old('region', $client->region ?? '') == 'Western' ? 'selected' : ''); ?>>Western</option>
                                            <option value="Eastern" <?php echo e(old('region', $client->region ?? '') == 'Eastern' ? 'selected' : ''); ?>>Eastern</option>
                                            <option value="Central" <?php echo e(old('region', $client->region ?? '') == 'Central' ? 'selected' : ''); ?>>Central</option>
                                            <option value="Volta" <?php echo e(old('region', $client->region ?? '') == 'Volta' ? 'selected' : ''); ?>>Volta</option>
                                            <option value="Brong Ahafo" <?php echo e(old('region', $client->region ?? '') == 'Brong Ahafo' ? 'selected' : ''); ?>>Brong Ahafo</option>
                                            <option value="Northern" <?php echo e(old('region', $client->region ?? '') == 'Northern' ? 'selected' : ''); ?>>Northern</option>
                                            <option value="Upper East" <?php echo e(old('region', $client->region ?? '') == 'Upper East' ? 'selected' : ''); ?>>Upper East</option>
                                            <option value="Upper West" <?php echo e(old('region', $client->region ?? '') == 'Upper West' ? 'selected' : ''); ?>>Upper West</option>
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
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', isset($client) ? $client->is_active : true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">
                                    Active Client
                                </label>
                            </div>
                            <small class="text-muted">Uncheck to mark client as inactive</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Clients
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                <?php echo e(isset($client) && $client->exists ? 'Update Client' : 'Create Client'); ?>

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
        // Set maximum date for date of birth (18 years ago)
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        document.getElementById('date_of_birth').max = maxDate.toISOString().split('T')[0];
        
        // Phone number formatting
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
            const phone = document.getElementById('phone').value;
            const idType = document.getElementById('id_type').value;
            const idNumber = document.getElementById('id_number').value;
            const occupation = document.getElementById('occupation').value;
            const address = document.getElementById('address').value;
            
            if (!fullName || !phone || !idType || !idNumber || !occupation || !address) {
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
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/clients/create.blade.php ENDPATH**/ ?>