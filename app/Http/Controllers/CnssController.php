<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CnssController extends Controller
{
    public function index()
    {
        $declarations = Cnss::with('entreprise')->paginate(10);
        return view('cnss.index', compact('declarations'));
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