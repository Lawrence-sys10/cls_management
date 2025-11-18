<?php $__env->startSection('title', 'Create New Allocation'); ?>
<?php $__env->startSection('subtitle', 'Create a new land allocation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Create New Allocation</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('allocations.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Land Selection</h5>
                                    
                                    <div class="mb-3">
                                        <label for="land_id" class="form-label">Select Land Plot <span class="text-danger">*</span></label>
                                        <select name="land_id" id="land_id" class="form-select select2 <?php $__errorArgs = ['land_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Choose Land Plot...</option>
                                            <?php $__currentLoopData = $lands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $land): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($land->id); ?>" <?php echo e(old('land_id', request('land_id')) == $land->id ? 'selected' : ''); ?>>
                                                <?php echo e($land->plot_number); ?> - <?php echo e($land->location); ?> (<?php echo e(number_format($land->area_acres, 2)); ?> acres)
                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['land_id'];
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

                                    <div id="land-details" class="card bg-light border-0 p-3" style="display: none;">
                                        <h6 class="card-title text-dark">Land Details</h6>
                                        <div class="row text-sm text-muted">
                                            <div class="col-6">
                                                <strong>Chief:</strong> <span id="land-chief">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Price:</strong> GHS <span id="land-price">0.00</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Land Use:</strong> <span id="land-use">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Status:</strong> <span id="land-status">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Client Selection</h5>
                                    
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Select Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-select select2 <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Choose Client...</option>
                                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id', request('client_id')) == $client->id ? 'selected' : ''); ?>>
                                                <?php echo e($client->full_name); ?> - <?php echo e($client->phone); ?> (<?php echo e($client->id_number); ?>)
                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['client_id'];
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

                                    <div id="client-details" class="card bg-light border-0 p-3" style="display: none;">
                                        <h6 class="card-title text-dark">Client Details</h6>
                                        <div class="row text-sm text-muted">
                                            <div class="col-6">
                                                <strong>Occupation:</strong> <span id="client-occupation">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>ID Type:</strong> <span id="client-id-type">-</span>
                                            </div>
                                            <div class="col-12">
                                                <strong>Address:</strong> <span id="client-address">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Email:</strong> <span id="client-email">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Allocation Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="chief_id" class="form-label">Approving Chief <span class="text-danger">*</span></label>
                                        <select name="chief_id" id="chief_id" class="form-select select2 <?php $__errorArgs = ['chief_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select Chief...</option>
                                            <?php $__currentLoopData = $chiefs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chief): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($chief->id); ?>" <?php echo e(old('chief_id') == $chief->id ? 'selected' : ''); ?>>
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

                                    <div class="mb-3">
                                        <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                                        <input type="date" name="allocation_date" id="allocation_date" 
                                               value="<?php echo e(old('allocation_date', now()->format('Y-m-d'))); ?>" 
                                               class="form-control <?php $__errorArgs = ['allocation_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <?php $__errorArgs = ['allocation_date'];
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
                                    <h5 class="text-primary mb-3">Payment Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select name="payment_status" id="payment_status" class="form-select select2 <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="pending" <?php echo e(old('payment_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                            <option value="partial" <?php echo e(old('payment_status') == 'partial' ? 'selected' : ''); ?>>Partial Payment</option>
                                            <option value="paid" <?php echo e(old('payment_status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                            <option value="overdue" <?php echo e(old('payment_status') == 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                                        </select>
                                        <?php $__errorArgs = ['payment_status'];
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
                                        <label for="payment_amount" class="form-label">Payment Amount (GHS)</label>
                                        <input type="number" step="0.01" name="payment_amount" id="payment_amount" 
                                               value="<?php echo e(old('payment_amount')); ?>" 
                                               class="form-control <?php $__errorArgs = ['payment_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['payment_amount'];
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
                                        <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                        <select name="processed_by" id="processed_by" class="form-select select2 <?php $__errorArgs = ['processed_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select Staff...</option>
                                            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staffMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($staffMember->id); ?>" <?php echo e(old('processed_by') == $staffMember->id ? 'selected' : ''); ?>>
                                                <?php echo e($staffMember->user->name); ?> - <?php echo e($staffMember->department); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['processed_by'];
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
                                <label for="purpose" class="form-label">Purpose of Allocation</label>
                                <textarea name="purpose" id="purpose" rows="3" 
                                          class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          placeholder="Describe the purpose of this land allocation..."><?php echo e(old('purpose')); ?></textarea>
                                <?php $__errorArgs = ['purpose'];
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
                                          placeholder="Any additional notes or comments..."><?php echo e(old('notes')); ?></textarea>
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

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="<?php echo e(route('allocations.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Allocations
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 4px 12px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all dropdowns
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option...';
            },
            allowClear: true,
            width: '100%'
        });

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('allocation_date').min = today;
        
        // Land selection details
        const landSelect = document.getElementById('land_id');
        const landDetails = document.getElementById('land-details');
        
        if (landSelect) {
            // Initial trigger for pre-selected values
            if (landSelect.value) {
                updateLandDetails(landSelect.value);
            }

            // Listen for Select2 change events
            $(landSelect).on('change', function() {
                const landId = this.value;
                updateLandDetails(landId);
            });
        }

        // Client selection details
        const clientSelect = document.getElementById('client_id');
        const clientDetails = document.getElementById('client-details');
        
        if (clientSelect) {
            // Initial trigger for pre-selected values
            if (clientSelect.value) {
                updateClientDetails(clientSelect.value);
            }

            // Listen for Select2 change events
            $(clientSelect).on('change', function() {
                const clientId = this.value;
                updateClientDetails(clientId);
            });
        }

        function updateLandDetails(landId) {
            if (landId) {
                // Show loading state
                document.getElementById('land-chief').textContent = 'Loading...';
                document.getElementById('land-price').textContent = '0.00';
                document.getElementById('land-use').textContent = 'Loading...';
                document.getElementById('land-status').textContent = 'Loading...';
                
                // In a real application, you would fetch land details via AJAX
                // For now, we'll simulate with sample data
                setTimeout(() => {
                    document.getElementById('land-chief').textContent = 'Chief Kwame';
                    document.getElementById('land-price').textContent = '15,000.00';
                    document.getElementById('land-use').textContent = 'Residential';
                    document.getElementById('land-status').textContent = 'Available';
                    
                    landDetails.style.display = 'block';
                }, 500);
            } else {
                landDetails.style.display = 'none';
            }
        }

        function updateClientDetails(clientId) {
            if (clientId) {
                // Show loading state
                document.getElementById('client-occupation').textContent = 'Loading...';
                document.getElementById('client-id-type').textContent = 'Loading...';
                document.getElementById('client-address').textContent = 'Loading...';
                document.getElementById('client-email').textContent = 'Loading...';
                
                // Similar to land details, you would fetch client details via AJAX
                setTimeout(() => {
                    document.getElementById('client-occupation').textContent = 'Business Owner';
                    document.getElementById('client-id-type').textContent = 'Ghana Card';
                    document.getElementById('client-address').textContent = 'Accra, Ghana';
                    document.getElementById('client-email').textContent = 'client@example.com';
                    
                    clientDetails.style.display = 'block';
                }, 500);
            } else {
                clientDetails.style.display = 'none';
            }
        }

        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const landId = document.getElementById('land_id').value;
                const clientId = document.getElementById('client_id').value;
                const chiefId = document.getElementById('chief_id').value;
                const processedBy = document.getElementById('processed_by').value;
                
                if (!landId || !clientId || !chiefId || !processedBy) {
                    e.preventDefault();
                    alert('Please fill in all required fields marked with *.');
                    return false;
                }
            });
        }

        // Ensure Select2 works properly with Bootstrap validation
        $('.select2').on('change', function() {
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/allocations/create.blade.php ENDPATH**/ ?>