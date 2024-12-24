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
                    'strength' => -1,
                    'form' => 5
                ],
                'intensity' => 1
            ],
            [
                'name' => 'Teknikträning',
                'code' => 'TE',
                'effects' => [
                    'technique' => 2,
                    'stamina' => -1,
                    'strength' => -1
                ],
                'intensity' => 2
            ],
            [
                'name' => 'Konditionsträning',
                'code' => 'ST',
                'effects' => [
                    'stamina' => 3,
                    'strength' => 1,
                    'form' => -2
                ],
                'intensity' => 3
            ],
        ];

        foreach ($types as $type) {
            TrainingType::create($type);
        }
    }
}
