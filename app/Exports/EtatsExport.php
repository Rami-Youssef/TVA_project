<?php

namespace App\Exports;

use App\Models\Cnss;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth; // To access authenticated user for potential filtering

class EtatsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $etatFilter;
    protected $sortBy;

    public function __construct($search = null, $etatFilter = null, $sortBy = null)
    {
        $this->search = $search;
        $this->etatFilter = $etatFilter;
        $this->sortBy = $sortBy;
    }

    public function query()
    {
        $query = Cnss::query()->with('entreprise');

        if ($this->search) {
            $query->whereHas('entreprise', function ($q) {
                $q->where('nom', 'like', "%{$this->search}%");
            });
        }

        if ($this->etatFilter) {
            if ($this->etatFilter === 'valide') {
                $query->where('etat', 'valide');
            } elseif ($this->etatFilter === 'non_valide') {
                $query->where('etat', '!= ', 'valide'); // Or however non_valide is stored
            }
        }

        if ($this->sortBy) {
            switch ($this->sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'cnss.entreprise_id', '=', 'entreprises.id')->orderBy('entreprises.nom', 'asc')->select('cnss.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'cnss.entreprise_id', '=', 'entreprises.id')->orderBy('entreprises.nom', 'desc')->select('cnss.*');
                    break;
                case 'date_asc':
                    $query->orderBy('annee', 'asc')->orderBy('mois', 'asc'); // Assuming mois is stored numerically or can be sorted
                    break;
                case 'date_desc':
                    $query->orderBy('annee', 'desc')->orderBy('mois', 'desc');
                    break;
            }
        }
        // Default order if no sort_by is specified
        if (!$this->sortBy) {
            $query->orderBy('annee', 'desc')->orderBy('mois', 'desc');
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Entreprise',
            'Mois',
            'Année',
            'Nombre de Salariés',
            'Montant Total Salaires',
            'Montant CNSS',
            'État',
        ];
    }

    public function map($cnss): array
    {
        return [
            $cnss->id,
            $cnss->entreprise->nom ?? 'N/A',
            $cnss->french_month, // Assuming you have an accessor for this
            $cnss->annee,
            $cnss->Nbr_Salries,
            number_format($cnss->Tot_Salaires, 2, ',', ' ') . ' MAD',
            number_format($cnss->Mnt_Cnss, 2, ',', ' ') . ' MAD',
            $cnss->etat === 'valide' ? 'Déclaré' : 'Non déclaré',
        ];
    }
}
