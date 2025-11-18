<?php

namespace App\Exports;

use App\Models\Land;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LandsReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Land::with(['chief', 'allocation.client']);

        if (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->where('registration_date', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->where('registration_date', '<=', $this->filters['end_date']);
        }

        if (isset($this->filters['chief_id']) && $this->filters['chief_id']) {
            $query->where('chief_id', $this->filters['chief_id']);
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('ownership_status', $this->filters['status']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Plot Number',
            'Location',
            'Area (Acres)',
            'Area (Hectares)',
            'Chief',
            'Jurisdiction',
            'Ownership Status',
            'Land Use',
            'Price (GHS)',
            'Current Allocation',
            'Client Phone',
            'Registration Date',
            'Verification Status',
        ];
    }

    public function map($land): array
    {
        // Safe relationship access with null checks
        $currentAllocation = 'Vacant';
        $clientPhone = 'N/A';
        
        if ($land->allocation && $land->allocation->client) {
            $currentAllocation = $land->allocation->client->full_name ?? 'Vacant';
            $clientPhone = $land->allocation->client->phone ?? 'N/A';
        }

        $chiefName = $land->chief ? $land->chief->name : 'N/A';
        $jurisdiction = $land->chief ? $land->chief->jurisdiction : 'N/A';

        return [
            $land->plot_number ?? 'N/A',
            $land->location ?? 'N/A',
            $land->area_acres ? number_format($land->area_acres, 2) : '0.00',
            $land->area_hectares ? number_format($land->area_hectares, 2) : '0.00',
            $chiefName,
            $jurisdiction,
            $land->ownership_status ? ucfirst(str_replace('_', ' ', $land->ownership_status)) : 'N/A',
            $land->land_use ? ucfirst($land->land_use) : 'N/A',
            $land->price ? number_format($land->price, 2) : '0.00',
            $currentAllocation,
            $clientPhone,
            $land->registration_date ? $land->registration_date->format('Y-m-d') : 'N/A',
            $land->is_verified ? 'Verified' : 'Pending',
        ];
    }

    public function title(): string
    {
        return 'Lands Report';
    }
}