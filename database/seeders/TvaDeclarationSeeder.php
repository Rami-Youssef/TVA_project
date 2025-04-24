<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Models\TvaDeclaration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TvaDeclarationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keep the original TVA declarations
        TvaDeclaration::firstOrCreate(
            ['id' => 1],
            [
                'entreprise_id' => 1,
                'type' => 'mensuelle',
                'periode' => 'Avril 2025',
                'montant' => 12500.00,
                'date_declaration' => now(),
            ]
        );
        
        TvaDeclaration::firstOrCreate(
            ['id' => 2],
            [
                'entreprise_id' => 2,
                'type' => 'trimestrielle',
                'periode' => 'Q1 2025',
                'montant' => 37500.00,
                'date_declaration' => now(),
            ]
        );
        
        TvaDeclaration::firstOrCreate(
            ['id' => 3],
            [
                'entreprise_id' => 3,
                'type' => 'annuelle',
                'periode' => '2024',
                'montant' => 150000.00,
                'date_declaration' => now(),
            ]
        );
        
        TvaDeclaration::firstOrCreate(
            ['id' => 4],
            [
                'entreprise_id' => 4,
                'type' => 'mensuelle',
                'periode' => 'Mars 2025',
                'montant' => 18750.00,
                'date_declaration' => Carbon::now()->subMonth(),
            ]
        );

        // Get all entreprises to distribute TVA declarations among them
        $entreprises = Entreprise::all();
        $entrepriseCount = $entreprises->count();
        
        // Types of declarations
        $types = ['mensuelle', 'trimestrielle', 'annuelle'];

        // Month names in French
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        // Quarters
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        
        // Years
        $years = [2023, 2024, 2025];
        
        // Add 16 more records to reach 20 total
        for ($i = 5; $i <= 20; $i++) {
            $randomEntrepriseId = $entreprises[rand(0, $entrepriseCount-1)]->id;
            $type = $types[array_rand($types)];
            $year = $years[array_rand($years)];
            $montant = rand(5000, 200000) / 100 * 100; // Round to nearest 100
            
            // Generate period based on type
            if ($type === 'mensuelle') {
                $month = array_rand($months) + 1;
                $periode = $months[$month] . ' ' . $year;
                $date_declaration = Carbon::createFromDate($year, $month, rand(1, 28));
            } elseif ($type === 'trimestrielle') {
                $quarter = array_rand($quarters);
                $periode = $quarters[$quarter] . ' ' . $year;
                $month = ($quarter + 1) * 3;
                $date_declaration = Carbon::createFromDate($year, $month, rand(1, 28));
            } else { // annuelle
                $periode = (string) $year;
                $date_declaration = Carbon::createFromDate($year, 12, rand(1, 28));
            }
            
            TvaDeclaration::firstOrCreate(
                ['id' => $i],
                [
                    'entreprise_id' => $randomEntrepriseId,
                    'type' => $type,
                    'periode' => $periode,
                    'montant' => $montant,
                    'date_declaration' => $date_declaration,
                ]
            );
        }
    }
}
