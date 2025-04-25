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
        
        $query = Cnss::with('entreprise');
        
        if ($search) {
            $query->whereHas('entreprise', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        $declarations = $query->paginate(10);
        
        if ($search) {
            $declarations->appends(['search' => $search]);
        }
        
        return view('etats.index', compact('declarations', 'search'));
    }
}