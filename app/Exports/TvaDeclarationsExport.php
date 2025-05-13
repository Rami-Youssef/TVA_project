<?php

namespace App\Exports;

use App\Models\TvaDeclaration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TvaDeclarationsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $periodeType; // e.g., 'mensuelle', 'trimestrielle', 'annuelle'
    protected $search;

    public function __construct(string $periodeType, $search = null)
    {
        $this->periodeType = $periodeType;
        $this->search = $search;
    }

    public function query()
    {
        $query = TvaDeclaration::query()->with('entreprise');

        // Filter by periode type based on the route
        if ($this->periodeType === 'mensuelle') {
            $query->where('type', 'mensuelle');
        } elseif ($this->periodeType === 'trimestrielle') {
            $query->where('type', 'trimestrielle');
        } elseif ($this->periodeType === 'annuelle') {
            $query->where('type', 'annuelle');
        }

        if ($this->search) {
            $query->whereHas('entreprise', function ($q) {
                $q->where('nom', 'like', "%{$this->search}%");
            });
        }

        // Add other relevant filters if needed, e.g., by year, specific period
        $query->orderBy('date_declaration', 'desc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Entreprise',
            'Période',
            'Type',
            'Montant HT',
            'Montant TVA',
            'Montant TTC',
            'Date de Déclaration',
            'Date de Paiement Prévue',
            'Statut Paiement',
        ];
    }

    public function map($declaration): array
    {
        return [
            $declaration->id,
            $declaration->entreprise->nom ?? 'N/A',
            $declaration->periode,
            $declaration->type,
            number_format($declaration->montant_ht, 2, ',', ' ') . ' MAD',
            number_format($declaration->montant_tva, 2, ',', ' ') . ' MAD',
            number_format($declaration->montant_ttc, 2, ',', ' ') . ' MAD',
            $declaration->date_declaration ? (new \Carbon\Carbon($declaration->date_declaration))->format('d/m/Y') : 'N/A',
            $declaration->date_paiement_prevue ? (new \Carbon\Carbon($declaration->date_paiement_prevue))->format('d/m/Y') : 'N/A',
            $declaration->statut_paiement,
        ];
    }
}
