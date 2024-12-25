<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InjuryType;
use Illuminate\Database\Seeder;

class InjuryTypeSeeder extends Seeder
{
    public function run(): void
    {
        $injuries = [
            // Mindre skador (1-14 dagar)
            [
                'name' => 'muscle_strain_minor',
                'min_days' => 3,
                'max_days' => 7,
                'severity' => 'minor'
            ],
            [
                'name' => 'twisted_ankle',
                'min_days' => 5,
                'max_days' => 14,
                'severity' => 'minor'
            ],
            [
                'name' => 'thigh_contusion',
                'min_days' => 3,
                'max_days' => 10,
                'severity' => 'minor'
            ],

            // Måttliga skador (14-45 dagar)
            [
                'name' => 'achilles_tendinitis',
                'min_days' => 14,
                'max_days' => 28,
                'severity' => 'moderate'
            ],
            [
                'name' => 'knee_injury',
                'min_days' => 21,
                'max_days' => 42,
                'severity' => 'moderate'
            ],
            [
                'name' => 'groin_strain',
                'min_days' => 14,
                'max_days' => 35,
                'severity' => 'moderate'
            ],

            // Allvarliga skador (45+ dagar)
            [
                'name' => 'acl_injury',
                'min_days' => 180,
                'max_days' => 270,
                'severity' => 'severe'
            ],
            [
                'name' => 'fracture',
                'min_days' => 60,
                'max_days' => 120,
                'severity' => 'severe'
            ],
            [
                'name' => 'meniscus_tear',
                'min_days' => 45,
                'max_days' => 90,
                'severity' => 'severe'
            ]
        ];

        foreach ($injuries as $injury) {
            InjuryType::create($injury);
        }
    }
}
