<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Land;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\Chief;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        try {
            $stats = $this->getDashboardStats();
            $recentActivities = $this->getRecentActivities();
            $chartData = $this->getChartDataForView();

            return view('dashboard.index', compact(
                'stats',
                'recentActivities',
                'chartData'
            ));
        } catch (\Exception $e) {
            // Log the error and return a safe fallback
            \Log::error('Dashboard loading error: ' . $e->getMessage());
            
            return view('dashboard.index', [
                'stats' => $this->getEmptyStats(),
                'recentActivities' => collect(),
                'chartData' => $this->getEmptyChartData()
            ]);
        }
    }

    /**
     * Admin dashboard
     */
    public function admin(): View
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'inactive_users' => User::where('is_active', false)->count(),
                'total_lands' => Land::count(),
                'total_clients' => Client::count(),
                'total_chiefs' => Chief::count(),
                'total_allocations' => Allocation::count(),
                'pending_allocations' => Allocation::where('approval_status', 'pending')->count(),
                'approved_allocations' => Allocation::where('approval_status', 'approved')->count(),
                'rejected_allocations' => Allocation::where('approval_status', 'rejected')->count(),
            ];

            $recentUsers = User::with('roles')->latest()->take(5)->get();
            $systemStats = $this->getSystemStats();

            return view('admin.dashboard', compact('stats', 'recentUsers', 'systemStats'));
        } catch (\Exception $e) {
            \Log::error('Admin dashboard error: ' . $e->getMessage());
            
            return view('admin.dashboard', [
                'stats' => $this->getEmptyAdminStats(),
                'recentUsers' => collect(),
                'systemStats' => []
            ]);
        }
    }

    /**
     * Chief dashboard
     */
    public function chief(): View
    {
        try {
            $chiefId = auth()->id();
            
            $stats = [
                'total_lands' => Land::where('chief_id', $chiefId)->count(),
                'verified_lands' => Land::where('chief_id', $chiefId)->where('is_verified', true)->count(),
                'total_allocations' => Allocation::where('chief_id', $chiefId)->count(),
                'pending_allocations' => Allocation::where('chief_id', $chiefId)->where('approval_status', 'pending')->count(),
                'approved_allocations' => Allocation::where('chief_id', $chiefId)->where('approval_status', 'approved')->count(),
                'recent_allocations' => Allocation::where('chief_id', $chiefId)->latest()->take(5)->count(),
            ];

            $recentLands = Land::where('chief_id', $chiefId)->latest()->take(5)->get();
            $recentAllocations = Allocation::with(['client', 'land'])
                ->where('chief_id', $chiefId)
                ->latest()
                ->take(5)
                ->get();

            return view('chief.dashboard', compact('stats', 'recentLands', 'recentAllocations'));
        } catch (\Exception $e) {
            \Log::error('Chief dashboard error: ' . $e->getMessage());
            
            return view('chief.dashboard', [
                'stats' => $this->getEmptyChiefStats(),
                'recentLands' => collect(),
                'recentAllocations' => collect()
            ]);
        }
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(): array
    {
        return [
            'total_lands' => Land::count(),
            'total_clients' => Client::count(),
            'total_chiefs' => Chief::count(),
            'total_allocations' => Allocation::count(),
            'pending_approvals' => Allocation::where('approval_status', 'pending')->count(),
            'verified_lands' => Land::where('is_verified', true)->count(),
            'total_users' => User::count(),
            'active_allocations' => Allocation::where('approval_status', 'approved')->count(),
            'rejected_allocations' => Allocation::where('approval_status', 'rejected')->count(),
        ];
    }

    /**
     * Get system statistics for admin dashboard
     */
    private function getSystemStats(): array
    {
        return [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'environment' => app()->environment(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_driver' => config('database.default'),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
        ];
    }

    /**
     * Get recent activities for dashboard
     */
    private function getRecentActivities()
    {
        return Allocation::with(['land', 'client', 'chief'])
            ->withTrashed() // Include soft deleted if needed
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($allocation) {
                return [
                    'id' => $allocation->id,
                    'client_name' => $allocation->client->name ?? 'N/A',
                    'land_title' => $allocation->land->title ?? 'N/A',
                    'chief_name' => $allocation->chief->name ?? 'N/A',
                    'allocation_date' => $allocation->allocation_date?->format('M d, Y'),
                    'status' => $allocation->approval_status,
                    'status_class' => $this->getStatusClass($allocation->approval_status),
                ];
            });
    }

    /**
     * Get chart data for the view
     */
    private function getChartDataForView(): array
    {
        $currentYear = date('Y');
        
        return [
            'land_distribution' => Land::select('ownership_status', DB::raw('count(*) as total'))
                ->groupBy('ownership_status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->ownership_status => $item->total];
                }),

            'monthly_allocations' => Allocation::select(
                    DB::raw('MONTH(allocation_date) as month'),
                    DB::raw('YEAR(allocation_date) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('allocation_date', $currentYear)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->mapWithKeys(function ($item) {
                    $monthName = Carbon::create()->month($item->month)->format('M');
                    return [$monthName => $item->count];
                }),

            'allocation_status' => Allocation::select('approval_status', DB::raw('count(*) as count'))
                ->groupBy('approval_status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->approval_status => $item->count];
                }),

            'chief_performance' => Chief::withCount(['lands', 'allocations'])
                ->having('lands_count', '>', 0)
                ->orderBy('lands_count', 'desc')
                ->take(10)
                ->get()
                ->map(function ($chief) {
                    return [
                        'name' => $chief->name,
                        'lands_count' => $chief->lands_count,
                        'allocations_count' => $chief->allocations_count,
                    ];
                })
        ];
    }

    /**
     * API endpoint for chart data (AJAX requests)
     */
    public function getChartData(): JsonResponse
    {
        try {
            $data = [
                'land_ownership' => $this->getLandOwnershipData(),
                'allocation_trends' => $this->getAllocationTrends(),
                'chief_performance' => $this->getChiefPerformance(),
                'allocation_status' => $this->getAllocationStatusData(),
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'last_updated' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            \Log::error('Chart data error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load chart data',
                'data' => []
            ], 500);
        }
    }

    /**
     * Get land ownership distribution data
     */
    private function getLandOwnershipData()
    {
        return Land::select('ownership_status', DB::raw('count(*) as total'))
            ->groupBy('ownership_status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->ownership_status,
                    'count' => $item->total
                ];
            });
    }

    /**
     * Get allocation trends for current year
     */
    private function getAllocationTrends()
    {
        $currentYear = date('Y');
        
        return Allocation::select(
                DB::raw('MONTH(allocation_date) as month'),
                DB::raw('MONTHNAME(allocation_date) as month_name'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('allocation_date', $currentYear)
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month_name,
                    'allocations' => $item->count
                ];
            });
    }

    /**
     * Get chief performance data
     */
    private function getChiefPerformance()
    {
        return Chief::withCount(['lands', 'allocations'])
            ->having('lands_count', '>', 0)
            ->orderBy('allocations_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($chief) {
                return [
                    'name' => $chief->name,
                    'lands' => $chief->lands_count,
                    'allocations' => $chief->allocations_count,
                ];
            });
    }

    /**
     * Get allocation status distribution
     */
    private function getAllocationStatusData()
    {
        return Allocation::select('approval_status', DB::raw('count(*) as count'))
            ->groupBy('approval_status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->approval_status),
                    'count' => $item->count
                ];
            });
    }

    /**
     * Get CSS class for status badges
     */
    private function getStatusClass(string $status): string
    {
        return match($status) {
            'approved' => 'bg-success',
            'pending' => 'bg-warning',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get empty stats for error fallback
     */
    private function getEmptyStats(): array
    {
        return [
            'total_lands' => 0,
            'total_clients' => 0,
            'total_chiefs' => 0,
            'total_allocations' => 0,
            'pending_approvals' => 0,
            'verified_lands' => 0,
            'total_users' => 0,
            'active_allocations' => 0,
            'rejected_allocations' => 0,
        ];
    }

    /**
     * Get empty admin stats for error fallback
     */
    private function getEmptyAdminStats(): array
    {
        return [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'total_lands' => 0,
            'total_clients' => 0,
            'total_chiefs' => 0,
            'total_allocations' => 0,
            'pending_allocations' => 0,
            'approved_allocations' => 0,
            'rejected_allocations' => 0,
        ];
    }

    /**
     * Get empty chief stats for error fallback
     */
    private function getEmptyChiefStats(): array
    {
        return [
            'total_lands' => 0,
            'verified_lands' => 0,
            'total_allocations' => 0,
            'pending_allocations' => 0,
            'approved_allocations' => 0,
            'recent_allocations' => 0,
        ];
    }

    /**
     * Get empty chart data for error fallback
     */
    private function getEmptyChartData(): array
    {
        return [
            'land_distribution' => collect(),
            'monthly_allocations' => collect(),
            'allocation_status' => collect(),
            'chief_performance' => collect(),
        ];
    }

    /**
     * Get quick stats for widgets (optional)
     */
    public function getQuickStats(): JsonResponse
    {
        $stats = $this->getDashboardStats();
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get public statistics (for API)
     */
    public function getPublicStats(Request $request): JsonResponse
    {
        try {
            $stats = [
                'total_lands' => Land::count(),
                'total_chiefs' => Chief::count(),
                'total_allocations' => Allocation::where('approval_status', 'approved')->count(),
                'total_clients' => Client::count(),
                'system_status' => 'operational',
                'updated_at' => now()->toISOString(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Public statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Public stats error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve public statistics'
            ], 500);
        }
    }
}