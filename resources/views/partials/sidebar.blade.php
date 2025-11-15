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
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('lands.index') }}" class="nav-item {{ request()->routeIs('lands.*') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <span>Lands</span>
        </a>
        
        <a href="{{ route('clients.index') }}" class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span>Clients</span>
        </a>
        
        <a href="{{ route('allocations.index') }}" class="nav-item {{ request()->routeIs('allocations.*') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <span>Allocations</span>
        </a>
        
        <a href="{{ route('chiefs.index') }}" class="nav-item {{ request()->routeIs('chiefs.*') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-crown"></i>
            </div>
            <span>Chiefs</span>
        </a>
        
        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <span>Reports</span>
        </a>
    </div>

    <!-- User Profile with Dropup -->
    <div class="sidebar-footer">
        <div class="nav-dropup">
            <div class="user-profile-dropup dropdown-toggle" data-target="user-dropup">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="user-role">{{ Auth::user()->role ?? 'User' }}</div>
                </div>
                <i class="fas fa-chevron-up dropdown-arrow"></i>
            </div>
            <div class="dropup-menu" id="user-dropup">
                <a href="{{ route('profile.edit') }}" class="dropup-item">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit Profile
                </a>
                <a href="#" class="dropup-item" onclick="alert('Change password functionality coming soon!')">
                    <i class="fas fa-key me-2"></i>
                    Change Password
                </a>
                <div class="dropup-divider"></div>
                <form method="POST" action="{{ route('logout') }}" class="w-100">
                    @csrf
                    <button type="submit" class="dropup-item logout-btn">
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
    }

    .logout-btn:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Ensure sidebar has proper z-index and layout - PREVIOUS COLORS */
    .sidebar {
        z-index: 1000;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: var(--sidebar-width, 250px);
        display: flex;
        flex-direction: column;
        background: var(--primary); /* Previous sidebar color */
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 0.5rem 0;
    }

    /* Basic sidebar nav item styles - PREVIOUS COLORS */
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

    /* Logo Styles - PREVIOUS COLORS */
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
        color: var(--primary); /* Logo icon color matches sidebar */
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

    // Simple sidebar functionality for nav items
    const navItems = document.querySelectorAll('.nav-item');
    
    // Add click handlers for nav items
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // Close any open dropups when navigating
            document.querySelectorAll('.nav-dropup').forEach(dropup => {
                dropup.classList.remove('active');
            });
        });
    });
});
</script>