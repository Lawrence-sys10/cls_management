<?php

namespace App\Exports;

use App\Models\Chief;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChiefsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Chief::query();

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('traditional_area', 'like', "%{$search}%")
                  ->orWhere('community', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['status'])) {
            if ($this->filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($this->filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if (!empty($this->filters['region'])) {
            $query->where('region', $this->filters['region']);
        }

        return $query->withCount(['lands', 'allocations'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Title',
            'Phone',
            'Email',
            'Traditional Area',
            'Community',
            'Region',
            'Rank Level',
            'Address',
            'City',
            'Years of Service',
            'Total Lands',
            'Total Allocations',
            'Status',
            'Created At'
        ];
    }

    public function map($chief): array
    {
        return [
            $chief->id,
            $chief->full_name,
            $chief->title,
            $chief->phone,
            $chief->email,
            $chief->traditional_area,
            $chief->community,
            $chief->region,
            $chief->rank_level,
            $chief->address,
            $chief->city,
            $chief->years_of_service,
            $chief->lands_count,
            $chief->allocations_count,
            $chief->is_active ? 'Active' : 'Inactive',
            $chief->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set auto-size for columns
            'A' => ['width' => 10],
            'B' => ['width' => 25],
            'C' => ['width' => 15],
            'D' => ['width' => 15],
            'E' => ['width' => 25],
            'F' => ['width' => 20],
            'G' => ['width' => 20],
            'H' => ['width' => 15],
            'I' => ['width' => 15],
            'J' => ['width' => 30],
            'K' => ['width' => 15],
            'L' => ['width' => 15],
            'M' => ['width' => 12],
            'N' => ['width' => 15],
            'O' => ['width' => 10],
            'P' => ['width' => 20],
        ];
    }
}