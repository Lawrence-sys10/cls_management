<?php

namespace App\Exports;

use App\Models\Land;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LandsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Land::with([''chief'']);

        if (isset($this->filters[''chief_id'']) && $this->filters[''chief_id'']) {
            $query->where(''chief_id'', $this->filters[''chief_id'']);
        }

        if (isset($this->filters[''status'']) && $this->filters[''status'']) {
            $query->where(''ownership_status'', $this->filters[''status'']);
        }

        if (isset($this->filters[''search'']) && $this->filters[''search'']) {
            $query->where(function($q) {
                $q->where(''plot_number'', ''like'', ''%'' . $this->filters[''search''] . ''%'')
                  ->orWhere(''location'', ''like'', ''%'' . $this->filters[''search''] . ''%'');
            });
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
            ''Registration Date'',
            ''Verification Status'',
            ''Coordinates'',
        ];
    }

    public function map($land): array
    {
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
            $land->registration_date->format(''Y-m-d''),
            $land->is_verified ? ''Verified'' : ''Pending'',
            $land->latitude && $land->longitude ? $land->latitude . '', '' . $land->longitude : ''N/A'',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [''font'' => [''bold'' => true]],
            
            // Style the header row
            ''A1:L1'' => [
                ''fill'' => [
                    ''fillType'' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    ''color'' => [''argb'' => ''FFE6F3FF'']
                ]
            ],
        ];
    }
}
