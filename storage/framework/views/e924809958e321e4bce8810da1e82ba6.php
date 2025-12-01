<div class="card">
    <div class="card-header">
        <h5 class="card-title">General Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.settings.update.general')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo e(old('site_name', 'Land Allocation System')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="site_email" class="form-label">Site Email</label>
                        <input type="email" class="form-control" id="site_email" name="site_email" value="<?php echo e(old('site_email', 'admin@landallocation.com')); ?>">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save General Settings</button>
        </form>
    </div>
</div><?php /**PATH C:\Users\pprhl\cls_management\resources\views/admin/settings/partials/general.blade.php ENDPATH**/ ?>