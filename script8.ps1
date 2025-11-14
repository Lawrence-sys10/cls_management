# Step 8: Generate Excel Exports, PDF Reports, Services and Additional Components
# Save this as generate-exports-services.ps1 and run from project root

# Create necessary directories
$exportsPath = "app/Exports"
$servicesPath = "app/Services"
$pdfPath = "resources/views/pdf"
$notificationsPath = "app/Notifications"
$componentsPath = "resources/views/components"

@($exportsPath, $servicesPath, $pdfPath, $notificationsPath, $componentsPath) | ForEach-Object {
    if (!(Test-Path $_)) {
        New-Item -ItemType Directory -Path $_ -Force
    }
}

# 1. Lands Excel Export
@'
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
'@ | Out-File -FilePath "$exportsPath/LandsExport.php" -Encoding UTF8

# 2. Clients Excel Export
@'
<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Client::withCount(''allocations'');

        if (isset($this->filters[''search'']) && $this->filters[''search'']) {
            $query->where(function($q) {
                $q->where(''full_name'', ''like'', ''%'' . $this->filters[''search''] . ''%'')
                  ->orWhere(''phone'', ''like'', ''%'' . $this->filters[''search''] . ''%'')
                  ->orWhere(''id_number'', ''like'', ''%'' . $this->filters[''search''] . ''%'');
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            ''Full Name'',
            ''Phone Number'',
            ''Email'',
            ''ID Type'',
            ''ID Number'',
            ''Occupation'',
            ''Address'',
            ''Date of Birth'',
            ''Gender'',
            ''Emergency Contact'',
            ''Total Allocations'',
            ''Registration Date'',
        ];
    }

    public function map($client): array
    {
        return [
            $client->full_name,
            $client->phone,
            $client->email ?? ''N/A'',
            ucfirst(str_replace(''_'', '' '', $client->id_type)),
            $client->id_number,
            $client->occupation,
            $client->address,
            $client->date_of_birth ? $client->date_of_birth->format(''Y-m-d'') : ''N/A'',
            $client->gender ? ucfirst($client->gender) : ''N/A'',
            $client->emergency_contact ?? ''N/A'',
            $client->allocations_count,
            $client->created_at->format(''Y-m-d''),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [''font'' => [''bold'' => true]],
            ''A1:L1'' => [
                ''fill'' => [
                    ''fillType'' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    ''color'' => [''argb'' => ''FFF0F8FF'']
                ]
            ],
        ];
    }
}
'@ | Out-File -FilePath "$exportsPath/ClientsExport.php" -Encoding UTF8

# 3. Lands Report Export
@'
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
'@ | Out-File -FilePath "$exportsPath/LandsReportExport.php" -Encoding UTF8

# 4. Allocation Report Export
@'
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
'@ | Out-File -FilePath "$exportsPath/AllocationsReportExport.php" -Encoding UTF8

