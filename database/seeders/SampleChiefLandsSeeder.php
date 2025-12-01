<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chief;
use App\Models\Land;
use Illuminate\Support\Facades\Log;

class SampleChiefLandsSeeder extends Seeder
{
    public function run()
    {
        // Find or create the chief
        $chief = Chief::where('name', 'Nana Yaw Boateng')->first();
        
        if (!$chief) {
            echo "❌ Chief Nana Yaw Boateng not found. Creating chief first...\n";
            
            $chief = Chief::create([
                'name' => 'Nana Yaw Boateng',
                'region' => 'Ashanti Region',
                'district' => 'Kumasi Metropolitan',
                'stool' => 'Asante Stool',
                'email' => 'nana.boateng@example.com',
                'phone' => '0241000000'
            ]);
            echo "✓ Created chief: {$chief->name} with ID: {$chief->id}\n";
        } else {
            echo "✓ Found existing chief: {$chief->name} (ID: {$chief->id})\n";
        }

        echo "✓ Creating lands for chief: {$chief->name} (ID: {$chief->id})\n";

        $lands = [
            [
                'plot_number' => 'PLOT-001',
                'location' => 'East District, Block A',
                'area_acres' => 2.5,
                'area_hectares' => 1.011715,
                'land_use' => 'residential',
                'ownership_status' => 'vacant',
                'registration_date' => now()
            ],
            [
                'plot_number' => 'PLOT-002',
                'location' => 'West District, Block B',
                'area_acres' => 5,
                'area_hectares' => 2.02343,
                'land_use' => 'commercial',
                'ownership_status' => 'vacant',
                'registration_date' => now()
            ],
            [
                'plot_number' => 'PLOT-003',
                'location' => 'North District, Block C',
                'area_acres' => 10,
                'area_hectares' => 4.04686,
                'land_use' => 'agricultural',
                'ownership_status' => 'vacant',
                'registration_date' => now()
            ],
            [
                'plot_number' => 'PLOT-004',
                'location' => 'South District, Block D',
                'area_acres' => 3,
                'area_hectares' => 1.214058,
                'land_use' => 'residential',
                'ownership_status' => 'vacant',
                'registration_date' => now()
            ],
            [
                'plot_number' => 'PLOT-005',
                'location' => 'Central District, Block E',
                'area_acres' => 7.5,
                'area_hectares' => 3.035145,
                'land_use' => 'commercial',
                'ownership_status' => 'vacant',
                'registration_date' => now()
            ]
        ];

        $createdCount = 0;
        foreach ($lands as $landData) {
            try {
                // Check if land with this plot number already exists
                $existingLand = Land::where('plot_number', $landData['plot_number'])->first();
                
                if (!$existingLand) {
                    $chief->lands()->create($landData);
                    echo "✓ Created land: {$landData['plot_number']}\n";
                    $createdCount++;
                } else {
                    echo "ⓘ Skipped {$landData['plot_number']}: Plot number already exists\n";
                }
            } catch (\Exception $e) {
                echo "✗ Error creating {$landData['plot_number']}: {$e->getMessage()}\n";
                Log::error("Error creating land {$landData['plot_number']}: " . $e->getMessage());
            }
        }

        echo "\n✅ Successfully created {$createdCount} new lands for chief {$chief->name}\n";
        echo "📍 Chief ID: {$chief->id}\n";
    }
}