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

        // Get all entreprises to distribute CNSS declarations among them
        $entreprises = Entreprise::all();
        $entrepriseCount = $entreprises->count();
        
        // Add 16 more records to reach 20 total
        for ($i = 5; $i <= 20; $i++) {
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
