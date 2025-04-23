<?php

namespace Database\Seeders;

use App\Models\Cnss;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CnssSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cnss::create([
            'id' => 1,
            'entreprise_id' => 1,
            'Mois' => now()->month,
            'annee' => now()->year,
            'Nbr_Salries' => 10,
            'etat' => 'valide',
        ]);
        Cnss::create([
            'id' => 2,
            'entreprise_id' => 2,
            'Mois' => now()->month,
            'annee' => now()->year,
            'Nbr_Salries' => 12,
            'etat' => 'valide',
        ]);
        Cnss::create([
            'id' => 3,
            'entreprise_id' => 2,
            'Mois' => now()->month,
            'annee' => now()->year,
            'Nbr_Salries' => 15,
            'etat' => 'valide',
        ]);
        Cnss::create([
            'id' => 4,
            'entreprise_id' => 4,
            'Mois' => now()->month,
            'annee' => now()->year,
            'Nbr_Salries' => 20,
            'etat' => 'valide',
        ]);
    }
}
