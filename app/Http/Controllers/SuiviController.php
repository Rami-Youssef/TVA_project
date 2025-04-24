<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;

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
        $declarations = Cnss::where('entreprise_id', $entrepriseId)->paginate(10);
        
        return view('suivi.show', compact('declarations', 'entreprise'));
    }
}