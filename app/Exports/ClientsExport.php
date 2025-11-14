<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Client::withCount('allocations');

        if ($this->request && $this->request->has('search') && $this->request->search) {
            $query->where('full_name', 'like', '%' . $this->request->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->request->search . '%')
                  ->orWhere('id_number', 'like', '%' . $this->request->search . '%');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'ID Number',
            'Phone',
            'Email',
            'Address',
            'Total Allocations',
            'Registered Date',
        ];
    }

    public function map($client): array
    {
        return [
            $client->full_name,
            $client->id_number,
            $client->phone,
            $client->email ?? 'N/A',
            $client->address ?? 'N/A',
            $client->allocations_count,
            $client->created_at->format('Y-m-d H:i:s'),
        ];
    }
}