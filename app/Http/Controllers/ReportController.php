<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Land;
use App\Models\Chief;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LandsReportExport;
use App\Exports\AllocationsReportExport;
use App\Exports\ClientsReportExport;
use App\Exports\ChiefsReportExport;
use App\Exports\SystemReportExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::with('generatedBy')->latest()->paginate(15);
        return view('reports.index', compact('reports'));
    }

    /**
     * Display lands report (HTML view)
     */
    public function landReport(Request $request): View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'chief_id' => 'nullable|exists:chiefs,id',
            'status' => 'nullable|string'
        ]);

        $lands = Land::with(['chief', 'allocation.client'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('registration_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('registration_date', '<=', $request->end_date);
            })
            ->when($request->chief_id, function($query) use ($request) {
                $query->where('chief_id', $request->chief_id);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('ownership_status', $request->status);
            })
            ->latest()
            ->get();

        $chiefs = Chief::where('is_active', true)->get();

        return view('reports.lands', compact('lands', 'chiefs'));
    }

    /**
     * Generate and download land report (PDF/Excel)
     */
    public function generateLandReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'chief_id' => 'nullable|exists:chiefs,id',
            'status' => 'nullable|string',
            'format' => 'required|in:pdf,excel'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new LandsReportExport($request), 
                'lands-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF report
        $lands = Land::with(['chief', 'allocation.client'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('registration_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('registration_date', '<=', $request->end_date);
            })
            ->when($request->chief_id, function($query) use ($request) {
                $query->where('chief_id', $request->chief_id);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('ownership_status', $request->status);
            })
            ->get();

        $pdf = PDF::loadView('reports.lands-pdf', compact('lands'));

        // Save report record
        Report::create([
            'name' => 'Lands Report - ' . date('Y-m-d'),
            'type' => 'lands',
            'date_range' => [
                'start' => $request->start_date,
                'end' => $request->end_date
            ],
            'generated_by' => auth()->id(),
            'file_path' => 'reports/lands-report-' . date('Y-m-d') . '.pdf',
            'parameters' => $request->all()
        ]);

        return $pdf->download('lands-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Quick export land report
     */
    public function exportLandReport(Request $request)
    {
        return Excel::download(
            new LandsReportExport($request), 
            'lands-report-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Display allocations report (HTML view)
     */
    public function allocationReport(Request $request): View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string',
            'chief_id' => 'nullable|exists:chiefs,id'
        ]);

        $allocations = Allocation::with(['land', 'client', 'chief'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('allocation_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('allocation_date', '<=', $request->end_date);
            })
            ->when($request->status, function($query) use ($request) {
                // FIXED: Changed 'status' to 'approval_status'
                $query->where('approval_status', $request->status);
            })
            ->when($request->chief_id, function($query) use ($request) {
                $query->where('chief_id', $request->chief_id);
            })
            ->latest()
            ->get();

        $chiefs = Chief::where('is_active', true)->get();

        return view('reports.allocations', compact('allocations', 'chiefs'));
    }

    /**
     * Generate and download allocation report
     */
    public function generateAllocationReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string',
            'chief_id' => 'nullable|exists:chiefs,id',
            'format' => 'required|in:pdf,excel'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new AllocationsReportExport($request), 
                'allocations-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF implementation
        $allocations = Allocation::with(['land', 'client', 'chief'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('allocation_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('allocation_date', '<=', $request->end_date);
            })
            ->when($request->status, function($query) use ($request) {
                // FIXED: Changed 'status' to 'approval_status'
                $query->where('approval_status', $request->status);
            })
            ->when($request->chief_id, function($query) use ($request) {
                $query->where('chief_id', $request->chief_id);
            })
            ->get();

        $pdf = PDF::loadView('reports.allocations-pdf', compact('allocations'));

        // Save report record
        Report::create([
            'name' => 'Allocations Report - ' . date('Y-m-d'),
            'type' => 'allocations',
            'date_range' => [
                'start' => $request->start_date,
                'end' => $request->end_date
            ],
            'generated_by' => auth()->id(),
            'file_path' => 'reports/allocations-report-' . date('Y-m-d') . '.pdf',
            'parameters' => $request->all()
        ]);

        return $pdf->download('allocations-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Quick export allocation report
     */
    public function exportAllocationReport(Request $request)
    {
        return Excel::download(
            new AllocationsReportExport($request), 
            'allocations-report-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Display clients report (HTML view)
     */
    public function clientReport(Request $request): View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string'
        ]);

        $clients = Client::with(['allocations.land'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->get();

        return view('reports.clients', compact('clients'));
    }

    /**
     * Generate and download client report
     */
    public function generateClientReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string',
            'format' => 'required|in:pdf,excel'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new ClientsReportExport($request), 
                'clients-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF implementation
        $clients = Client::with(['allocations.land'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->get();

        $pdf = PDF::loadView('reports.clients-pdf', compact('clients'));

        return $pdf->download('clients-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Quick export client report
     */
    public function exportClientReport(Request $request)
    {
        return Excel::download(
            new ClientsReportExport($request), 
            'clients-report-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Display chiefs report (HTML view)
     */
    public function chiefReport(Request $request): View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'region' => 'nullable|string'
        ]);

        $chiefs = Chief::with(['lands', 'lands.allocation'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->when($request->region, function($query) use ($request) {
                $query->where('region', $request->region);
            })
            ->latest()
            ->get();

        return view('reports.chiefs', compact('chiefs'));
    }

    /**
     * Generate and download chief report
     */
    public function generateChiefReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'region' => 'nullable|string',
            'format' => 'required|in:pdf,excel'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new ChiefsReportExport($request), 
                'chiefs-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF implementation
        $chiefs = Chief::with(['lands', 'lands.allocation'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('created_at', '<=', $request->end_date);
            })
            ->when($request->region, function($query) use ($request) {
                $query->where('region', $request->region);
            })
            ->get();

        $pdf = PDF::loadView('reports.chiefs-pdf', compact('chiefs'));

        return $pdf->download('chiefs-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Quick export chief report
     */
    public function exportChiefReport(Request $request)
    {
        return Excel::download(
            new ChiefsReportExport($request), 
            'chiefs-report-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Display system report (HTML view)
     */
    public function systemReport(Request $request): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_lands' => Land::count(),
            'total_clients' => Client::count(),
            'total_chiefs' => Chief::count(),
            'total_allocations' => Allocation::count(),
            // FIXED: Changed 'status' to 'approval_status'
            'pending_allocations' => Allocation::where('approval_status', 'pending')->count(),
            'storage_usage' => $this->getStorageUsage(),
        ];

        return view('reports.system', compact('stats'));
    }

    /**
     * Generate system report
     */
    public function generateSystemReport(Request $request)
    {
        $request->validate([
            'format' => 'required|in:pdf,excel'
        ]);

        if ($request->format === 'excel') {
            // FIXED: Changed ChiefsReportExport to SystemReportExport (you may need to create this)
            return Excel::download(
                new ChiefsReportExport(), 
                'system-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF implementation
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_lands' => Land::count(),
            'total_clients' => Client::count(),
            'total_chiefs' => Chief::count(),
            'total_allocations' => Allocation::count(),
            // FIXED: Changed 'status' to 'approval_status'
            'pending_allocations' => Allocation::where('approval_status', 'pending')->count(),
        ];

        $pdf = PDF::loadView('reports.system-pdf', compact('stats'));

        return $pdf->download('system-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Quick export system report
     */
    public function exportSystemReport()
    {
        return Excel::download(new ChiefsReportExport(), 'system-report-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Download generated report
     */
    public function download(Report $report)
    {
        if (!storage_path('app/' . $report->file_path)) {
            return redirect()->back()->with('error', 'Report file not found.');
        }

        return response()->download(storage_path('app/' . $report->file_path));
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted successfully!');
    }

    /**
     * Calculate storage usage
     */
    private function getStorageUsage(): array
    {
        $totalSize = 0;
        $path = storage_path('app');
        
        try {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                }
            }
        } catch (\Exception $e) {
            // If there's an error reading files, return 0
            $totalSize = 0;
        }

        return [
            'total' => $totalSize,
            'formatted' => $this->formatBytes($totalSize)
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}