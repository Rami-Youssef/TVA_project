<?php

namespace Database\Seeders;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CnssSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keep the original 4 records
        Cnss::firstOrCreate(
            ['id' => 1],
            [
                'entreprise_id' => 1,
                'Mois' => now()->month,
                'annee' => now()->year,
                'Nbr_Salries' => 10,
                'etat' => 'valide',
            ]
        );
        
        Cnss::firstOrCreate(
            ['id' => 2],
            [
                'entreprise_id' => 2,
                'Mois' => now()->month,
                'annee' => now()->year,
                'Nbr_Salries' => 12,
                'etat' => 'valide',
            ]
        );
        
        Cnss::firstOrCreate(
            ['id' => 3],
            [
                'entreprise_id' => 2,
                'Mois' => now()->month - 1 > 0 ? now()->month - 1 : 12,
                'annee' => now()->month - 1 > 0 ? now()->year : now()->year - 1,
                'Nbr_Salries' => 15,
                'etat' => 'valide',
            ]
        );
        
        Cnss::firstOrCreate(
            ['id' => 4],
            [
                'entreprise_id' => 4,
                'Mois' => now()->month,
                'annee' => now()->year,
                'Nbr_Salries' => 20,
                'etat' => 'valide',
            ]
        );

        // Find Tech Solutions Maroc company (assuming it's ID 1)
        $techSolutionsId = Entreprise::where('nom', 'Tech Solutions Maroc')->first()->id ?? 1;
        
        // Create scattered CNSS declarations for Tech Solutions Maroc for 2023, 2024, and 2025
        // 2023 declarations (scattered with gaps)
        $techSolutionsData = [
            // 2023 - January, March, April, June, September, December (skipping others)
            ['id' => 5, 'entreprise_id' => $techSolutionsId, 'Mois' => 1, 'annee' => 2023, 'Nbr_Salries' => 8, 'etat' => 'valide'],
            ['id' => 6, 'entreprise_id' => $techSolutionsId, 'Mois' => 3, 'annee' => 2023, 'Nbr_Salries' => 9, 'etat' => 'valide'],
            ['id' => 7, 'entreprise_id' => $techSolutionsId, 'Mois' => 4, 'annee' => 2023, 'Nbr_Salries' => 9, 'etat' => 'valide'],
            ['id' => 8, 'entreprise_id' => $techSolutionsId, 'Mois' => 6, 'annee' => 2023, 'Nbr_Salries' => 10, 'etat' => 'valide'],
            ['id' => 9, 'entreprise_id' => $techSolutionsId, 'Mois' => 9, 'annee' => 2023, 'Nbr_Salries' => 11, 'etat' => 'valide'],
            ['id' => 10, 'entreprise_id' => $techSolutionsId, 'Mois' => 12, 'annee' => 2023, 'Nbr_Salries' => 12, 'etat' => 'valide'],
            
            // 2024 - February, March, May, July, October, November (skipping others)
            ['id' => 11, 'entreprise_id' => $techSolutionsId, 'Mois' => 2, 'annee' => 2024, 'Nbr_Salries' => 14, 'etat' => 'valide'],
            ['id' => 12, 'entreprise_id' => $techSolutionsId, 'Mois' => 3, 'annee' => 2024, 'Nbr_Salries' => 16, 'etat' => 'valide'],
            ['id' => 13, 'entreprise_id' => $techSolutionsId, 'Mois' => 5, 'annee' => 2024, 'Nbr_Salries' => 15, 'etat' => 'valide'],
            ['id' => 14, 'entreprise_id' => $techSolutionsId, 'Mois' => 7, 'annee' => 2024, 'Nbr_Salries' => 18, 'etat' => 'valide'],
            ['id' => 15, 'entreprise_id' => $techSolutionsId, 'Mois' => 10, 'annee' => 2024, 'Nbr_Salries' => 20, 'etat' => 'valide'],
            ['id' => 16, 'entreprise_id' => $techSolutionsId, 'Mois' => 11, 'annee' => 2024, 'Nbr_Salries' => 21, 'etat' => 'non_valide'], // Non-validated declaration
            
            // 2025 - January, February, April (skipping March, and others as it's only May 2025)
            ['id' => 17, 'entreprise_id' => $techSolutionsId, 'Mois' => 1, 'annee' => 2025, 'Nbr_Salries' => 22, 'etat' => 'valide'],
            ['id' => 18, 'entreprise_id' => $techSolutionsId, 'Mois' => 2, 'annee' => 2025, 'Nbr_Salries' => 24, 'etat' => 'valide'],
            ['id' => 19, 'entreprise_id' => $techSolutionsId, 'Mois' => 4, 'annee' => 2025, 'Nbr_Salries' => 25, 'etat' => 'valide'],
        ];
        
        foreach ($techSolutionsData as $data) {
            Cnss::firstOrCreate(
                ['entreprise_id' => $data['entreprise_id'], 'Mois' => $data['Mois'], 'annee' => $data['annee']],
                $data
            );
        }

        // Get all entreprises to distribute additional CNSS declarations among them (skip Tech Solutions)
        $entreprises = Entreprise::where('id', '!=', $techSolutionsId)->get();
        $entrepriseCount = $entreprises->count();
        
        // Add more records for other companies
        $startId = 20;
        $maxId = 35;
        
        for ($i = $startId; $i <= $maxId; $i++) {
            if ($entrepriseCount > 0) {
                $randomEntrepriseId = $entreprises[rand(0, $entrepriseCount-1)]->id;
                $randomMonth = rand(1, 12);
                $randomYear = rand(2023, 2025);
                $randomSalaries = rand(5, 100);
                $randomEtat = rand(0, 1) ? 'valide' : 'non_valide';
                
                Cnss::firstOrCreate(
                    ['id' => $i],
                    [
                        'entreprise_id' => $randomEntrepriseId,
                        'Mois' => $randomMonth,
                        'annee' => $randomYear,
                        'Nbr_Salries' => $randomSalaries,
                        'etat' => $randomEtat,
                    ]
                );
            }
        }
    }
}
