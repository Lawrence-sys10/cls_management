<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CLS Management System')</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --dark: #1d3557;
            --light: #f8f9fa;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            
            --sidebar-width: 260px;
            --header-height: 70px;
            --border-radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: var(--gray-800);
            overflow-x: hidden;
            transition: all 0.3s ease;
        }
        
        /* Dark Mode */
        body.dark-mode {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        
        body.dark-mode .main-content {
            background-color: #0f172a;
        }
        
        body.dark-mode .card {
            background: #1e293b;
            color: #e2e8f0;
        }
        
        body.dark-mode .card-header {
            background: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }
        
        body.dark-mode .table-container {
            background: #1e293b;
        }
        
        body.dark-mode .table {
            color: #e2e8f0;
        }
        
        body.dark-mode .table thead th {
            background: #334155;
            border-color: #475569;
            color: #cbd5e1;
        }
        
        body.dark-mode .table tbody td {
            border-color: #334155;
        }
        
        body.dark-mode .table tbody tr:hover {
            background: #334155;
        }
        
        body.dark-mode .form-control {
            background: #1e293b;
            border-color: #475569;
            color: #e2e8f0;
        }
        
        body.dark-mode .form-control:focus {
            background: #1e293b;
            border-color: var(--primary);
            color: #e2e8f0;
        }
        
        body.dark-mode .form-label {
            color: #e2e8f0;
        }
        
        body.dark-mode .page-title h1 {
            color: #f1f5f9;
        }
        
        body.dark-mode .page-title p {
            color: #94a3b8;
        }
        
        body.dark-mode .footer {
            color: #94a3b8;
            border-color: #334155;
        }
        
        /* Layout */
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }
        
        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .nav-item:hover, .nav-item.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
            border-radius: 0 4px 4px 0;
        }
        
        .nav-icon {
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem 0;
        }
        
        .page-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }
        
        .page-title p {
            color: var(--gray-600);
            font-size: 0.95rem;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Notification and Theme Controls */
        .header-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .control-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--gray-300);
            background: white;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
        }
        
        body.dark-mode .control-btn {
            background: #1e293b;
            border-color: #475569;
            color: #cbd5e1;
        }
        
        .control-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
            text-decoration: none;
        }
        
        body.dark-mode .control-btn:hover {
            background: #334155;
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Notification Dropdown */
        .notification-dropdown .dropdown-menu {
            width: 350px;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            padding: 0;
        }
        
        body.dark-mode .notification-dropdown .dropdown-menu {
            background: #1e293b;
            border-color: #475569;
        }
        
        .notification-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        body.dark-mode .notification-header {
            border-color: #475569;
        }
        
        .notification-header h6 {
            margin: 0;
            font-weight: 600;
        }
        
        body.dark-mode .notification-header h6 {
            color: #e2e8f0;
        }
        
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        body.dark-mode .notification-item {
            border-color: #334155;
        }
        
        .notification-item:hover {
            background: var(--gray-50);
        }
        
        body.dark-mode .notification-item:hover {
            background: #334155;
        }
        
        .notification-item.unread {
            background: rgba(67, 97, 238, 0.05);
        }
        
        body.dark-mode .notification-item.unread {
            background: rgba(67, 97, 238, 0.1);
        }
        
        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.8rem;
        }
        
        .notification-text {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        body.dark-mode .notification-title {
            color: #e2e8f0;
        }
        
        .notification-message {
            font-size: 0.8rem;
            color: var(--gray-600);
            line-height: 1.4;
        }
        
        body.dark-mode .notification-message {
            color: #94a3b8;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }
        
        body.dark-mode .notification-time {
            color: #64748b;
        }
        
        .notification-footer {
            padding: 0.75rem 1.25rem;
            text-align: center;
            border-top: 1px solid var(--gray-200);
        }
        
        body.dark-mode .notification-footer {
            border-color: #475569;
        }
        
        .view-all-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .view-all-link:hover {
            text-decoration: underline;
        }
        
        /* Theme Toggle Animation */
        .theme-toggle .fa-sun {
            display: none;
        }
        
        body.dark-mode .theme-toggle .fa-moon {
            display: none;
        }
        
        body.dark-mode .theme-toggle .fa-sun {
            display: block;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--secondary);
            color: white;
            text-decoration: none;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #3aabd8;
            color: white;
            text-decoration: none;
        }
        
        /* Card Styles */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: none;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            padding: 1rem 1.5rem;
        }
        
        .table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .table tbody tr:hover {
            background: var(--gray-50);
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 6px;
        }
        
        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .badge-primary {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
        
        .badge-warning {
            background: rgba(247, 37, 133, 0.1);
            color: var(--warning);
        }
        
        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        /* Action Links */
        .action-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .action-link.view {
            color: var(--primary);
        }
        
        .action-link.edit {
            color: var(--success);
        }
        
        .action-link.delete {
            color: var(--danger);
        }
        
        .action-link:hover {
            background: var(--gray-100);
            text-decoration: none;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--gray-600);
            font-size: 0.85rem;
            border-top: 1px solid var(--gray-200);
            margin-top: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .notification-dropdown .dropdown-menu {
                width: 300px;
                right: -1rem !important;
                left: auto !important;
            }
        }
        
        @media (max-width: 576px) {
            .header-controls {
                gap: 0.5rem;
            }
            
            .control-btn {
                width: 36px;
                height: 36px;
            }
            
            .notification-dropdown .dropdown-menu {
                width: 280px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @include('partials.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h1>@yield('title', 'Dashboard')</h1>
                    <p>@yield('subtitle', 'CLS Management System')</p>
                </div>
                
                <div class="header-actions">
                    @hasSection('actions')
                        @yield('actions')
                    @endif
                    
                    <!-- Header Controls - Notifications and Theme Toggle -->
                    <div class="header-controls">
                        <!-- Theme Toggle -->
                        <button class="control-btn theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                            <i class="fas fa-moon"></i>
                            <i class="fas fa-sun"></i>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div class="dropdown notification-dropdown">
                            <a class="control-btn dropdown-toggle" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge">3</span>
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                <li>
                                    <div class="notification-header">
                                        <h6>Notifications</h6>
                                        <small class="text-muted">3 unread</small>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <!-- Notification Items -->
                                        <div class="notification-item unread">
                                            <div class="notification-content">
                                                <div class="notification-icon">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div class="notification-text">
                                                    <div class="notification-title">New Client Registered</div>
                                                    <div class="notification-message">John Doe has registered as a new client in the system.</div>
                                                    <div class="notification-time">2 minutes ago</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="notification-item unread">
                                            <div class="notification-content">
                                                <div class="notification-icon" style="background: var(--success);">
                                                    <i class="fas fa-handshake"></i>
                                                </div>
                                                <div class="notification-text">
                                                    <div class="notification-title">Allocation Approved</div>
                                                    <div class="notification-message">Land allocation #LA-2024-001 has been approved.</div>
                                                    <div class="notification-time">1 hour ago</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="notification-item">
                                            <div class="notification-content">
                                                <div class="notification-icon" style="background: var(--warning);">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="notification-text">
                                                    <div class="notification-title">System Maintenance</div>
                                                    <div class="notification-message">Scheduled maintenance this weekend from 2-4 AM.</div>
                                                    <div class="notification-time">Yesterday</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-footer">
                                        <a href="#" class="view-all-link">View All Notifications</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            @yield('content')
            
            <!-- Footer -->
            <div class="footer">
                <p>© {{ date('Y') }} CLS Management System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Theme Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            
            // Check for saved theme preference or default to light
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                body.classList.add('dark-mode');
            }
            
            // Theme toggle event
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');
                    
                    // Save theme preference
                    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
                    localStorage.setItem('theme', currentTheme);
                });
            }
            
            // Mobile sidebar toggle (if exists)
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Mark notifications as read when clicked
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    updateNotificationBadge();
                });
            });
            
            function updateNotificationBadge() {
                const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    if (unreadCount > 0) {
                        badge.textContent = unreadCount;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>