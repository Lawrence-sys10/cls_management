

<?php $__env->startSection('title', 'Create New Allocation'); ?>
<?php $__env->startSection('subtitle', 'Allocate land to a client'); ?>

<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('chief.allocations.index')); ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Allocations
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>Create New Land Allocation
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('chief.allocations.store')); ?>" method="POST" id="allocationForm">
                        <?php echo csrf_field(); ?>

                        <!-- Client Selection Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Select Client
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Select Client *</label>
                                    <select class="form-control select2-client" 
                                            id="client_id" 
                                            name="client_id"
                                            data-placeholder="Search for a client by name, ID number, or phone..."
                                            data-ajax-url="<?php echo e(route('chief.clients.search')); ?>"
                                            required>
                                        <?php if($selectedClient ?? false): ?>
                                            <option value="<?php echo e($selectedClient->id); ?>" selected>
                                                <?php echo e($selectedClient->full_name); ?> - <?php echo e($selectedClient->id_number); ?> - <?php echo e($selectedClient->phone); ?>

                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted">
                                        Start typing to search for existing clients
                                    </small>
                                </div>

                                <!-- Quick Client Creation -->
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="create_new_client">
                                        <label class="form-check-label" for="create_new_client">
                                            Create new client
                                        </label>
                                    </div>
                                    
                                    <div id="new_client_fields" class="mt-3" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="new_client_name" class="form-label">Full Name *</label>
                                                    <input type="text" class="form-control" id="new_client_name" name="new_client_name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="new_client_id_number" class="form-label">ID Number *</label>
                                                    <input type="text" class="form-control" id="new_client_id_number" name="new_client_id_number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="new_client_phone" class="form-label">Phone *</label>
                                                    <input type="text" class="form-control" id="new_client_phone" name="new_client_phone">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="new_client_email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="new_client_email" name="new_client_email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Land Selection Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-map me-2"></i>Select Land
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="land_id" class="form-label">Select Available Land *</label>
                                    <select class="form-control select2-land" 
                                            id="land_id" 
                                            name="land_id"
                                            data-placeholder="Search for available lands by plot number, location, or size..."
                                            data-ajax-url="<?php echo e(route('chief.lands.search')); ?>"
                                            required>
                                        <?php if($selectedLand ?? false): ?>
                                            <option value="<?php echo e($selectedLand->id); ?>" selected>
                                                Plot <?php echo e($selectedLand->plot_number); ?> - <?php echo e($selectedLand->location); ?> - <?php echo e($selectedLand->size); ?> acres
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted">
                                        Search for available lands in your jurisdiction
                                    </small>
                                </div>

                                <!-- Selected Land Preview -->
                                <div id="land_preview" class="mt-3 p-3 border rounded bg-light" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div id="land_preview_info">
                                            <!-- Land preview will appear here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Lands List -->
                                <div class="mt-3">
                                    <label class="form-label">Quick Select from Available Lands</label>
                                    <div class="row" id="available_lands_list">
                                        <?php
                                            // Safely get lands with fallback
                                            $lands = $lands ?? collect();
                                        ?>
                                        <?php if($lands->count() > 0): ?>
                                            <?php $__currentLoopData = $lands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $land): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-6 mb-2">
                                                    <div class="card land-card <?php echo e(($selectedLand ?? false) && $selectedLand->id == $land->id ? 'border-primary selected' : ''); ?>" 
                                                         data-land-id="<?php echo e($land->id); ?>"
                                                         style="cursor: pointer;">
                                                        <div class="card-body py-2">
                                                            <h6 class="card-title mb-1">Plot <?php echo e($land->plot_number); ?></h6>
                                                            <p class="card-text text-muted mb-1 small"><?php echo e(Str::limit($land->location, 40)); ?></p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted"><?php echo e($land->size); ?> acres</small>
                                                                <span class="badge bg-success">Available</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    No available lands found. 
                                                    <a href="<?php echo e(route('chief.lands.create')); ?>" class="alert-link">Create new lands</a>.
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Allocation Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-file-contract me-2"></i>Allocation Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="allocation_date" class="form-label">Allocation Date *</label>
                                            <input type="date" 
                                                   class="form-control <?php $__errorArgs = ['allocation_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="allocation_date" 
                                                   name="allocation_date" 
                                                   value="<?php echo e(old('allocation_date', now()->format('Y-m-d'))); ?>"
                                                   required>
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration_years" class="form-label">Duration (Years) *</label>
                                            <select class="form-control <?php $__errorArgs = ['duration_years'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    id="duration_years" 
                                                    name="duration_years"
                                                    required>
                                                <option value="">Select duration</option>
                                                <?php for($i = 1; $i <= 20; $i++): ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e(old('duration_years') == $i ? 'selected' : ''); ?>>
                                                        <?php echo e($i); ?> year<?php echo e($i > 1 ? 's' : ''); ?>

                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                            <?php $__errorArgs = ['duration_years'];
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
                                    <label for="purpose" class="form-label">Purpose of Allocation *</label>
                                    <textarea class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="purpose" 
                                              name="purpose" 
                                              rows="3" 
                                              placeholder="Describe the purpose of this land allocation..."
                                              required><?php echo e(old('purpose')); ?></textarea>
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rent_amount" class="form-label">Rent Amount (Optional)</label>
                                            <input type="number" 
                                                   class="form-control <?php $__errorArgs = ['rent_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="rent_amount" 
                                                   name="rent_amount" 
                                                   value="<?php echo e(old('rent_amount')); ?>"
                                                   step="0.01"
                                                   placeholder="0.00">
                                            <?php $__errorArgs = ['rent_amount'];
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
                                            <label for="payment_frequency" class="form-label">Payment Frequency</label>
                                            <select class="form-control <?php $__errorArgs = ['payment_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    id="payment_frequency" 
                                                    name="payment_frequency">
                                                <option value="">Select frequency</option>
                                                <option value="monthly" <?php echo e(old('payment_frequency') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                                <option value="quarterly" <?php echo e(old('payment_frequency') == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                                                <option value="yearly" <?php echo e(old('payment_frequency') == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                                            </select>
                                            <?php $__errorArgs = ['payment_frequency'];
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
                                    <label for="terms" class="form-label">Additional Terms & Conditions (Optional)</label>
                                    <textarea class="form-control <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="terms" 
                                              name="terms" 
                                              rows="3" 
                                              placeholder="Any additional terms and conditions for this allocation..."><?php echo e(old('terms')); ?></textarea>
                                    <?php $__errorArgs = ['terms'];
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

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('chief.allocations.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
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
        border: 1px solid #ced4da;
    }
    
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    
    .land-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .land-card.selected {
        border: 2px solid #667eea;
        background-color: rgba(102, 126, 234, 0.05);
    }
    
    #new_client_fields {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        background-color: #f8f9fa;
    }
    
    .land-preview-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .land-preview-item:last-child {
        border-bottom: none;
    }
    
    .debug-info {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-top: 1rem;
        font-family: monospace;
        font-size: 0.875rem;
    }
    
    .alert-warning {
        border-left: 4px solid #ffc107;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for Clients
        $('.select2-client').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            ajax: {
                url: $('.select2-client').data('ajax-url'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    if (data && data.success && data.clients) {
                        return {
                            results: $.map(data.clients, function (client) {
                                return {
                                    id: client.id,
                                    text: client.full_name + ' - ' + client.id_number + ' - ' + client.phone,
                                    client: client
                                }
                            })
                        };
                    } else {
                        // Show helpful message if no results
                        if (data && !data.success) {
                            return {
                                results: [{
                                    id: null,
                                    text: 'No clients found. Try creating a new client below.',
                                    disabled: true
                                }]
                            };
                        }
                        return { results: [] };
                    }
                },
                cache: true,
                error: function(xhr, status, error) {
                    // Show user-friendly error
                    return {
                        results: [{
                            id: null,
                            text: 'Search error. Please try again.',
                            disabled: true
                        }]
                    };
                }
            },
            placeholder: 'Search for a client...',
            minimumInputLength: 1,
            templateResult: formatClient,
            templateSelection: formatClientSelection
        });

        // Initialize Select2 for Lands
        $('.select2-land').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            ajax: {
                url: $('.select2-land').data('ajax-url'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    if (data && data.success && data.lands) {
                        return {
                            results: $.map(data.lands, function (land) {
                                return {
                                    id: land.id,
                                    text: 'Plot ' + land.plot_number + ' - ' + land.location + ' - ' + land.size + ' acres',
                                    land: land
                                }
                            })
                        };
                    } else {
                        // Show helpful message if no results
                        if (data && !data.success) {
                            return {
                                results: [{
                                    id: null,
                                    text: 'No available lands found. Create lands first.',
                                    disabled: true
                                }]
                            };
                        }
                        return { results: [] };
                    }
                },
                cache: true,
                error: function(xhr, status, error) {
                    // Show user-friendly error
                    return {
                        results: [{
                            id: null,
                            text: 'Search error. Please try again.',
                            disabled: true
                        }]
                    };
                }
            },
            placeholder: 'Search for available lands...',
            minimumInputLength: 1,
            templateResult: formatLand,
            templateSelection: formatLandSelection
        });

        // Format client display in dropdown
        function formatClient(client) {
            if (!client.id) {
                return client.text;
            }
            
            // Handle disabled options (error messages)
            if (client.disabled) {
                return $(
                    '<div class="text-muted"><i class="fas fa-exclamation-circle me-1"></i>' + client.text + '</div>'
                );
            }
            
            var $container = $(
                '<div class="client-option">' +
                    '<strong>' + (client.client ? client.client.full_name : client.text) + '</strong>' +
                    '<div class="text-muted small">' +
                        'ID: ' + (client.client ? client.client.id_number : 'N/A') + ' | Phone: ' + (client.client ? client.client.phone : 'N/A') +
                    '</div>' +
                '</div>'
            );
            return $container;
        }

        // Format client selection
        function formatClientSelection(client) {
            if (client.disabled) {
                return client.text;
            }
            return client.client ? client.client.full_name : client.text;
        }

        // Format land display in dropdown
        function formatLand(land) {
            if (!land.id) {
                return land.text;
            }
            
            // Handle disabled options (error messages)
            if (land.disabled) {
                return $(
                    '<div class="text-muted"><i class="fas fa-exclamation-circle me-1"></i>' + land.text + '</div>'
                );
            }
            
            var $container = $(
                '<div class="land-option">' +
                    '<strong>Plot ' + (land.land ? land.land.plot_number : 'N/A') + '</strong>' +
                    '<div class="text-muted small">' +
                        (land.land ? land.land.location : 'N/A') + ' | ' + (land.land ? land.land.size + ' acres' : 'N/A') +
                    '</div>' +
                '</div>'
            );
            return $container;
        }

        // Format land selection
        function formatLandSelection(land) {
            if (land.disabled) {
                return land.text;
            }
            return land.land ? 'Plot ' + land.land.plot_number + ' - ' + land.land.location : land.text;
        }

        // Handle land selection from Select2
        $('.select2-land').on('select2:select', function (e) {
            // Don't process disabled items
            if (e.params.data.disabled) return;
            
            const land = e.params.data.land;
            updateLandPreview(land);
            updateLandCardSelection(land.id);
        });

        // Handle land clearing from Select2
        $('.select2-land').on('select2:clear', function (e) {
            $('#land_preview').hide();
            $('.land-card').removeClass('selected border-primary');
        });

        // Update land preview
        function updateLandPreview(land) {
            if (land) {
                $('#land_preview_info').html(`
                    <h6 class="mb-1">Plot ${land.plot_number}</h6>
                    <p class="mb-1 text-muted">${land.location}</p>
                    <small class="text-muted">
                        Size: ${land.size} acres | Status: ${land.ownership_status || 'Available'}
                    </small>
                `);
                $('#land_preview').show();
            }
        }

        // Update land card selection
        function updateLandCardSelection(landId) {
            $('.land-card').removeClass('selected border-primary');
            $(`.land-card[data-land-id="${landId}"]`).addClass('selected border-primary');
        }

        // Handle land card clicks
        $('.land-card').on('click', function() {
            const landId = $(this).data('land-id');
            const landText = $(this).find('.card-title').text() + ' - ' + 
                           $(this).find('.card-text').text() + ' - ' + 
                           $(this).find('small:first').text();
            
            // Create option and select it
            const option = new Option(landText, landId, true, true);
            $('.select2-land').append(option).trigger('change');
            
            // Update preview
            const landData = {
                plot_number: $(this).find('.card-title').text().replace('Plot ', ''),
                location: $(this).find('.card-text').text().trim(),
                size: $(this).find('small:first').text().replace(' acres', ''),
                ownership_status: 'Available'
            };
            updateLandPreview(landData);
        });

        // Toggle New Client Fields
        $('#create_new_client').on('change', function(e) {
            const newClientFields = $('#new_client_fields');
            if (e.target.checked) {
                newClientFields.show();
                $('.select2-client').val(null).trigger('change');
            } else {
                newClientFields.hide();
            }
        });

        // Form Validation
        $('#allocationForm').on('submit', function(e) {
            const clientId = $('#client_id').val();
            const landId = $('#land_id').val();
            const createNewClient = $('#create_new_client').is(':checked');
            
            if (!clientId && !createNewClient) {
                e.preventDefault();
                alert('Please select a client or create a new one.');
                return;
            }
            
            if (!landId) {
                e.preventDefault();
                alert('Please select a land to allocate.');
                return;
            }
            
            // Validate new client fields if creating new client
            if (createNewClient) {
                const newClientName = $('#new_client_name').val();
                const newClientIdNumber = $('#new_client_id_number').val();
                const newClientPhone = $('#new_client_phone').val();
                
                if (!newClientName || !newClientIdNumber || !newClientPhone) {
                    e.preventDefault();
                    alert('Please fill in all required fields for the new client.');
                    return;
                }
            }
            
            // Show loading state
            const submitBtn = $('#submitBtn');
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        });

        // Initialize land preview if land is pre-selected
        <?php if($selectedLand ?? false): ?>
            const selectedLand = {
                plot_number: '<?php echo e($selectedLand->plot_number); ?>',
                location: '<?php echo e($selectedLand->location); ?>',
                size: '<?php echo e($selectedLand->size); ?>',
                ownership_status: '<?php echo e($selectedLand->ownership_status ?? "Available"); ?>'
            };
            updateLandPreview(selectedLand);
        <?php endif; ?>
    });

    // Additional helper functions
    function clearClientSelection() {
        $('.select2-client').val(null).trigger('change');
    }

    function clearLandSelection() {
        $('.select2-land').val(null).trigger('change');
        $('#land_preview').hide();
        $('.land-card').removeClass('selected border-primary');
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/chiefs/allocations/create.blade.php ENDPATH**/ ?>