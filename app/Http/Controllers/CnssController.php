<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CnssController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all'); 
        
        
        $currentMonth = date('n'); 
        $currentYear = date('Y');  
        
        // Get all companies
        $allEntreprises = Entreprise::query();
        
        // Apply search filter to companies if needed
        if ($search) {
            $allEntreprises->where('nom', 'like', "%{$search}%");
        }
        
        // Get the filtered companies
        $entreprises = $allEntreprises->get();
        
        // Prepare the result collection
        $declarations = collect();
        
        foreach ($entreprises as $entreprise) {
            // Check if the company has a CNSS declaration for the current month
            $declaration = Cnss::where('entreprise_id', $entreprise->id)
                ->where('Mois', $currentMonth)
                ->where('annee', $currentYear)
                ->first();
            
            if ($declaration) {
                // Company has declared - include it based on filter
                if ($filter === 'all' || $filter === 'declared') {
                    $declarations->push($declaration);
                }
            } else {
                // Company hasn't declared - create a placeholder and include based on filter
                if ($filter === 'all' || $filter === 'undeclared') {
                    // Create a non-persisted model instance for display
                    $placeholder = new Cnss([
                        'entreprise_id' => $entreprise->id,
                        'Mois' => $currentMonth,
                        'annee' => $currentYear,
                        'Nbr_Salries' => null,
                        'etat' => 'non_valide'
                    ]);
                    $placeholder->exists = false; // Mark as non-persisted
                    $placeholder->entreprise = $entreprise; // Set relationship manually
                    $declarations->push($placeholder);
                }
            }
        }
        
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = 10;
        $totalItems = $declarations->count();
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $declarations->forPage($page, $perPage),
            $totalItems,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Pass the current month name to the view
        $monthName = date('F Y'); // Returns the full month name (April 2025)
        
        return view('cnss.index', compact('paginator', 'search', 'monthName', 'filter'));
    }

    public function create(Request $request)
    {
        $entreprises = Entreprise::all();
        $selectedEntrepriseId = $request->query('entreprise_id');
        return view('cnss.create', compact('entreprises', 'selectedEntrepriseId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'Mois' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('cnss')->where(function ($query) use ($request) {
                    return $query->where('entreprise_id', $request->entreprise_id)
                                 ->where('annee', $request->annee);
                }),
            ],
            'annee' => 'required|integer|min:2000',
            'Nbr_Salries' => 'required|integer|min:0',
            'etat' => 'required|in:valide,non_valide',
        ]);

        Cnss::create($validated);
        return redirect()->route('cnss.index')->withStatus(__('Déclaration CNSS créée avec succès.'));
    }

    public function edit(Cnss $cnss)
    {
        $entreprises = Entreprise::all();
        return view('cnss.edit', compact('cnss', 'entreprises'));
    }

    public function update(Request $request, Cnss $cnss)
    {
        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'Mois' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('cnss')->where(function ($query) use ($request) {
                    return $query->where('entreprise_id', $request->entreprise_id)
                                 ->where('annee', $request->annee);
                })->ignore($cnss->id),
            ],
            'annee' => 'required|integer|min:2000',
            'Nbr_Salries' => 'required|integer|min:0',
            'etat' => 'required|in:valide,non_valide',
        ]);

        $cnss->update($validated);
        return redirect()->route('cnss.index')->withStatus(__('Déclaration CNSS mise à jour avec succès.'));
    }

    public function destroy(Cnss $cnss)
    {
        $cnss->delete();
        return redirect()->route('cnss.index')->withStatus(__('Déclaration CNSS supprimée avec succès.'));
    }
}