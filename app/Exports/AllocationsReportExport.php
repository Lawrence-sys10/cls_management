<?php

namespace App\Exports;

use App\Models\Allocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllocationsReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Allocation::with([''land'', ''client'', ''chief'', ''processedBy.user'']);

        if (isset($this->filters[''start_date'']) && $this->filters[''start_date'']) {
            $query->where(''allocation_date'', ''>='', $this->filters[''start_date'']);
        }

        if (isset($this->filters[''end_date'']) && $this->filters[''end_date'']) {
            $query->where(''allocation_date'', ''<='', $this->filters[''end_date'']);
        }

        if (isset($this->filters[''status'']) && $this->filters[''status'']) {
            $query->where(''approval_status'', $this->filters[''status'']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            ''Allocation ID'',
            ''Plot Number'',
            ''Location'',
            ''Client Name'',
            ''Client Phone'',
            ''Chief'',
            ''Allocation Date'',
            ''Approval Status'',
            ''Payment Status'',
            ''Payment Amount (GHS)'',
            ''Processed By'',
            ''Chief Approval Date'',
            ''Registrar Approval Date'',
        ];
    }

    public function map($allocation): array
    {
        return [
            $allocation->id,
            $allocation->land->plot_number,
            $allocation->land->location,
            $allocation->client->full_name,
            $allocation->client->phone,
            $allocation->chief->name,
            $allocation->allocation_date->format(''Y-m-d''),
            ucfirst($allocation->approval_status),
            ucfirst($allocation->payment_status),
            number_format($allocation->payment_amount, 2),
            $allocation->processedBy->user->name,
            $allocation->chief_approval_date ? $allocation->chief_approval_date->format(''Y-m-d'') : ''Pending'',
            $allocation->registrar_approval_date ? $allocation->registrar_approval_date->format(''Y-m-d'') : ''Pending'',
        ];
    }
}
