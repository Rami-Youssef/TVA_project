<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keep the original companies
        Entreprise::firstOrCreate(
            ['id' => 1],
            [
                'nom' => 'Tech Solutions Maroc',
                'siege_social' => 'Casablanca, Maroc',
                'form_juridique' => 'SARL',
                'activite_principale' => 'Développement informatique',
                'ice' => '001234567890123',
            ]
        );
        
        Entreprise::firstOrCreate(
            ['id' => 2],
            [
                'nom' => 'Maroc Consulting Group',
                'siege_social' => 'Rabat, Maroc',
                'form_juridique' => 'SA',
                'activite_principale' => 'Conseil en management',
                'ice' => '002345678901234',
            ]
        );
        
        Entreprise::firstOrCreate(
            ['id' => 3],
            [
                'nom' => 'Atlas Transport',
                'siege_social' => 'Marrakech, Maroc',
                'form_juridique' => 'SARL',
                'activite_principale' => 'Transport et logistique',
                'ice' => '003456789012345',
            ]
        );
        
        Entreprise::firstOrCreate(
            ['id' => 4],
            [
                'nom' => 'Maghreb Industries',
                'siege_social' => 'Tanger, Maroc',
                'form_juridique' => 'SA',
                'activite_principale' => 'Industrie manufacturière',
                'ice' => '004567890123456',
            ]
        );

        // Add 16 more companies to reach 20 total
        $companyNames = [
            'Maroc Digital Services', 'Atlas Construction', 'Sahara Textiles', 
            'Casablanca Food Processing', 'Maghreb Telecom', 'Fes Automotive Parts',
            'Marrakech Tourism Agency', 'Rabat Medical Supplies', 'Agadir Fishing Co.',
            'Tanger Shipping Lines', 'Moroccan Agricultural Exports', 'Tetouan Tech Academy',
            'Oujda Mining Corporation', 'Kenitra Electronics', 'Mohammedia Energy Solutions',
            'Essaouira Handicrafts'
        ];
        
        $cities = ['Casablanca', 'Rabat', 'Marrakech', 'Tanger', 'Fes', 'Agadir', 'Tetouan', 'Oujda', 'Kenitra', 'Mohammedia', 'Essaouira'];
        $legalForms = ['SARL', 'SA', 'SNC', 'SCS', 'SCA'];
        $activities = [
            'Commerce de détail', 'Services informatiques', 'Construction', 'Transport', 
            'Agroalimentaire', 'Textile', 'Tourisme', 'Santé', 'Éducation', 
            'Immobilier', 'Énergie', 'Télécommunications'
        ];

        for ($i = 5; $i <= 20; $i++) {
            $city = $cities[array_rand($cities)];
            $name = $companyNames[$i - 5];
            
            Entreprise::firstOrCreate(
                ['id' => $i],
                [
                    'nom' => $name,
                    'siege_social' => $city . ', Maroc',
                    'form_juridique' => $legalForms[array_rand($legalForms)],
                    'activite_principale' => $activities[array_rand($activities)],
                    'ice' => '00' . rand(1000000000000, 9999999999999),
                ]
            );
        }
    }
}
