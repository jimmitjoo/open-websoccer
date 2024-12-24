<?php

namespace Database\Seeders;

use App\Models\TrainingType;
use Illuminate\Database\Seeder;

class TrainingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Återhämtning',
                'code' => 'FR',
                'effects' => [
                    'stamina' => -2,
                    'freshness' => 5
                ],
                'intensity' => 1,
                'cost' => 1000
            ],
            [
                'name' => 'Teknikträning',
                'code' => 'TE',
                'effects' => [
                    'technique' => 2,
                    'stamina' => -1,
                    'freshness' => -1
                ],
                'intensity' => 2,
                'cost' => 2000
            ],
            [
                'name' => 'Konditionsträning',
                'code' => 'ST',
                'effects' => [
                    'stamina' => 3,
                    'freshness' => -2
                ],
                'intensity' => 3,
                'cost' => 2000
            ],
        ];

        foreach ($types as $type) {
            TrainingType::create($type);
        }
    }
} 