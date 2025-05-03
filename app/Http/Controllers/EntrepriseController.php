<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Http\Requests\EntrepriseRequest; // You can create this request as needed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EntrepriseController extends Controller
{
    /**
     * Show the form for creating a new entreprise.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('entreprises.create');
    }

    /**
     * Store a newly created entreprise in storage.
     *
     * @param  \App\Http\Requests\EntrepriseRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EntrepriseRequest $request)
    {
        Entreprise::create([
            'nom' => $request->input('nom'),
            'siege_social' => $request->input('siege_social'),
            'form_juridique' => $request->input('form_juridique'),
            'activite_principale' => $request->input('activite_principale'),
            'ice' => $request->input('ice'),
        ]);

        return redirect()->route('entreprise.getAllEntreprises')->withStatus(__('Entreprise successfully created.'));
    }

    /**
     * Show the form for editing the entreprise.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        return view('entreprises.edit', compact('entreprise'));
    }

    /**
     * Update the entreprise in storage.
     *
     * @param  \App\Http\Requests\EntrepriseRequest  $request
     * @param  \App\Models\Entreprise  $entreprise
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EntrepriseRequest $request, Entreprise $entreprise)
    {
        $entreprise->update([
            'nom' => $request->input('nom'),
            'siege_social' => $request->input('siege_social'),
            'form_juridique' => $request->input('form_juridique'),
            'activite_principale' => $request->input('activite_principale'),
            'ice' => $request->input('ice'),
        ]);

        return redirect()->route('entreprise.getAllEntreprises')->withStatus(__('Entreprise successfully updated.'));
    }

    /**
     * Delete the entreprise from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entreprise  $entreprise
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Entreprise $entreprise)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $entreprise->delete();

        return redirect()->route('entreprise.getAllEntreprises')->withStatus(__('Entreprise successfully deleted.'));
    }

    /**
     * Get all entreprises.
     *
     * @return \Illuminate\View\View
     */
    public function getAllEntreprises(Request $request)
    {
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter');
        $sortBy = $request->input('sort_by');
        
        $query = Entreprise::query();
        
        // Apply search filter
        if ($search) {
            $query->where('nom', 'like', "%{$search}%");
        }
        
        // Apply etat filter
        if ($etatFilter) {
            $query->whereHas('cnssDeclarations', function($q) use ($etatFilter) {
                $q->where('etat', $etatFilter);
            });
        }
        
        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->orderBy('nom', 'asc');
                    break;
                case 'nom_desc':
                    $query->orderBy('nom', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
        
        $entreprises = $query->paginate(10);
        
        // Preserve all query parameters in pagination links
        $entreprises->appends($request->all());
        
        return view('entreprises.index', compact('entreprises', 'search', 'etatFilter', 'sortBy'));
    }
}
