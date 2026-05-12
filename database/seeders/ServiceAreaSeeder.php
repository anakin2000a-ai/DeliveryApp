<?php

namespace Database\Seeders;

use App\Models\ServiceArea;
use Illuminate\Database\Seeder;
use RuntimeException;

class ServiceAreaSeeder extends Seeder
{
    public function run(): void
    {
        $polygonPath = database_path('seeders/data/polygon_33775.json');

        if (! file_exists($polygonPath)) {
            throw new RuntimeException('polygon_33775.json file not found.');
        }

        $polygon = json_decode(file_get_contents($polygonPath), true);

        if (! is_array($polygon) || count($polygon) < 3) {
            throw new RuntimeException('Invalid polygon_33775.json file.');
        }

        ServiceArea::updateOrCreate(
            [
                'postal_code' => '33775',
                'city' => 'Versmold',
                'country' => 'Germany',
            ],
            [
                'name' => 'Versmold',
                'center_lat' => 52.0401,
                'center_lng' => 8.1527,
                'radius_km' => null,
                'polygon' => $polygon,
                'is_active' => true,
            ]
        );
    }
}