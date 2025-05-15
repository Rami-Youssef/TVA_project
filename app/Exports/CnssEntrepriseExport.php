<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CnssEntrepriseExport implements FromCollection, WithHeadings, WithMapping
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
            'Mois',
            'Année',
            'Nombre de Salariés',
            'État',
        ];
    }    public function map($declaration): array
    {
        return [
            $declaration['Mois'],
            $declaration['Année'],
            $declaration['Nombre de Salariés'],
            $declaration['État'],
        ];
    }
}
