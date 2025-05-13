<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CnssExport implements FromCollection, WithHeadings, WithMapping
{
    protected $declarations;

    public function __construct($declarations)
    {
        $this->declarations = $declarations;
    }

    public function collection()
    {
        return $this->declarations;
    }

    public function headings(): array
    {
        return [
            'Entreprise',
            'Mois',
            'Année',
            'Nombre de Salariés',
            'État',
        ];
    }

    public function map($declaration): array
    {
        return [
            $declaration->entreprise->nom ?? 'N/A',
            $declaration->french_month,
            $declaration->annee,
            $declaration->Nbr_Salries ?? 'Non déclaré',
            $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré',
        ];
    }
}