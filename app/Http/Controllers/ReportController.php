<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LandsReportExport;
use App\Exports\AllocationsReportExport;
use App\Exports\ClientsReportExport;

class ReportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $reports = Report::with('generatedBy')->latest()->paginate(15);
        return view('reports.index', compact('reports'));
    }

    public function generateLandReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'chief_id' => 'nullable|exists:chiefs,id',
            'status' => 'nullable|string'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new LandsReportExport($request), 
                'lands-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF report
        $lands = \App\Models\Land::with(['chief', 'allocation.client'])
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

    public function generateAllocationReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new AllocationsReportExport($request), 
                'allocations-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // Similar implementation for allocation report...
        return Excel::download(new AllocationsReportExport($request), 'allocations-report.xlsx');
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted successfully!');
    }
}
