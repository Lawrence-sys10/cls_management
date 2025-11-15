<!-- resources/views/partials/sidebar.blade.php -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-landmark"></i>
            </div>
            <div class="logo-text">CLS System</div>
        </div>
    </div>
    
    <div class="sidebar-nav">
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <span>Dashboard</span>
        </a>
        
        <a href="<?php echo e(route('lands.index')); ?>" class="nav-item <?php echo e(request()->routeIs('lands.*') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <span>Lands</span>
        </a>
        
        <a href="<?php echo e(route('clients.index')); ?>" class="nav-item <?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span>Clients</span>
        </a>
        
        <a href="<?php echo e(route('allocations.index')); ?>" class="nav-item <?php echo e(request()->routeIs('allocations.*') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <span>Allocations</span>
        </a>
        
        <a href="<?php echo e(route('chiefs.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chiefs.*') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-crown"></i>
            </div>
            <span>Chiefs</span>
        </a>
        
        <a href="<?php echo e(route('reports.index')); ?>" class="nav-item <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <span>Reports</span>
        </a>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>">
            <div class="nav-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <span>Admin</span>
        </a>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\pprhl\cls_management\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>