<?php

namespace App\Exports;

use App\Models\Land;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class LandsReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Land::with([''chief'', ''allocation.client'']);

        if (isset($this->filters[''start_date'']) && $this->filters[''start_date'']) {
            $query->where(''registration_date'', ''>='', $this->filters[''start_date'']);
        }

        if (isset($this->filters[''end_date'']) && $this->filters[''end_date'']) {
            $query->where(''registration_date'', ''<='', $this->filters[''end_date'']);
        }

        if (isset($this->filters[''chief_id'']) && $this->filters[''chief_id'']) {
            $query->where(''chief_id'', $this->filters[''chief_id'']);
        }

        if (isset($this->filters[''status'']) && $this->filters[''status'']) {
            $query->where(''ownership_status'', $this->filters[''status'']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            ''Plot Number'',
            ''Location'',
            ''Area (Acres)'',
            ''Area (Hectares)'',
            ''Chief'',
            ''Jurisdiction'',
            ''Ownership Status'',
            ''Land Use'',
            ''Price (GHS)'',
            ''Current Allocation'',
            ''Client Phone'',
            ''Registration Date'',
            ''Verification Status'',
        ];
    }

    public function map($land): array
    {
        $currentAllocation = $land->allocation ? $land->allocation->client->full_name : ''Vacant'';
        $clientPhone = $land->allocation ? $land->allocation->client->phone : ''N/A'';

        return [
            $land->plot_number,
            $land->location,
            number_format($land->area_acres, 2),
            number_format($land->area_hectares, 2),
            $land->chief->name,
            $land->chief->jurisdiction,
            ucfirst(str_replace(''_'', '' '', $land->ownership_status)),
            ucfirst($land->land_use),
            number_format($land->price, 2),
            $currentAllocation,
            $clientPhone,
            $land->registration_date->format(''Y-m-d''),
            $land->is_verified ? ''Verified'' : ''Pending'',
        ];
    }

    public function title(): string
    {
        return ''Lands Report'';
    }
}
