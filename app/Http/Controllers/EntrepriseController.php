<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Http\Requests\EntrepriseRequest; // You can create this request as needed
use Illuminate\Http\Request;

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
            'numero_societe' => $request->input('numero_societe'),
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
            'numero_societe' => $request->input('numero_societe'),
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
        $entreprise->delete();

        return redirect()->route('entreprise.getAllEntreprises')->withStatus(__('Entreprise successfully deleted.'));
    }

    /**
     * Get all entreprises.
     *
     * @return \Illuminate\View\View
     */
    public function getAllEntreprises()
    {
        $entreprises = Entreprise::all();
        return view('entreprises.index', compact('entreprises'));
    }
}
