<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ClientsExport
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    public function download()
    {
        $clients = $this->getClients();
        
        $fileName = 'clients-' . date('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to handle special characters
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($file, [
                'Full Name',
                'ID Type',
                'ID Number', 
                'Phone',
                'Email',
                'Occupation',
                'Address',
                'Total Allocations',
                'Registered Date',
            ], ',');
            
            // Add data
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->full_name,
                    $client->id_type ? ucfirst(str_replace('_', ' ', $client->id_type)) : 'N/A',
                    $client->id_number,
                    $client->phone,
                    $client->email ?? 'N/A',
                    $client->occupation ?? 'N/A',
                    $client->address ?? 'N/A',
                    $client->allocations_count,
                    $client->created_at->format('M j, Y H:i'),
                ], ',');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function getClients()
    {
        $query = Client::withCount('allocations');

        if ($this->request && $this->request->has('search') && $this->request->search) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('id_number', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}