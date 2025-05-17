<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\SuiviExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SuiviController extends Controller
{
    private function getFilteredEntreprisesQuery(Request $request)
    {
        $search = $request->input('search');
        $query = Entreprise::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('ice', 'like', "%{$search}%")
                  ->orWhere('activite_principale', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = $this->getFilteredEntreprisesQuery($request);
        $entreprises = $query->paginate(10);
        
        if ($search) {
            $entreprises->appends(['search' => $search]);
        }
        
        return view('suivi.index', compact('entreprises', 'search'));
    }
    
    public function show(Request $request, $entrepriseId)
    {
        $entreprise = Entreprise::findOrFail($entrepriseId);
        $etat_filter = $request->input('etat_filter', 'all');
        $year_filter = $request->input('year_filter', 'all');
        
        $query = Cnss::where('entreprise_id', $entrepriseId);
        
        // Apply état filter
        if ($etat_filter != 'all') {
            $query->where('etat', $etat_filter);
        }
        
        // Apply year filter
        if ($year_filter != 'all') {
            $query->where('annee', $year_filter);
        }
        
        // Get filtered declarations for pagination
        $declarations = $query->orderBy('annee', 'desc')
                             ->orderBy('Mois', 'desc')
                             ->paginate(10);
        
        // Get years for the year filter dropdown
        $years = Cnss::where('entreprise_id', $entrepriseId)
                    ->select('annee')
                    ->distinct()
                    ->orderBy('annee', 'desc')
                    ->pluck('annee')
                    ->toArray();
        
        // Apply request parameters to pagination links
        $declarations->appends($request->all());
        
        // Get all declarations for the chart (unfiltered for complete view)
        $allDeclarations = Cnss::where('entreprise_id', $entrepriseId)
            ->orderBy('annee', 'asc')
            ->orderBy('Mois', 'asc')
            ->get();
        
        $chartData = $this->prepareMonthlyChartData($allDeclarations);
        
        return view('suivi.show', compact(
            'declarations', 
            'entreprise', 
            'chartData',
            'etat_filter',
            'year_filter',
            'years'
        ));
    }
    
    private function prepareMonthlyChartData($declarations)
    {
        if ($declarations->isEmpty()) {
            return [];
        }
        
        $firstDate = null;
        $lastDate = Carbon::now();
        
        foreach ($declarations as $declaration) {
            $declarationDate = Carbon::createFromDate($declaration->annee, $declaration->Mois, 1);
            
            if ($firstDate === null || $declarationDate->lt($firstDate)) {
                $firstDate = $declarationDate;
            }
        }
        
        if ($firstDate === null) {
            return [];
        }
        
        $declarationsByMonth = [];
        foreach ($declarations as $declaration) {
            $key = $declaration->annee . '-' . str_pad($declaration->Mois, 2, '0', STR_PAD_LEFT);
            $declarationsByMonth[$key] = $declaration->Nbr_Salries ?? 0;
        }
        
        $result = [];
        $currentDate = clone $firstDate;
        
        while ($currentDate->lte($lastDate)) {
            $key = $currentDate->format('Y-m');
            $timestamp = $currentDate->timestamp * 1000;
            $count = $declarationsByMonth[$key] ?? 0;
            
            $result[] = [$timestamp, $count];
            
            $currentDate->addMonth();
        }
        
        return $result;
    }

    
    public function exportEnreprisePdf(Request $request, $id)
    {
        $entrepriseId = $id;
        $entreprise = Entreprise::findOrFail($entrepriseId);
        $etat_filter = $request->input('etat_filter', 'all');
        $year_filter = $request->input('year_filter', 'all');
        
        $query = Cnss::where('entreprise_id', $entrepriseId);
        
        // Apply état filter
        if ($etat_filter != 'all') {
            $query->where('etat', $etat_filter);
        }
        
        // Apply year filter
        if ($year_filter != 'all') {
            $query->where('annee', $year_filter);
        }
        
        $declarations = $query->orderBy('annee', 'desc')
                              ->orderBy('Mois', 'desc')
                              ->get();
            
        $pdf = Pdf::loadView('suivi.entreprise-pdf', compact('entreprise', 'declarations'));
        return $pdf->download('declarations-cnss-' . $entreprise->nom . '-' . date('Y-m-d_H-i-s') . '.pdf');
    }
    
    public function exportEntrepriseExcel(Request $request, $id)
    {
        $entrepriseId = $id;
        $entreprise = Entreprise::findOrFail($entrepriseId);
        $etat_filter = $request->input('etat_filter', 'all');
        $year_filter = $request->input('year_filter', 'all');
        
        $query = Cnss::where('entreprise_id', $entrepriseId);
        
        // Apply état filter
        if ($etat_filter != 'all') {
            $query->where('etat', $etat_filter);
        }
        
        // Apply year filter
        if ($year_filter != 'all') {
            $query->where('annee', $year_filter);
        }
        
        // Create a custom collection for export
        $declarations = $query->orderBy('annee', 'desc')
                             ->orderBy('Mois', 'desc')
                             ->get()
                             ->map(function ($declaration) {
                                 return [
                                     'Mois' => $declaration->french_month,
                                     'Année' => $declaration->annee,
                                     'Nombre de Salariés' => $declaration->Nbr_Salries,
                                     'État' => $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré',
                                 ];
                             });
            
        return Excel::download(new \App\Exports\CnssEntrepriseExport($declarations), 
            'declarations-cnss-' . $entreprise->nom . '-' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}