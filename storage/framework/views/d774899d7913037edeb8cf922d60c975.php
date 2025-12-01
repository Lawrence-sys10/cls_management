

<?php $__env->startSection('title', 'System Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <nav class="mb-4">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-general-tab" data-bs-toggle="tab" data-bs-target="#nav-general" type="button" role="tab" aria-controls="nav-general" aria-selected="true">
                        General Settings
                    </button>
                    <button class="nav-link" id="nav-system-tab" data-bs-toggle="tab" data-bs-target="#nav-system" type="button" role="tab" aria-controls="nav-system" aria-selected="false">
                        System Settings
                    </button>
                    <button class="nav-link" id="nav-backup-tab" data-bs-toggle="tab" data-bs-target="#nav-backup" type="button" role="tab" aria-controls="nav-backup" aria-selected="false">
                        Backup & Restore
                    </button>
                </div>
            </nav>
            
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-general" role="tabpanel" aria-labelledby="nav-general-tab">
                    <?php echo $__env->make('admin.settings.partials.general', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="tab-pane fade" id="nav-system" role="tabpanel" aria-labelledby="nav-system-tab">
                    <?php echo $__env->make('admin.settings.partials.system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="tab-pane fade" id="nav-backup" role="tabpanel" aria-labelledby="nav-backup-tab">
                    <?php echo $__env->make('admin.settings.partials.backup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>