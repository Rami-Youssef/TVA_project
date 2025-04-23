<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class CnssController extends Controller
{
    public function index()
    {
        $declarations = Cnss::with('entreprise')->get();
        return view('cnss.index', compact('declarations'));
    }

    public function create()
    {
        $entreprises = Entreprise::all();
        return view('cnss.create', compact('entreprises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'Mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2000',
            'Nbr_Salries' => 'required|integer|min:0',
            'etat' => 'required|in:en_attente,valide,refuse',
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
            'Mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2000',
            'Nbr_Salries' => 'required|integer|min:0',
            'etat' => 'required|in:en_attente,valide,refuse',
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