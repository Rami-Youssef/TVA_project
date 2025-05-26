<?php

namespace App\Http\Controllers;

use App\Models\TvaDeclaration;
use App\Models\Entreprise;
use App\Http\Requests\TvaDeclarationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exports\TvaDeclarationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'montant_ht' => $request->input('montant_ht'),
            'montant_tva' => $request->input('montant_tva'),
            'montant_ttc' => $request->input('montant_ttc'),
            'date_declaration' => $request->input('date_declaration'),
            'date_paiement_prevue' => $request->input('date_paiement_prevue'),
            'statut_paiement' => $request->input('statut_paiement'),
        ]);

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
            'montant_ht' => $request->input('montant_ht'),
            'montant_tva' => $request->input('montant_tva'),
            'montant_ttc' => $request->input('montant_ttc'),
            'date_declaration' => $request->input('date_declaration'),
            'date_paiement_prevue' => $request->input('date_paiement_prevue'),
            'statut_paiement' => $request->input('statut_paiement'),
        ]);

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
        $currentUser = Auth::user();
        if (!$currentUser || !Hash::check($request->password, $currentUser->password)) {
            return back()->withErrors(['password' => 'Incorrect password or not authenticated.']);
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
    public function getAllDeclarations(Request $request)
    {
        $search = $request->input('search');
        
        $query = TvaDeclaration::with('entreprise');
        
        if ($search) {
            $query->whereHas('entreprise', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        $tvaDeclarations = $query->orderBy('date_declaration', 'desc')->paginate(10);
        
        if ($search) {
            $tvaDeclarations->appends(['search' => $search]);
        }
        
        return view('tva-declarations.index', compact('tvaDeclarations', 'search'));
    }

    /**
     * Get monthly TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getMensuelle(Request $request)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        
        $query = TvaDeclaration::with('entreprise')->where('type', 'mensuelle');
        
        // Apply search filter
        if ($search) {
            $query->whereHas('entreprise', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        // Apply periode filter
        if ($periodeFilter) {
            $query->where('periode', $periodeFilter);
        }
        
        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('tva_declarations.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('tva_declarations.*');
                    break;
                case 'periode_asc':
                    $query->orderBy('periode', 'asc');
                    break;
                case 'periode_desc':
                    $query->orderBy('periode', 'desc');
                    break;
                case 'montant_asc':
                    $query->orderBy('montant', 'asc');
                    break;
                case 'montant_desc':
                    $query->orderBy('montant', 'desc');
                    break;
                default:
                    $query->orderBy('periode', 'desc');
                    break;
            }
        } else {
            $query->orderBy('periode', 'desc');
        }
        
        // Get distinct periodes for filter dropdown
        $periodes = TvaDeclaration::where('type', 'mensuelle')
                     ->select('periode')
                     ->distinct()
                     ->orderBy('periode', 'desc')
                     ->pluck('periode')
                     ->toArray();
                     
        $declarations = $query->paginate(10);
        $declarations->appends($request->all());
        
        return view('tva-declarations.mensuel.index', compact('declarations', 'search', 'periodes', 'periodeFilter', 'sortBy'));
    }

    /**
     * Get quarterly TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getTrimestrielle(Request $request)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        
        $query = TvaDeclaration::with('entreprise')->where('type', 'trimestrielle');
        
        // Apply search filter
        if ($search) {
            $query->whereHas('entreprise', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        // Apply periode filter
        if ($periodeFilter) {
            $query->where('periode', $periodeFilter);
        }
        
        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('tva_declarations.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('tva_declarations.*');
                    break;
                case 'periode_asc':
                    $query->orderBy('periode', 'asc');
                    break;
                case 'periode_desc':
                    $query->orderBy('periode', 'desc');
                    break;
                case 'montant_asc':
                    $query->orderBy('montant', 'asc');
                    break;
                case 'montant_desc':
                    $query->orderBy('montant', 'desc');
                    break;
                default:
                    $query->orderBy('periode', 'desc');
                    break;
            }
        } else {
            $query->orderBy('periode', 'desc');
        }
        
        // Get distinct periodes for filter dropdown
        $periodes = TvaDeclaration::where('type', 'trimestrielle')
                     ->select('periode')
                     ->distinct()
                     ->orderBy('periode', 'desc')
                     ->pluck('periode')
                     ->toArray();
                     
        $declarations = $query->paginate(10);
        $declarations->appends($request->all());
        
        return view('tva-declarations.trimestriel.index', compact('declarations', 'search', 'periodes', 'periodeFilter', 'sortBy'));
    }

    /**
     * Get annual TVA declarations.
     *
     * @return \Illuminate\View\View
     */
    public function getAnnuelle(Request $request)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        
        $query = TvaDeclaration::with('entreprise')->where('type', 'annuelle');
        
        // Apply search filter
        if ($search) {
            $query->whereHas('entreprise', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        // Apply periode filter
        if ($periodeFilter) {
            $query->where('periode', $periodeFilter);
        }
        
        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('tva_declarations.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('tva_declarations.*');
                    break;
                case 'periode_asc':
                    $query->orderBy('periode', 'asc');
                    break;
                case 'periode_desc':
                    $query->orderBy('periode', 'desc');
                    break;
                case 'montant_asc':
                    $query->orderBy('montant', 'asc');
                    break;
                case 'montant_desc':
                    $query->orderBy('montant', 'desc');
                    break;
                default:
                    $query->orderBy('periode', 'desc');
                    break;
            }
        } else {
            $query->orderBy('periode', 'desc');
        }
        
        // Get distinct periodes (years) for filter dropdown
        $periodes = TvaDeclaration::where('type', 'annuelle')
                     ->select('periode')
                     ->distinct()
                     ->orderBy('periode', 'desc')
                     ->pluck('periode')
                     ->toArray();
                     
        $declarations = $query->paginate(10);
        $declarations->appends($request->all());
        
        return view('tva-declarations.annuel.index', compact('declarations', 'search', 'periodes', 'periodeFilter', 'sortBy'));
    }

    private function getFilteredDeclarations(Request $request, string $periodeType)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        
        $query = TvaDeclaration::with('entreprise')->where('type', $periodeType);

        // Apply search filter
        if ($search) {
            $query->whereHas('entreprise', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%");
            });
        }
        
        // Apply periode filter
        if ($periodeFilter) {
            $query->where('periode', $periodeFilter);
        }
        
        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'nom_asc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'asc')
                         ->select('tva_declarations.*');
                    break;
                case 'nom_desc':
                    $query->join('entreprises', 'tva_declarations.entreprise_id', '=', 'entreprises.id')
                         ->orderBy('entreprises.nom', 'desc')
                         ->select('tva_declarations.*');
                    break;
                case 'periode_asc':
                    $query->orderBy('periode', 'asc');
                    break;
                case 'periode_desc':
                    $query->orderBy('periode', 'desc');
                    break;
                case 'montant_asc':
                    $query->orderBy('montant', 'asc');
                    break;
                case 'montant_desc':
                    $query->orderBy('montant', 'desc');
                    break;
                default:
                    $query->orderBy('periode', 'desc');
                    break;
            }
        } else {
            $query->orderBy('periode', 'desc');
        }

        return $query->get();
    }

    /**
     * Export monthly TVA declarations to PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportMensuellePdf(Request $request)
    {
        $declarations = $this->getFilteredDeclarations($request, 'mensuelle');
        $pdf = Pdf::loadView('tva-declarations.pdf.mensuelle', compact('declarations'));
        return $pdf->download('tva-mensuelle-' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export monthly TVA declarations to Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportMensuelleExcel(Request $request)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        return Excel::download(
            new TvaDeclarationsExport('mensuelle', $search, $periodeFilter, $sortBy), 
            'tva-mensuelle-' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Export quarterly TVA declarations to PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportTrimestriellePdf(Request $request)
    {
        $declarations = $this->getFilteredDeclarations($request, 'trimestrielle');
        $pdf = Pdf::loadView('tva-declarations.pdf.trimestrielle', compact('declarations'));
        return $pdf->download('tva-trimestrielle-' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export quarterly TVA declarations to Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportTrimestrielleExcel(Request $request)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        return Excel::download(
            new TvaDeclarationsExport('trimestrielle', $search, $periodeFilter, $sortBy), 
            'tva-trimestrielle-' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Export annual TVA declarations to PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportAnnuellePdf(Request $request)
    {
        $declarations = $this->getFilteredDeclarations($request, 'annuelle');
        $pdf = Pdf::loadView('tva-declarations.pdf.annuelle', compact('declarations'));
        return $pdf->download('tva-annuelle-' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export annual TVA declarations to Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportAnnuelleExcel(Request $request)
    {
        $search = $request->input('search');
        $periodeFilter = $request->input('periode_filter');
        $sortBy = $request->input('sort_by');
        return Excel::download(
            new TvaDeclarationsExport('annuelle', $search, $periodeFilter, $sortBy), 
            'tva-annuelle-' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}