<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Land;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\Chief;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $stats = [
            'total_lands' => Land::count(),
            'total_clients' => Client::count(),
            'total_chiefs' => Chief::count(),
            'total_allocations' => Allocation::count(),
            'pending_approvals' => Allocation::where('approval_status', 'pending')->count(),
            'verified_lands' => Land::where('is_verified', true)->count(),
        ];

        // Recent activities
        $recent_allocations = Allocation::with(['land', 'client', 'chief'])
            ->latest()
            ->take(10)
            ->get();

        // Land distribution by status
        $land_distribution = Land::select('ownership_status', DB::raw('count(*) as total'))
            ->groupBy('ownership_status')
            ->get();

        // Monthly allocation trends
        $allocation_trends = Allocation::select(
                DB::raw('MONTH(allocation_date) as month'),
                DB::raw('YEAR(allocation_date) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('allocation_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recent_allocations',
            'land_distribution',
            'allocation_trends'
        ));
    }

    public function getChartData(): JsonResponse
    {
        $data = [
            'land_status' => Land::select('ownership_status', DB::raw('count(*) as total'))
                ->groupBy('ownership_status')
                ->get(),
            'monthly_allocations' => Allocation::select(
                    DB::raw('MONTHNAME(allocation_date) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('allocation_date', date('Y'))
                ->groupBy('month')
                ->orderBy(DB::raw('MONTH(allocation_date)'))
                ->get(),
            'chief_lands' => Chief::withCount('lands')->get()
        ];

        return response()->json($data);
    }
}
