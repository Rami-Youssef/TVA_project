<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example 1
        Entreprise::create([
            'nom' => 'TechnoCorp',
            'siege_social' => '123 Silicon Valley, San Francisco, CA',
            'form_juridique' => 'Société par Actions Simplifiée (SAS)',
            'activite_principale' => 'Développement de logiciels',
            'numero_societe' => '5678901234',
        ]);

        // Example 2
        Entreprise::create([
            'nom' => 'GreenEnergy Solutions',
            'siege_social' => '45 Green Road, Paris, France',
            'form_juridique' => 'Société à Responsabilité Limitée (SARL)',
            'activite_principale' => 'Production d’énergie renouvelable',
            'numero_societe' => '1234567890',
        ]);

        // Example 3
        Entreprise::create([
            'nom' => 'FinTech International',
            'siege_social' => '789 Financial Blvd, London, UK',
            'form_juridique' => 'Public Limited Company (PLC)',
            'activite_principale' => 'Technologies financières et paiements',
            'numero_societe' => '2345678901',
        ]);

        // Example 4
        Entreprise::create([
            'nom' => 'BuildIt Construction',
            'siege_social' => '987 Builders Street, Dubai, UAE',
            'form_juridique' => 'Limited Liability Company (LLC)',
            'activite_principale' => 'Construction et génie civil',
            'numero_societe' => '3456789012',
        ]);

        // Example 5
        Entreprise::create([
            'nom' => 'HealthCare Plus',
            'siege_social' => '12 Health Avenue, Berlin, Germany',
            'form_juridique' => 'Aktiengesellschaft (AG)',
            'activite_principale' => 'Services de santé et bien-être',
            'numero_societe' => '4567890123',
        ]);
    }
}
