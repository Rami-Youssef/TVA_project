<?php

namespace App\Exports;

use App\Models\Entreprise; // Suivi is based on Entreprises
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuiviExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function query()
    {
        $query = Entreprise::query(); // Suivi page lists entreprises

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('ice', 'like', "%{$this->search}%")
                  ->orWhere('activite_principale', 'like', "%{$this->search}%");
            });
        }
        // Add any other specific filtering for the Suivi page if necessary

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom de l\'entreprise',
            'ICE',
            'Activité Principale',
            'Siège Social',
            'Téléphone',
            'Email',
            // Add more headings if you display more info on the Suivi index before clicking an entreprise
        ];
    }

    public function map($entreprise): array
    {
        return [
            $entreprise->id,
            $entreprise->nom,
            $entreprise->ice,
            $entreprise->activite_principale,
            $entreprise->siege_social,
            $entreprise->telephone,
            $entreprise->email,
            // Map additional fields if needed
        ];
    }
}
