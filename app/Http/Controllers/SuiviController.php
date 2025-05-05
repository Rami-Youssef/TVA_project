<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuiviController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Filter companies by name if search parameter is provided
        if ($search) {
            $entreprises = Entreprise::where('nom', 'like', "%{$search}%")->paginate(10);
            $entreprises->appends(['search' => $search]);
        } else {
            $entreprises = Entreprise::paginate(10);
        }
        
        return view('suivi.index', compact('entreprises', 'search'));
    }
    
    public function show($entrepriseId)
    {
        // Fetch only CNSS declarations for the specific company
        $entreprise = Entreprise::findOrFail($entrepriseId);
        $declarations = Cnss::where('entreprise_id', $entrepriseId)->orderBy('annee', 'asc')
            ->orderBy('Mois', 'asc')->paginate(10);
            
        // Get all existing declarations for the chart (without pagination)
        $allDeclarations = Cnss::where('entreprise_id', $entrepriseId)
            ->orderBy('annee', 'asc')
            ->orderBy('Mois', 'asc')
            ->get();
        
        // Prepare data for the chart, ensuring all months are covered
        $chartData = $this->prepareMonthlyChartData($allDeclarations);
        
        return view('suivi.show', compact('declarations', 'entreprise', 'chartData'));
    }
    
    /**
     * Prepare monthly chart data with gaps filled with zeroes
     */
    private function prepareMonthlyChartData($declarations)
    {
        if ($declarations->isEmpty()) {
            return [];
        }
        
        // Find the first and last dates
        $firstDate = null;
        $lastDate = Carbon::now();
        
        foreach ($declarations as $declaration) {
            $declarationDate = Carbon::createFromDate($declaration->annee, $declaration->Mois, 1);
            
            if ($firstDate === null || $declarationDate->lt($firstDate)) {
                $firstDate = $declarationDate;
            }
        }
        
        // If no declarations found, return empty array
        if ($firstDate === null) {
            return [];
        }
        
        // Create a map of all declarations by year-month
        $declarationsByMonth = [];
        foreach ($declarations as $declaration) {
            $key = $declaration->annee . '-' . str_pad($declaration->Mois, 2, '0', STR_PAD_LEFT);
            $declarationsByMonth[$key] = $declaration->Nbr_Salries ?? 0;
        }
        
        // Generate continuous series of months from first to current date
        $result = [];
        $currentDate = clone $firstDate;
        
        while ($currentDate->lte($lastDate)) {
            $key = $currentDate->format('Y-m');
            $timestamp = $currentDate->timestamp * 1000; // Convert to milliseconds for ApexCharts
            $count = $declarationsByMonth[$key] ?? 0; // Use 0 if no data exists
            
            $result[] = [$timestamp, $count];
            
            $currentDate->addMonth();
        }
        
        return $result;
    }
}