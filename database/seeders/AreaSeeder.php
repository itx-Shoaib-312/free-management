<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                "ref" => "st-21-rawal-road",
                "name" => "Street # 21, Rawal Road",
            ],
            [
                "ref" => "kalma-chok",
                "name" => "Street # 21, Rawal Chok",
            ]
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate([
                'ref' => $area['ref'],
            ],$area);
        }
    }
}
