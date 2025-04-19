<?php

namespace App\Http\Controllers;

use App\Models\TvaDeclaration;
use App\Models\Entreprise;
use App\Http\Requests\TvaDeclarationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TvaDeclarationController extends Controller
{
    /**
     * Show the form for creating a new TVA declaration.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $entreprises = Entreprise::all();
        return view('tva-declarations.create', compact('entreprises'));
    }

    /**
     * Store a newly created TVA declaration in storage.
     *
     * @param  \App\Http\Requests\TvaDeclarationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TvaDeclarationRequest $request)
    {
        TvaDeclaration::create([
            'entreprise_id' => $request->input('entreprise_id'),
            'type' => $request->input('type'),
            'periode' => $request->input('periode'),
            'montant' => $request->input('montant'),
            'date_declaration' => $request->input('date_declaration'),
        ]);

        // Redirect to the appropriate type-specific view
        $route = match($request->input('type')) {
            'mensuelle' => 'tva-declaration.mensuelle',
            'trimestrielle' => 'tva-declaration.trimestrielle',
            'annuelle' => 'tva-declaration.annuelle',
            default => 'tva-declaration.getAllDeclarations'
        };

        return redirect()->route($route)->withStatus(__('Déclaration TVA créée avec succès.'));
    }

    /**
     * Show the form for editing the TVA declaration.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $tvaDeclaration = TvaDeclaration::findOrFail($id);
        $entreprises = Entreprise::all();
        return view('tva-declarations.edit', compact('tvaDeclaration', 'entreprises'));
    }

    /**
     * Update the TVA declaration in storage.
     *
     * @param  \App\Http\Requests\TvaDeclarationRequest  $request
     * @param  \App\Models\TvaDeclaration  $tvaDeclaration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TvaDeclarationRequest $request, TvaDeclaration $tvaDeclaration)
    {
        $tvaDeclaration->update([
            'entreprise_id' => $request->input('entreprise_id'),
            'type' => $request->input('type'),
            'periode' => $request->input('periode'),
            'montant' => $request->input('montant'),
            'date_declaration' => $request->input('date_declaration'),
        ]);

        // Redirect to the appropriate type-specific view
        $route = match($request->input('type')) {
            'mensuelle' => 'tva-declaration.mensuelle',
            'trimestrielle' => 'tva-declaration.trimestrielle',
            'annuelle' => 'tva-declaration.annuelle',
            default => 'tva-declaration.getAllDeclarations'
        };

        return redirect()->route($route)->withStatus(__('Déclaration TVA modifiée avec succès.'));
    }

    /**
     * Delete the TVA declaration from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TvaDeclaration  $tvaDeclaration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, TvaDeclaration $tvaDeclaration)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $type = $tvaDeclaration->type;
        $tvaDeclaration->delete();

        $route = match($type) {
            'mensuelle' => 'tva-declaration.mensuelle',
            'trimestrielle' => 'tva-declaration.trimestrielle',
            'annuelle' => 'tva-declaration.annuelle',
            default => 'tva-declaration.getAllDeclarations'
        };

        return redirect()->route($route)
            ->withStatus(__('Déclaration TVA supprimée avec succès.'));
    }

    /**
     * Get all TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getAllDeclarations()
    {
        $tvaDeclarations = TvaDeclaration::with('entreprise')->get();
        return view('tva-declarations.index', compact('tvaDeclarations'));
    }

    /**
     * Get monthly TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getMensuelle()
    {
        $declarations = TvaDeclaration::with('entreprise')
            ->where('type', 'mensuelle')
            ->orderBy('periode', 'desc')
            ->get();
        return view('tva-declarations.mensuel.index', compact('declarations'));
    }

    /**
     * Get quarterly TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getTrimestrielle()
    {
        $declarations = TvaDeclaration::with('entreprise')
            ->where('type', 'trimestrielle')
            ->orderBy('periode', 'desc')
            ->get();
        return view('tva-declarations.trimestriel.index', compact('declarations'));
    }

    /**
     * Get annual TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getAnnuelle()
    {
        $declarations = TvaDeclaration::with('entreprise')
            ->where('type', 'annuelle')
            ->orderBy('periode', 'desc')
            ->get();
        return view('tva-declarations.annuel.index', compact('declarations'));
    }
}