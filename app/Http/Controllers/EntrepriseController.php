<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Http\Requests\EntrepriseRequest; // You can create this request as needed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exports\EntreprisesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $etatFilter = $request->input('etat_filter', 'all'); // Default to 'all'
        $sortBy = $request->input('sort_by');
        
        $query = $this->getFilteredEntreprisesQuery($request);
        
        $entreprises = $query->paginate(10);
        
        // Preserve all query parameters in pagination links
        $entreprises->appends($request->except('page')); // Use except('page') for cleaner URLs
        
        return view('entreprises.index', compact('entreprises', 'search', 'etatFilter', 'sortBy'));
    }

    private function getFilteredEntreprisesQuery(Request $request)
    {
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter', 'all');
        $sortBy = $request->input('sort_by');

        $query = Entreprise::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('ice', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }
        
        // Apply etat filter (assuming 'etat' is on a related 'cnssDeclarations' model)
        if ($etatFilter && $etatFilter !== 'all') {
            $query->whereHas('cnssDeclarations', function($q) use ($etatFilter) {
                $q->where('etat', $etatFilter);
            });
        }
        
        // Apply sorting
        if ($sortBy) {
            $direction = (str_ends_with($sortBy, '_desc')) ? 'desc' : 'asc';
            $column = str_replace(['_asc', '_desc'], '', $sortBy);
            // Validate sortable columns
            $sortableColumns = ['nom', 'created_at', 'ice']; // Add other sortable columns as needed
            if (in_array($column, $sortableColumns)) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('nom', 'asc'); // Default sort
        }
        return $query;
    }

    public function exportPdf(Request $request)
    {
        $entreprises = $this->getFilteredEntreprisesQuery($request)->get();

        $pdf = Pdf::loadView('entreprises.pdf', compact('entreprises'));
        return $pdf->download('entreprises-' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $etatFilter = $request->input('etat_filter', 'all');

        return Excel::download(new EntreprisesExport($search, $etatFilter), 'entreprises-' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
