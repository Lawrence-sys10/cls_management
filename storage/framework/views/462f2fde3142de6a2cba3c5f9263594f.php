

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Welcome to CLS Management System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card lands">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Lands</h3>
                    <div class="stat-value"><?php echo e($stats['total_lands'] ?? 0); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span><?php echo e($stats['verified_lands'] ?? 0); ?> verified</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-landmark"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card clients">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Clients</h3>
                    <div class="stat-value"><?php echo e($stats['total_clients'] ?? 0); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>Active clients</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card chiefs">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Chiefs</h3>
                    <div class="stat-value"><?php echo e($stats['total_chiefs'] ?? 0); ?></div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>Traditional authorities</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Pending Approvals</h3>
                    <div class="stat-value"><?php echo e($stats['pending_approvals'] ?? 0); ?></div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Needs attention</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Activities Section -->
    <div class="content-grid">
        <!-- Left Column -->
        <div>
            <!-- Allocation Trends Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Allocation Trends</div>
                    <div class="chart-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-calendar"></i>
                            Last 30 days
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="allocationChart" height="250"></canvas>
                </div>
            </div>

            <!-- Land Distribution Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Land Distribution</div>
                    <div class="chart-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="landDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div>
            <!-- Recent Activities -->
            <div class="activity-list">
                <div class="activity-header">
                    <div class="activity-title">Recent Allocations</div>
                    <a href="<?php echo e(route('allocations.index')); ?>" class="btn btn-outline">View All</a>
                </div>
                
                <?php
                    // Use the variable from controller or create empty collection
                    $recentAllocations = $recent_allocations ?? collect();
                ?>
                
                <?php $__empty_1 = true; $__currentLoopData = $recentAllocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="activity-item">
                    <div class="activity-icon allocation">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-text">
                            <strong><?php echo e($allocation->land->plot_number ?? 'N/A'); ?></strong> allocated to 
                            <strong><?php echo e($allocation->client->full_name ?? 'N/A'); ?></strong>
                        </div>
                        <div class="activity-time"><?php echo e($allocation->created_at->diffForHumans()); ?></div>
                        <div class="activity-meta">
                            <span class="badge 
                                <?php if($allocation->approval_status == 'approved'): ?> bg-success
                                <?php elseif($allocation->approval_status == 'pending'): ?> bg-warning
                                <?php else: ?> bg-secondary
                                <?php endif; ?>">
                                <?php echo e(ucfirst($allocation->approval_status)); ?>

                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <i class="fas fa-handshake fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No recent allocations</p>
                    <a href="<?php echo e(route('allocations.create')); ?>" class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-plus me-1"></i>Create Allocation
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="actions-title">Quick Actions</div>
                <div class="actions-grid">
                    <a href="<?php echo e(route('lands.create')); ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-text">Add Land</div>
                    </a>
                    
                    <a href="<?php echo e(route('clients.create')); ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="action-text">Add Client</div>
                    </a>
                    
                    <a href="<?php echo e(route('allocations.create')); ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="action-text">New Allocation</div>
                    </a>
                    
                    <a href="<?php echo e(route('chiefs.create')); ?>" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="action-text">Add Chief</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="status-item">
                                <div class="status-icon text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h6 class="mt-2 mb-1">Approved Allocations</h6>
                                <span class="badge bg-success"><?php echo e($stats['approved_allocations'] ?? 0); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="status-item">
                                <div class="status-icon text-warning">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <h6 class="mt-2 mb-1">Pending Approvals</h6>
                                <span class="badge bg-warning"><?php echo e($stats['pending_approvals'] ?? 0); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="status-item">
                                <div class="status-icon text-danger">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                                <h6 class="mt-2 mb-1">Rejected Allocations</h6>
                                <span class="badge bg-danger"><?php echo e($stats['rejected_allocations'] ?? 0); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="status-item">
                                <div class="status-icon text-info">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <h6 class="mt-2 mb-1">System Users</h6>
                                <span class="badge bg-info"><?php echo e($stats['total_users'] ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--primary);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-card.lands {
        border-left-color: var(--primary);
    }
    
    .stat-card.clients {
        border-left-color: var(--success);
    }
    
    .stat-card.chiefs {
        border-left-color: var(--warning);
    }
    
    .stat-card.pending {
        border-left-color: var(--danger);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .stat-info h3 {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card.lands .stat-icon {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }
    
    .stat-card.clients .stat-icon {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }
    
    .stat-card.chiefs .stat-icon {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
    }
    
    .stat-card.pending .stat-icon {
        background: rgba(230, 57, 70, 0.1);
        color: var(--danger);
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: var(--danger);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .chart-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .chart-body {
        position: relative;
        height: 250px;
    }
    
    .activity-list {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .activity-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .activity-item {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .activity-icon.allocation {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-text {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: var(--gray-500);
    }
    
    .activity-meta {
        margin-top: 0.25rem;
    }
    
    .quick-actions {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 1.5rem;
    }
    
    .actions-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 1.5rem;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.5rem 1rem;
        border-radius: var(--border-radius);
        background: var(--gray-100);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }
    
    .action-item:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
    }
    
    .action-item:hover .action-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
        background: white;
        color: var(--primary);
        transition: all 0.2s;
    }
    
    .action-text {
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }
    
    .status-item {
        padding: 1rem;
    }
    
    .status-icon {
        margin-bottom: 0.5rem;
    }
    
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
        
        .actions-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Allocation Trends Chart
        const allocationCtx = document.getElementById('allocationChart').getContext('2d');
        const allocationChart = new Chart(allocationCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Land Allocations',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Land Distribution Chart
        const landDistCtx = document.getElementById('landDistributionChart').getContext('2d');
        const landDistributionChart = new Chart(landDistCtx, {
            type: 'doughnut',
            data: {
                labels: ['Allocated', 'Vacant', 'Under Dispute', 'Reserved'],
                datasets: [{
                    data: [65, 20, 10, 5],
                    backgroundColor: [
                        '#4361ee',
                        '#4cc9f0',
                        '#f72585',
                        '#ff9e00'
                    ],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pprhl\cls_management\resources\views/dashboard/index.blade.php ENDPATH**/ ?>