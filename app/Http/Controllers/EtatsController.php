<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class EtatsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter');
        $sortBy = $request->input('sort_by');
        
        $query = Cnss::with('entreprise');
        
        // Apply search filter for company name
        if ($search) {
            $query->whereHas('entreprise', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        // Apply etat filter
        if ($etatFilter) {
            $query->where('etat', $etatFilter);
        }
        
        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'cnss.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('cnss.*'); // Ensure we only select from the cnss table
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'cnss.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('cnss.*');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
        
        $declarations = $query->paginate(10);
        
        // Preserve all query parameters in pagination links
        $declarations->appends($request->all());
        
        return view('etats.index', compact('declarations', 'search', 'etatFilter', 'sortBy'));
    }
}