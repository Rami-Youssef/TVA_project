<?php

namespace App\Exports;

use App\Models\Entreprise;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntreprisesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $etatFilter;

    public function __construct($search = null, $etatFilter = null)
    {
        $this->search = $search;
        $this->etatFilter = $etatFilter;
    }

    public function query()
    {
        $query = Entreprise::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('ice', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('telephone', 'like', "%{$this->search}%");
            });
        }

        if ($this->etatFilter && $this->etatFilter !== 'all') {
            $query->whereHas('cnssDeclarations', function($q) {
                $q->where('etat', $this->etatFilter);
            });
        }

        return $query->orderBy('nom', 'asc');
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Siège Social',
            'Forme Juridique',
            'Activité Principale',
            'ICE',
            'Email',
            'Téléphone',
        ];
    }

    public function map($entreprise): array
    {
        return [
            $entreprise->nom,
            $entreprise->siege_social,
            $entreprise->form_juridique,
            $entreprise->activite_principale,
            $entreprise->ice,
            $entreprise->email,
            $entreprise->telephone,
        ];
    }
}
