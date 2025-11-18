

<?php $__env->startSection('title', 'Edit Allocation: #' . $allocation->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Edit Allocation #<?php echo e($allocation->id); ?>

                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('allocations.update', $allocation)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Allocation Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="land_id" class="form-label">Land Plot <span class="text-danger">*</span></label>
                                        <select name="land_id" id="land_id" class="form-select select2 <?php $__errorArgs = ['land_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select Land Plot...</option>
                                            <?php $__currentLoopData = $lands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $land): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($land->id); ?>" <?php echo e(old('land_id', $allocation->land_id) == $land->id ? 'selected' : ''); ?>>
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

                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-select select2 <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select Client...</option>
                                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id', $allocation->client_id) == $client->id ? 'selected' : ''); ?>>
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

                                    <div class="mb-3">
                                        <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                                        <input type="date" name="allocation_date" id="allocation_date" 
                                               value="<?php echo e(old('allocation_date', $allocation->allocation_date->format('Y-m-d'))); ?>" 
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

                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose</label>
                                        <textarea name="purpose" id="purpose" rows="3" 
                                                  class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                  placeholder="Describe the purpose of this allocation..."><?php echo e(old('purpose', $allocation->purpose)); ?></textarea>
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
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Approval & Payment</h5>
                                    
                                    <div class="mb-3">
                                        <label for="chief_id" class="form-label">Chief <span class="text-danger">*</span></label>
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
                                            <option value="<?php echo e($chief->id); ?>" <?php echo e(old('chief_id', $allocation->chief_id) == $chief->id ? 'selected' : ''); ?>>
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
                                        <label for="approval_status" class="form-label">Approval Status <span class="text-danger">*</span></label>
                                        <select name="approval_status" id="approval_status" class="form-select <?php $__errorArgs = ['approval_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="pending" <?php echo e(old('approval_status', $allocation->approval_status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                            <option value="approved" <?php echo e(old('approval_status', $allocation->approval_status) == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                            <option value="rejected" <?php echo e(old('approval_status', $allocation->approval_status) == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                        </select>
                                        <?php $__errorArgs = ['approval_status'];
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
                                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select name="payment_status" id="payment_status" class="form-select <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="unpaid" <?php echo e(old('payment_status', $allocation->payment_status) == 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                                            <option value="partial" <?php echo e(old('payment_status', $allocation->payment_status) == 'partial' ? 'selected' : ''); ?>>Partial</option>
                                            <option value="paid" <?php echo e(old('payment_status', $allocation->payment_status) == 'paid' ? 'selected' : ''); ?>>Paid</option>
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="payment_amount" class="form-label">Payment Amount (GHS)</label>
                                                <input type="number" step="0.01" name="payment_amount" id="payment_amount" 
                                                       value="<?php echo e(old('payment_amount', $allocation->payment_amount)); ?>" 
                                                       class="form-control <?php $__errorArgs = ['payment_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       placeholder="0.00" min="0">
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="payment_date" class="form-label">Payment Date</label>
                                                <input type="date" name="payment_date" id="payment_date" 
                                                       value="<?php echo e(old('payment_date', $allocation->payment_date ? $allocation->payment_date->format('Y-m-d') : '')); ?>" 
                                                       class="form-control <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php $__errorArgs = ['payment_date'];
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
                                        <label for="processed_by" class="form-label">Processed By</label>
                                        <select name="processed_by" id="processed_by" class="form-select select2 <?php $__errorArgs = ['processed_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="">Select Staff...</option>
                                            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staffMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($staffMember->id); ?>" <?php echo e(old('processed_by', $allocation->processed_by) == $staffMember->id ? 'selected' : ''); ?>>
                                                <?php echo e($staffMember->user->name ?? 'N/A'); ?> - <?php echo e($staffMember->department); ?>

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

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="chief_approval_date" class="form-label">Chief Approval Date</label>
                                        <input type="date" name="chief_approval_date" id="chief_approval_date" 
                                               value="<?php echo e(old('chief_approval_date', $allocation->chief_approval_date ? $allocation->chief_approval_date->format('Y-m-d') : '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['chief_approval_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['chief_approval_date'];
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
                                        <label for="registrar_approval_date" class="form-label">Registrar Approval Date</label>
                                        <input type="date" name="registrar_approval_date" id="registrar_approval_date" 
                                               value="<?php echo e(old('registrar_approval_date', $allocation->registrar_approval_date ? $allocation->registrar_approval_date->format('Y-m-d') : '')); ?>" 
                                               class="form-control <?php $__errorArgs = ['registrar_approval_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['registrar_approval_date'];
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
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          placeholder="Additional notes or comments..."><?php echo e(old('notes', $allocation->notes)); ?></textarea>
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

                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_finalized" id="is_finalized" value="1" 
                                       class="form-check-input <?php $__errorArgs = ['is_finalized'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       <?php echo e(old('is_finalized', $allocation->is_finalized) ? 'checked' : ''); ?>>
                                <label for="is_finalized" class="form-check-label">
                                    Mark as Finalized
                                </label>
                                <?php $__errorArgs = ['is_finalized'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">
                                    Once finalized, this allocation cannot be modified.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="<?php echo e(route('allocations.show', $allocation)); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Allocation
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Allocation
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
        // Initialize Select2 for dropdowns with select2 class
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option...';
            },
            allowClear: true,
            width: '100%'
        });

        // Set maximum date to today for all date fields
        const today = new Date().toISOString().split('T')[0];
        const dateFields = ['allocation_date', 'payment_date', 'chief_approval_date', 'registrar_approval_date'];
        
        dateFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.max = today;
            }
        });

        // Auto-fill chief approval date when status is set to approved
        const approvalStatus = document.getElementById('approval_status');
        const chiefApprovalDate = document.getElementById('chief_approval_date');
        
        if (approvalStatus && chiefApprovalDate) {
            approvalStatus.addEventListener('change', function() {
                if (this.value === 'approved' && !chiefApprovalDate.value) {
                    chiefApprovalDate.value = today;
                }
            });
        }

        // Auto-fill payment date when payment status is set to paid
        const paymentStatus = document.getElementById('payment_status');
        const paymentDate = document.getElementById('payment_date');
        
        if (paymentStatus && paymentDate) {
            paymentStatus.addEventListener('change', function() {
                if (this.value === 'paid' && !paymentDate.value) {
                    paymentDate.value = today;
                }
            });
        }

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const landId = document.getElementById('land_id').value;
            const clientId = document.getElementById('client_id').value;
            const allocationDate = document.getElementById('allocation_date').value;
            const chiefId = document.getElementById('chief_id').value;
            const approvalStatus = document.getElementById('approval_status').value;
            const paymentStatus = document.getElementById('payment_status').value;
            
            if (!landId || !clientId || !allocationDate || !chiefId || !approvalStatus || !paymentStatus) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
        });

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/allocations/edit.blade.php ENDPATH**/ ?>