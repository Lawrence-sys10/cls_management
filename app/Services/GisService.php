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
