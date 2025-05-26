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
    protected $periodeFilter;
    protected $sortBy;

    public function __construct(string $periodeType, $search = null, $periodeFilter = null, $sortBy = null)
    {
        $this->periodeType = $periodeType;
        $this->search = $search;
        $this->periodeFilter = $periodeFilter;
        $this->sortBy = $sortBy;
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

        // Apply search filter
        if ($this->search) {
            $query->whereHas('entreprise', function ($q) {
                $q->where('nom', 'like', "%{$this->search}%");
            });
        }
        
        // Apply periode filter
        if ($this->periodeFilter) {
            $query->where('periode', $this->periodeFilter);
        }
        
        // Apply sorting
        if ($this->sortBy) {
            switch ($this->sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('tva_declarations.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('tva_declarations.*');
                    break;
                case 'periode_asc':
                    $query->orderBy('periode', 'asc');
                    break;
                case 'periode_desc':
                    $query->orderBy('periode', 'desc');
                    break;
                case 'montant_asc':
                    $query->orderBy('montant', 'asc');
                    break;
                case 'montant_desc':
                    $query->orderBy('montant', 'desc');
                    break;
                default:
                    $query->orderBy('date_declaration', 'desc');
                    break;
            }
        } else {
            $query->orderBy('date_declaration', 'desc');
        }

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