# 5. PDF Allocation Letter View
@'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Allocation Letter - {{ $allocation->land->plot_number }}</title>
    <style>
        body {
            font-family: ''DejaVu Sans'', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
        }
        .subtitle {
            font-size: 14px;
            color: #718096;
        }
        .content {
            margin: 30px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 300px;
            margin-top: 40px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #e2e8f0;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">TECHIMAN CUSTOMARY LANDS SECRETARIAT</div>
        <div class="subtitle">Customary Lands Management System</div>
        <div class="subtitle">Techiman Traditional Council</div>
    </div>

    <div class="content">
        <h2 style="text-align: center; color: #2d3748;">LAND ALLOCATION CERTIFICATE</h2>
        
        <div class="section">
            <p><strong>Certificate Number:</strong> CLS/{{ $allocation->id }}/{{ date(''Y'') }}</p>
            <p><strong>Date of Issue:</strong> {{ $allocation->allocation_date->format(''F d, Y'') }}</p>
        </div>

        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">ALLOCATION DETAILS</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; width: 30%;"><strong>Plot Number:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->land->plot_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Location:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->land->location }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Area:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        {{ number_format($allocation->land->area_acres, 2) }} acres 
                        ({{ number_format($allocation->land->area_hectares, 2) }} hectares)
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Land Use:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ ucfirst($allocation->land->land_use) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">CLIENT INFORMATION</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; width: 30%;"><strong>Full Name:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->client->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>ID Number:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        {{ ucfirst(str_replace(''_'', '' '', $allocation->client->id_type)) }}: {{ $allocation->client->id_number }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Phone:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->client->phone }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Occupation:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->client->occupation }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">APPROVAL INFORMATION</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; width: 30%;"><strong>Approving Chief:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->chief->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Jurisdiction:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->chief->jurisdiction }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Processed By:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->processedBy->user->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Status:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        <span class="badge">{{ ucfirst($allocation->approval_status) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        @if($allocation->notes)
        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">ADDITIONAL NOTES</h3>
            <p>{{ $allocation->notes }}</p>
        </div>
        @endif
    </div>

    <div class="signature-section">
        <div style="float: left; width: 45%;">
            <div class="signature-line"></div>
            <p><strong>{{ $allocation->chief->name }}</strong><br>
            Approving Chief<br>
            {{ $allocation->chief->jurisdiction }}</p>
        </div>
        
        <div style="float: right; width: 45%;">
            <div class="signature-line"></div>
            <p><strong>Registrar</strong><br>
            Techiman Customary Lands Secretariat<br>
            Techiman Traditional Council</p>
        </div>
        
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        <p>This is a computer-generated document. No physical signature is required.</p>
        <p>Generated on: {{ date(''F d, Y \\a\\t H:i'') }}</p>
        <p>CLS Management System - Techiman Customary Lands Secretariat</p>
    </div>
</body>
</html>
'@ | Out-File -FilePath "$pdfPath/allocation-letter.blade.php" -Encoding UTF8

# 6. GIS Service Class
@'
<?php

namespace App\Services;

use App\Models\Land;
use App\Models\Chief;
use Illuminate\Support\Facades\Http;

class GisService
{
    /**
     * Get land coordinates from location using Geocoding API
     */
    public function geocodeLocation(string $location): ?array
    {
        try {
            // Using OpenStreetMap Nominatim API
            $response = Http::timeout(10)->get(''https://nominatim.openstreetmap.org/search'', [
                ''q'' => $location . '', Techiman, Ghana'',
                ''format'' => ''json'',
                ''limit'' => 1
            ]);

            if ($response->successful() && !empty($response->json())) {
                $data = $response->json()[0];
                return [
                    ''latitude'' => $data[''lat''],
                    ''longitude'' => $data[''lon''],
                ];
            }
        } catch (\Exception $e) {
            \Log::error(''Geocoding failed for location: '' . $location, [''error'' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Generate GeoJSON for all lands
     */
    public function generateLandsGeoJson(): array
    {
        $lands = Land::with(''chief'')
            ->whereNotNull(''latitude'')
            ->whereNotNull(''longitude'')
            ->get();

        $features = [];

        foreach ($lands as $land) {
            $features[] = [
                ''type'' => ''Feature'',
                ''geometry'' => [
                    ''type'' => ''Point'',
                    ''coordinates'' => [(float) $land->longitude, (float) $land->latitude]
                ],
                ''properties'' => [
                    ''id'' => $land->id,
                    ''plot_number'' => $land->plot_number,
                    ''location'' => $land->location,
                    ''status'' => $land->ownership_status,
                    ''area_acres'' => $land->area_acres,
                    ''chief'' => $land->chief->name,
                    ''popupContent'' => $this->generatePopupContent($land)
                ]
            ];
        }

        return [
            ''type'' => ''FeatureCollection'',
            ''features'' => $features
        ];
    }

    /**
     * Generate popup content for map markers
     */
    private function generatePopupContent(Land $land): string
    {
        return ""
            <div style=''min-width: 200px;''>
                <h4 style=''margin: 0 0 8px 0; color: #2d3748;''><strong>{$land->plot_number}</strong></h4>
                <p style=''margin: 4px 0;''><strong>Location:</strong> {$land->location}</p>
                <p style=''margin: 4px 0;''><strong>Area:</strong> "" . number_format($land->area_acres, 2) . "" acres</p>
                <p style=''margin: 4px 0;''><strong>Chief:</strong> {$land->chief->name}</p>
                <p style=''margin: 4px 0;''>
                    <strong>Status:</strong> 
                    <span style=''padding: 2px 6px; border-radius: 12px; font-size: 12px; 
                        background: "" . $this->getStatusColor($land->ownership_status) . ""; 
                        color: white;''>
                        "" . ucfirst(str_replace(''_'', '' '', $land->ownership_status)) . ""
                    </span>
                </p>
                <div style=''margin-top: 8px;''>
                    <a href=''/lands/{$land->id}'' 
                       style=''display: inline-block; padding: 4px 12px; background: #3182ce; 
                              color: white; text-decoration: none; border-radius: 4px; font-size: 12px;''>
                        View Details
                    </a>
                </div>
            </div>
        "";
    }

    /**
     * Get color for land status
     */
    private function getStatusColor(string $status): string
    {
        return match($status) {
            ''vacant'' => ''#38a169'',
            ''allocated'' => ''#3182ce'',
            ''under_dispute'' => ''#e53e3e'',
            ''reserved'' => ''#d69e2e'',
            default => ''#a0aec0''
        };
    }

    /**
     * Calculate distance between two coordinates in kilometers
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth''s radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    /**
     * Find lands within radius of a point
     */
    public function findLandsWithinRadius(float $centerLat, float $centerLon, float $radiusKm)
    {
        $lands = Land::whereNotNull(''latitude'')
            ->whereNotNull(''longitude'')
            ->get();

        return $lands->filter(function ($land) use ($centerLat, $centerLon, $radiusKm) {
            $distance = $this->calculateDistance(
                $centerLat, $centerLon, 
                $land->latitude, $land->longitude
            );
            return $distance <= $radiusKm;
        });
    }
}
'@ | Out-File -FilePath "$servicesPath/GisService.php" -Encoding UTF8

# 7. Notification Service
@'
<?php

namespace App\Services;

use App\Models\User;
use App\Models\Allocation;
use App\Notifications\AllocationApprovedNotification;
use App\Notifications\AllocationPendingNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send allocation approval notification
     */
    public function sendAllocationApprovedNotification(Allocation $allocation): void
    {
        try {
            // Notify the client (if they have an email)
            if ($allocation->client->email) {
                Notification::route(''mail'', $allocation->client->email)
                    ->notify(new AllocationApprovedNotification($allocation));
            }

            // Notify relevant staff
            $staffUsers = User::whereHas(''roles'', function ($query) {
                $query->whereIn(''name'', [''admin'', ''registrar'']);
            })->get();

            Notification::send($staffUsers, new AllocationApprovedNotification($allocation));

            \Log::info(""Allocation approved notifications sent for allocation #{$allocation->id}"");

        } catch (\Exception $e) {
            \Log::error(''Failed to send allocation approved notification: '' . $e->getMessage());
        }
    }

    /**
     * Send allocation pending notification to chief
     */
    public function sendAllocationPendingNotification(Allocation $allocation): void
    {
        try {
            // Notify the chief (if they have a user account)
            if ($allocation->chief->user) {
                Notification::send(
                    $allocation->chief->user, 
                    new AllocationPendingNotification($allocation)
                );
            }

            \Log::info(""Allocation pending notification sent to chief for allocation #{$allocation->id}"");

        } catch (\Exception $e) {
            \Log::error(''Failed to send allocation pending notification: '' . $e->getMessage());
        }
    }

    /**
     * Send SMS notification (placeholder for Twilio integration)
     */
    public function sendSmsNotification(string $phoneNumber, string $message): bool
    {
        try {
            // This would integrate with Twilio or another SMS provider
            // For now, we''ll log the SMS that would be sent
            \Log::info(""SMS to {$phoneNumber}: {$message}"");

            return true;

        } catch (\Exception $e) {
            \Log::error(''Failed to send SMS notification: '' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk notifications for pending approvals
     */
    public function sendPendingApprovalsReminder(): void
    {
        $pendingAllocations = Allocation::where(''approval_status'', ''pending'')
            ->with([''chief'', ''land''])
            ->get();

        foreach ($pendingAllocations as $allocation) {
            // Send reminder to chief
            if ($allocation->chief->user) {
                $this->sendAllocationPendingNotification($allocation);
            }
        }

        \Log::info(""Sent pending approvals reminder for {$pendingAllocations->count()} allocations"");
    }
}
'@ | Out-File -FilePath "$servicesPath/NotificationService.php" -Encoding UTF8

# 8. Allocation Approved Notification
@'
<?php

namespace App\Notifications;

use App\Models\Allocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AllocationApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Allocation $allocation)
    {
    }

    public function via($notifiable): array
    {
        return [''mail''];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(''Land Allocation Approved - Techiman Customary Lands'')
            ->greeting(''Hello '' . $notifiable->name . ''!'')
            ->line(''We are pleased to inform you that your land allocation has been approved.'')
            ->line(''**Allocation Details:**'')
            ->line(''- Plot Number: '' . $this->allocation->land->plot_number)
            ->line(''- Location: '' . $this->allocation->land->location)
            ->line(''- Area: '' . number_format($this->allocation->land->area_acres, 2) . '' acres'')
            ->line(''- Approved Chief: '' . $this->allocation->chief->name)
            ->action(''View Allocation Details'', url(''/allocations/'' . $this->allocation->id))
            ->line(''Please visit the Lands Secretariat to complete the necessary documentation.'')
            ->salutation(''Regards, Techiman Customary Lands Secretariat'');
    }

    public function toArray($notifiable): array
    {
        return [
            ''allocation_id'' => $this->allocation->id,
            ''plot_number'' => $this->allocation->land->plot_number,
            ''message'' => ''Your land allocation has been approved.'',
        ];
    }
}
'@ | Out-File -FilePath "$notificationsPath/AllocationApprovedNotification.php" -Encoding UTF8

# 9. Additional Blade Components
@'
@props([''land''])

<div class="bg-white border border-gray-200 rounded-lg p-4">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $land->plot_number }}</h4>
            <div class="space-y-1 text-sm text-gray-600">
                <p><strong>Location:</strong> {{ $land->location }}</p>
                <p><strong>Area:</strong> {{ number_format($land->area_acres, 2) }} acres</p>
                <p><strong>Chief:</strong> {{ $land->chief->name }}</p>
                <p>
                    <strong>Status:</strong> 
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $land->ownership_status == ''vacant'' ? ''bg-green-100 text-green-800'' : 
                           ($land->ownership_status == ''allocated'' ? ''bg-blue-100 text-blue-800'' : ''bg-orange-100 text-orange-800'') }}">
                        {{ ucfirst(str_replace(''_'', '' '', $land->ownership_status)) }}
                    </span>
                </p>
            </div>
        </div>
        <div class="ml-4 flex-shrink-0">
            <a href="{{ route(''lands.show'', $land) }}" 
               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View
            </a>
        </div>
    </div>
</div>
'@ | Out-File -FilePath "$componentsPath/land-card.blade.php" -Encoding UTF8

@'
@props([''client''])

<div class="bg-white border border-gray-200 rounded-lg p-4">
    <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600"></i>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">
                {{ $client->full_name }}
            </p>
            <p class="text-sm text-gray-500 truncate">
                {{ $client->phone }}
            </p>
            <p class="text-xs text-gray-400 truncate">
                {{ ucfirst(str_replace(''_'', '' '', $client->id_type)) }}: {{ $client->id_number }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $client->allocations_count }} allocations
            </span>
        </div>
    </div>
</div>
'@ | Out-File -FilePath "$componentsPath/client-card.blade.php" -Encoding UTF8

Write-Host "✅ Excel exports, PDF reports, and services generated successfully!" -ForegroundColor Green
Write-Host "📁 Files created:" -ForegroundColor Cyan
Write-Host "   - app/Exports/LandsExport.php" -ForegroundColor White
Write-Host "   - app/Exports/ClientsExport.php" -ForegroundColor White
Write-Host "   - app/Exports/LandsReportExport.php" -ForegroundColor White
Write-Host "   - app/Exports/AllocationsReportExport.php" -ForegroundColor White
Write-Host "   - resources/views/pdf/allocation-letter.blade.php" -ForegroundColor White
Write-Host "   - app/Services/GisService.php" -ForegroundColor White
Write-Host "   - app/Services/NotificationService.php" -ForegroundColor White
Write-Host "   - app/Notifications/AllocationApprovedNotification.php" -ForegroundColor White
Write-Host "   - resources/views/components/land-card.blade.php" -ForegroundColor White
Write-Host "   - resources/views/components/client-card.blade.php" -ForegroundColor White
Write-Host "🚀 Next step: We'll create database seeders, configuration files, and deployment scripts" -ForegroundColor Yellow