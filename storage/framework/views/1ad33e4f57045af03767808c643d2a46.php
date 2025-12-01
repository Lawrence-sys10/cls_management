<div class="card">
    <div class="card-header">
        <h5 class="card-title">System Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.settings.update.system')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Maintenance Mode</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                    <label class="form-check-label" for="maintenance_mode">Enable Maintenance Mode</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save System Settings</button>
        </form>
    </div>
</div><?php /**PATH C:\Users\pprhl\cls_management\resources\views/admin/settings/partials/system.blade.php ENDPATH**/ ?>