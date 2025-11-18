<?php $__env->startSection('title', 'Land Plot: ' . $land->plot_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Land Plot: <?php echo e($land->plot_number); ?></h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="<?php echo e(route('lands.edit', $land)); ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?php echo e(route('lands.documents', $land)); ?>" class="btn btn-info">
                    <i class="fas fa-file"></i> Documents
                </a>
                <?php if(!$land->is_verified): ?>
                    <form action="<?php echo e(route('lands.verify', $land)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </form>
                <?php endif; ?>
                <a href="<?php echo e(route('lands.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Land Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Plot Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Plot Number:</th>
                            <td>
                                <strong><?php echo e($land->plot_number); ?></strong>
                                <?php if($land->is_verified): ?>
                                    <span class="badge bg-success ms-2">Verified</span>
                                <?php else: ?>
                                    <span class="badge bg-warning ms-2">Unverified</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td><?php echo e($land->location); ?></td>
                        </tr>
                        <tr>
                            <th>Chief:</th>
                            <td><?php echo e($land->chief->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Area:</th>
                            <td><?php echo e(number_format($land->area_acres, 2)); ?> acres (<?php echo e(number_format($land->area_hectares, 2)); ?> hectares)</td>
                        </tr>
                        <tr>
                            <th>Ownership Status:</th>
                            <td>
                                <span class="badge bg-<?php echo e($land->ownership_status == 'available' ? 'success' : ($land->ownership_status == 'allocated' ? 'primary' : 'warning')); ?>">
                                    <?php echo e(ucfirst($land->ownership_status)); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Land Use:</th>
                            <td><?php echo e(ucfirst($land->land_use ?? 'N/A')); ?></td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>GHS <?php echo e(number_format($land->price, 2)); ?></td>
                        </tr>
                        <?php if($land->latitude && $land->longitude): ?>
                        <tr>
                            <th>Coordinates:</th>
                            <td><?php echo e($land->latitude); ?>, <?php echo e($land->longitude); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Soil Type:</th>
                            <td><?php echo e($land->soil_type ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Topography:</th>
                            <td><?php echo e($land->topography ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Boundary Description:</th>
                            <td><?php echo e($land->boundary_description ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td><?php echo e($land->description ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Registration Date:</th>
                            <td><?php echo e($land->registration_date ? $land->registration_date->format('M d, Y') : 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Allocations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Allocations (<?php echo e($land->allocations->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if($land->allocations->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $land->allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($allocation->client->full_name ?? 'N/A'); ?></td>
                                            <td><?php echo e($allocation->allocation_date->format('M d, Y')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger')); ?>">
                                                    <?php echo e(ucfirst($allocation->approval_status)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e($allocation->payment_status == 'paid' ? 'success' : ($allocation->payment_status == 'partial' ? 'warning' : 'secondary')); ?>">
                                                    <?php echo e(ucfirst($allocation->payment_status)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No allocations for this land plot.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documents Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Documents (<?php echo e($land->documents->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if($land->documents->count() > 0): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $land->documents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($document->document_type); ?></h6>
                                            <small class="text-muted"><?php echo e($document->file_name); ?></small>
                                        </div>
                                        <a href="<?php echo e(Storage::url($document->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($land->documents->count() > 3): ?>
                            <div class="text-center mt-2">
                                <a href="<?php echo e(route('lands.documents', $land)); ?>" class="btn btn-sm btn-outline-primary">
                                    View All Documents
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">No documents uploaded.</p>
                        <a href="<?php echo e(route('lands.documents', $land)); ?>" class="btn btn-sm btn-primary">
                            Upload Documents
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Map Preview -->
            <?php if($land->latitude && $land->longitude): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Location Map</h5>
                </div>
                <div class="card-body">
                    <div id="map-preview" style="height: 200px; width: 100%;"></div>
                    <div class="text-center mt-2">
                        <small class="text-muted">Coordinates: <?php echo e($land->latitude); ?>, <?php echo e($land->longitude); ?></small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php if($land->latitude && $land->longitude): ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-preview { 
        height: 200px; 
        border-radius: 0.375rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map-preview').setView([<?php echo e($land->latitude); ?>, <?php echo e($land->longitude); ?>], 15);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker for the land plot
    L.marker([<?php echo e($land->latitude); ?>, <?php echo e($land->longitude); ?>])
        .addTo(map)
        .bindPopup('<strong><?php echo e($land->plot_number); ?></strong><br><?php echo e($land->location); ?>')
        .openPopup();
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/lands/show.blade.php ENDPATH**/ ?>