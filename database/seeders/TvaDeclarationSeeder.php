<?php

namespace Database\Seeders;

use App\Models\TvaDeclaration;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TvaDeclarationSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = Carbon::now()->year;

        // Monthly declarations for the past 6 months
        for ($i = 1; $i <= 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            TvaDeclaration::create([
                'entreprise_id' => rand(1, 2),
                'type' => 'mensuelle',
                'periode' => $date->format('Y-m'),
                'montant' => rand(1000, 50000),
                'date_declaration' => $date->format('Y-m-d'),
            ]);
        }

        // Quarterly declarations for current year
        for ($quarter = 1; $quarter <= 2; $quarter++) {
            TvaDeclaration::create([
                'entreprise_id' => rand(1, 2),
                'type' => 'trimestrielle',
                'periode' => $currentYear . '-Q' . $quarter,
                'montant' => rand(5000, 150000),
                'date_declaration' => Carbon::create($currentYear, $quarter * 3, 1)->format('Y-m-d'),
            ]);
        }

        // Annual declarations for past 2 years
        for ($year = $currentYear - 1; $year <= $currentYear; $year++) {
            TvaDeclaration::create([
                'entreprise_id' => rand(1, 2),
                'type' => 'annuelle',
                'periode' => (string)$year,
                'montant' => rand(20000, 500000),
                'date_declaration' => Carbon::create($year, 12, 31)->format('Y-m-d'),
            ]);
        }
    }
}
