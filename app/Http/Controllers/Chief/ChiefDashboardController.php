<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiefDashboardController extends Controller
{
    public function index()
    {
        $chief = Auth::user();
        
        // Ensure the user is actually a chief
        if (!$chief->hasRole('chief')) {
            abort(403, 'Unauthorized access.');
        }

        try {
            // Get counts for dashboard stats - using the same variable names as the view expects
            $landCount = $chief->lands()->count();
            $clientCount = $chief->clients()->count();
            $allocationCount = $chief->allocations()->count();
            
            // Get dispute count - handle if Dispute model doesn't exist yet
            try {
                $disputeCount = $chief->disputes()->where('status', 'pending')->count();
            } catch (\Exception $e) {
                // If disputes table doesn't exist yet, set to 0
                $disputeCount = 0;
            }

            // Get recent allocations
            $recentAllocations = $chief->allocations()
                ->with(['land', 'client'])
                ->latest()
                ->take(5)
                ->get();

            // Get available lands count
            $availableLands = $chief->lands()
                ->where(function($query) {
                    $query->where('ownership_status', 'vacant')
                          ->orWhere('ownership_status', 'available')
                          ->orWhere('status', 'vacant')
                          ->orWhere('status', 'available')
                          ->orWhereNull('ownership_status')
                          ->orWhereNull('status');
                })
                ->count();

            // Get active allocations count
            $activeAllocations = $chief->allocations()
                ->where('status', 'active')
                ->count();

            // Get recent activities (if activity log exists)
            $recentActivities = collect();
            if (method_exists($chief, 'activityLogs')) {
                $recentActivities = $chief->activityLogs()
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }

            return view('chiefs.dashboard', compact(
                'landCount',
                'clientCount',
                'allocationCount',
                'disputeCount',
                'recentAllocations',
                'availableLands',
                'activeAllocations',
                'recentActivities'
            ));

        } catch (\Exception $e) {
            \Log::error('Error loading chief dashboard: ' . $e->getMessage());
            
            // Return a simplified dashboard if there's an error
            return view('chiefs.dashboard', [
                'landCount' => 0,
                'clientCount' => 0,
                'allocationCount' => 0,
                'disputeCount' => 0,
                'recentAllocations' => collect(),
                'availableLands' => 0,
                'activeAllocations' => 0,
                'recentActivities' => collect(),
                'error' => 'Unable to load full dashboard data. Some features may be unavailable.'
            ]);
        }
    }

    /**
     * Get quick stats for AJAX requests
     */
    public function getQuickStats()
    {
        $chief = Auth::user();
        
        if (!$chief->hasRole('chief')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'lands' => 0,
            'clients' => 0,
            'allocations' => 0,
            'available_lands' => 0,
            'active_allocations' => 0,
            'pending_disputes' => 0,
        ];

        try {
            // Get basic counts
            $stats['lands'] = $chief->lands()->count();
            $stats['clients'] = $chief->clients()->count();
            $stats['allocations'] = $chief->allocations()->count();
            
            // Get available lands
            $stats['available_lands'] = $chief->lands()
                ->where(function($query) {
                    $query->where('ownership_status', 'vacant')
                          ->orWhere('ownership_status', 'available')
                          ->orWhere('status', 'vacant')
                          ->orWhere('status', 'available')
                          ->orWhereNull('ownership_status')
                          ->orWhereNull('status');
                })
                ->count();

            // Get active allocations
            $stats['active_allocations'] = $chief->allocations()
                ->where('status', 'active')
                ->count();

            // Get pending disputes
            try {
                $stats['pending_disputes'] = $chief->disputes()
                    ->where('status', 'pending')
                    ->count();
            } catch (\Exception $e) {
                $stats['pending_disputes'] = 0;
            }

        } catch (\Exception $e) {
            \Log::error('Chief Quick Stats Error: ' . $e->getMessage());
        }

        return response()->json($stats);
    }

    /**
     * Get chart data for the chief dashboard
     */
    public function getChartData(Request $request)
    {
        $chief = Auth::user();
        
        if (!$chief->hasRole('chief')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $period = $request->get('period', 'monthly');
        
        try {
            $data = [
                'allocations' => $this->getAllocationsChartData($chief, $period),
                'lands' => $this->getLandsChartData($chief, $period),
                'clients' => $this->getClientsChartData($chief, $period),
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            \Log::error('Chief Chart Data Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load chart data'], 500);
        }
    }

    /**
     * Get allocations chart data
     */
    private function getAllocationsChartData($chief, $period)
    {
        $query = $chief->allocations();
        return $this->formatChartData($query, $period, 'allocations');
    }

    /**
     * Get lands chart data
     */
    private function getLandsChartData($chief, $period)
    {
        $query = $chief->lands();
        return $this->formatChartData($query, $period, 'lands');
    }

    /**
     * Get clients chart data
     */
    private function getClientsChartData($chief, $period)
    {
        $query = $chief->clients();
        return $this->formatChartData($query, $period, 'clients');
    }

    /**
     * Format chart data based on period
     */
    private function formatChartData($query, $period, $type)
    {
        $now = now();
        $data = [];
        
        switch ($period) {
            case 'weekly':
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $count = (clone $query)->whereDate('created_at', $date->format('Y-m-d'))->count();
                    $data[] = [
                        'label' => $date->format('D'),
                        'value' => $count
                    ];
                }
                break;

            case 'monthly':
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $count = (clone $query)->whereYear('created_at', $date->year)
                                 ->whereMonth('created_at', $date->month)
                                 ->count();
                    $data[] = [
                        'label' => $date->format('M Y'),
                        'value' => $count
                    ];
                }
                break;

            case 'yearly':
                for ($i = 4; $i >= 0; $i--) {
                    $year = $now->year - $i;
                    $count = (clone $query)->whereYear('created_at', $year)->count();
                    $data[] = [
                        'label' => (string)$year,
                        'value' => $count
                    ];
                }
                break;

            default:
                // Default to monthly
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $count = (clone $query)->whereYear('created_at', $date->year)
                                 ->whereMonth('created_at', $date->month)
                                 ->count();
                    $data[] = [
                        'label' => $date->format('M Y'),
                        'value' => $count
                    ];
                }
                break;
        }

        return $data;
    }

    /**
     * Get recent activities for AJAX requests
     */
    public function getRecentActivities()
    {
        $chief = Auth::user();
        
        if (!$chief->hasRole('chief')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $activities = collect();
            if (method_exists($chief, 'activityLogs')) {
                $activities = $chief->activityLogs()
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($activity) {
                        return [
                            'id' => $activity->id,
                            'description' => $activity->description,
                            'type' => $activity->type ?? 'info',
                            'created_at' => $activity->created_at->diffForHumans(),
                            'user' => $activity->user ? $activity->user->name : 'System'
                        ];
                    });
            }

            return response()->json($activities);

        } catch (\Exception $e) {
            \Log::error('Chief Recent Activities Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load activities'], 500);
        }
    }

    /**
     * Get recent allocations for AJAX requests
     */
    public function getRecentAllocations()
    {
        $chief = Auth::user();
        
        if (!$chief->hasRole('chief')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $allocations = $chief->allocations()
                ->with(['land', 'client'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($allocation) {
                    return [
                        'id' => $allocation->id,
                        'client_name' => $allocation->client->full_name,
                        'plot_number' => $allocation->land->plot_number,
                        'location' => $allocation->land->location,
                        'allocation_date' => $allocation->allocation_date->format('M d, Y'),
                        'status' => $allocation->status,
                    ];
                });

            return response()->json($allocations);

        } catch (\Exception $e) {
            \Log::error('Chief Recent Allocations Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load recent allocations'], 500);
        }
    }

    /**
     * Get available lands for AJAX requests
     */
    public function getAvailableLands()
    {
        $chief = Auth::user();
        
        if (!$chief->hasRole('chief')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $availableLands = $chief->lands()
                ->where(function($query) {
                    $query->where('ownership_status', 'vacant')
                          ->orWhere('ownership_status', 'available')
                          ->orWhere('status', 'vacant')
                          ->orWhere('status', 'available')
                          ->orWhereNull('ownership_status')
                          ->orWhereNull('status');
                })
                ->count();

            return response()->json(['available_lands' => $availableLands]);

        } catch (\Exception $e) {
            \Log::error('Chief Available Lands Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load available lands data'], 500);
        }
    }
}