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
        <!-- Dashboard - Different route based on role -->
        <?php if(auth()->user()->hasRole('chief')): ?>
            <a href="<?php echo e(route('chief.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('chief.dashboard') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <span>Dashboard</span>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>
        
        <!-- Lands - Different route based on role -->
        <?php if(auth()->user()->hasRole('chief')): ?>
            <a href="<?php echo e(route('chief.lands.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chief.lands.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <span>My Lands</span>
            </a>
        <?php elseif(auth()->user()->hasRole('admin|staff')): ?>
            <a href="<?php echo e(route('lands.index')); ?>" class="nav-item <?php echo e(request()->routeIs('lands.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <span>Lands</span>
            </a>
        <?php endif; ?>
        
        <!-- Clients - Different route based on role -->
        <?php if(auth()->user()->hasRole('chief')): ?>
            <a href="<?php echo e(route('chief.clients.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chief.clients.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span>My Clients</span>
            </a>
        <?php elseif(auth()->user()->hasRole('admin|staff')): ?>
            <a href="<?php echo e(route('clients.index')); ?>" class="nav-item <?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span>Clients</span>
            </a>
        <?php endif; ?>
        
        <!-- Allocations - Different route based on role -->
        <?php if(auth()->user()->hasRole('chief')): ?>
            <a href="<?php echo e(route('chief.allocations.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chief.allocations.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <span>My Allocations</span>
            </a>
        <?php elseif(auth()->user()->hasRole('admin|staff')): ?>
            <a href="<?php echo e(route('allocations.index')); ?>" class="nav-item <?php echo e(request()->routeIs('allocations.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <span>Allocations</span>
            </a>
        <?php endif; ?>
        
        <!-- Disputes - Only for Chiefs -->
        <?php if(auth()->user()->hasRole('chief')): ?>
            <a href="<?php echo e(route('chief.disputes.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chief.disputes.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span>Disputes</span>
            </a>
        <?php endif; ?>
        
        <!-- Chiefs Management - Only for Admin/Staff (not for chiefs themselves) -->
        <?php if(auth()->user()->hasRole('admin|staff')): ?>
            <a href="<?php echo e(route('chiefs.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chiefs.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <span>Chiefs</span>
            </a>
        <?php endif; ?>
        
        <!-- Staff Management - Only for Admin -->
        <?php if(auth()->user()->hasRole('admin')): ?>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <span>Users</span>
            </a>
            
            <!-- Admin Settings -->
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <span>Settings</span>
            </a>
        <?php endif; ?>
        
        <!-- Reports - Different routes based on role -->
        <?php if(auth()->user()->hasRole('chief')): ?>
            <a href="<?php echo e(route('chief.reports.index')); ?>" class="nav-item <?php echo e(request()->routeIs('chief.reports.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span>My Reports</span>
            </a>
        <?php elseif(auth()->user()->hasRole('admin|staff')): ?>
            <a href="<?php echo e(route('reports.index')); ?>" class="nav-item <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span>Reports</span>
            </a>
        <?php endif; ?>
        
        <!-- Documents - Only for Admin/Staff -->
        <?php if(auth()->user()->hasRole('admin|staff')): ?>
            <a href="<?php echo e(route('documents.index')); ?>" class="nav-item <?php echo e(request()->routeIs('documents.*') ? 'active' : ''); ?>">
                <div class="nav-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span>Documents</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- User Profile with Dropup -->
    <div class="sidebar-footer">
        <div class="nav-dropup">
            <div class="user-profile-dropup dropdown-toggle" data-target="user-dropup">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo e(Auth::user()->name ?? 'User'); ?></div>
                    <div class="user-role">
                        <?php if(auth()->user()->hasRole('chief')): ?>
                            Chief
                        <?php elseif(auth()->user()->hasRole('admin')): ?>
                            Administrator
                        <?php elseif(auth()->user()->hasRole('staff')): ?>
                            Staff
                        <?php else: ?>
                            User
                        <?php endif; ?>
                    </div>
                </div>
                <i class="fas fa-chevron-up dropdown-arrow"></i>
            </div>
            <div class="dropup-menu" id="user-dropup">
                <a href="<?php echo e(route('profile.edit')); ?>" class="dropup-item">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit Profile
                </a>
                <a href="<?php echo e(route('profile.password.edit')); ?>" class="dropup-item">
                    <i class="fas fa-key me-2"></i>
                    Change Password
                </a>
                <div class="dropup-divider"></div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-100" id="logout-form">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="dropup-item logout-btn w-100 text-start">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Sidebar Footer Styles */
    .sidebar-footer {
        margin-top: auto;
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-dropup {
        position: relative;
    }

    .user-profile-dropup {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .user-profile-dropup:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        color: white;
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
        text-transform: capitalize;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dropdown-arrow {
        margin-left: auto;
        font-size: 0.8rem;
        transition: transform 0.3s ease;
        opacity: 0.7;
        color: rgba(255, 255, 255, 0.6);
    }

    .nav-dropup.active .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Dropup Menu Styles */
    .dropup-menu {
        position: absolute;
        bottom: 100%;
        left: 0;
        width: 100%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid #e5e7eb;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 1000;
        margin-bottom: 0.5rem;
    }

    .nav-dropup.active .dropup-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropup-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .dropup-item:hover {
        background: #f3f4f6;
        color: #4361ee;
    }

    .dropup-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 0.5rem 0;
    }

    .logout-btn {
        color: #ef4444;
        width: 100%;
        border: none;
        background: none;
        padding: 0;
    }

    .logout-btn:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Form styles */
    #logout-form {
        margin: 0;
        padding: 0;
    }

    /* Ensure sidebar has proper z-index and layout */
    .sidebar {
        z-index: 1000;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: var(--sidebar-width, 250px);
        display: flex;
        flex-direction: column;
        background: var(--primary);
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 0.5rem 0;
    }

    /* Basic sidebar nav item styles */
    .nav-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-right: 3px solid white;
    }

    .nav-icon {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Logo Styles */
    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .logo-icon {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.1rem;
    }

    .logo-text {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Dark mode styles for dropup */
    body.dark-mode .dropup-menu {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .dropup-item {
        color: #e5e7eb;
    }

    body.dark-mode .dropup-item:hover {
        background: #374151;
        color: #6366f1;
    }

    body.dark-mode .dropup-divider {
        background: #374151;
    }

    body.dark-mode .logout-btn:hover {
        background: #7f1d1d;
        color: #fca5a5;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        
        .user-name, .user-role {
            font-size: 0.8rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sidebar initialized');

    // Dropup functionality
    const userDropupToggle = document.querySelector('.user-profile-dropup');
    const userDropup = document.querySelector('.nav-dropup');

    if (userDropupToggle) {
        userDropupToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle current dropup
            userDropup.classList.toggle('active');
        });
    }

    // Close dropup when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-dropup')) {
            document.querySelectorAll('.nav-dropup').forEach(dropup => {
                dropup.classList.remove('active');
            });
        }
    });

    // Close dropup when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.nav-dropup').forEach(dropup => {
                dropup.classList.remove('active');
            });
        }
    });

    // Prevent dropup close when clicking inside dropup menu
    document.querySelectorAll('.dropup-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Handle logout form submission
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
        const logoutButton = logoutForm.querySelector('button[type="submit"]');
        if (logoutButton) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show confirmation dialog
                if (confirm('Are you sure you want to logout?')) {
                    // Submit the form
                    logoutForm.submit();
                }
            });
        }
    }

    // Simple sidebar functionality for nav items
    const navItems = document.querySelectorAll('.nav-item');
    
    // Add click handlers for nav items
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // Close any open dropups when navigating
            document.querySelectorAll('.nav-dropup').forEach(dropup => {
                dropup.classList.remove('active');
            });
            
            // Close mobile sidebar if open
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
            }
        });
    });

    // Mobile menu toggle (if you have a mobile menu button)
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('mobile-open');
        });
    }
});
</script><?php /**PATH C:\Users\pprhl\cls_management\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>