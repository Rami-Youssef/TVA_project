<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise; // Though not directly queried, it's related via Cnss model
use Illuminate\Http\Request;
use App\Exports\EtatsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EtatsController extends Controller
{
    private function getFilteredCnssQuery(Request $request)
    {
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter');
        $sortBy = $request->input('sort_by');

        $query = Cnss::with('entreprise');

        if ($search) {
            $query->whereHas('entreprise', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }

        if ($etatFilter) {
            if ($etatFilter === 'valide') {
                $query->where('etat', 'valide');
            } elseif ($etatFilter === 'non_valide') {
                $query->where('etat', '!=', 'valide');
            }
        }

        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'cnss.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('cnss.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'cnss.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('cnss.*');
                    break;
                case 'date_asc':
                    $query->orderBy('annee', 'asc')->orderBy('mois', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('annee', 'desc')->orderBy('mois', 'desc');
                    break;
                default:
                    $query->orderBy('annee', 'desc')->orderBy('mois', 'desc');
                    break;
            }
        } else {
            $query->orderBy('annee', 'desc')->orderBy('mois', 'desc');
        }
        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredCnssQuery($request);
        $declarations = $query->paginate(10);

        $declarations->appends($request->all());
        
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter');
        $sortBy = $request->input('sort_by');

        return view('etats.index', compact('declarations', 'search', 'etatFilter', 'sortBy'));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->getFilteredCnssQuery($request);
        $declarations = $query->get();

        $pdf = Pdf::loadView('etats.pdf', compact('declarations'));
        return $pdf->download('etats-cnss-' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter');
        $sortBy = $request->input('sort_by');

        return Excel::download(new EtatsExport($search, $etatFilter, $sortBy), 'etats-cnss-' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}