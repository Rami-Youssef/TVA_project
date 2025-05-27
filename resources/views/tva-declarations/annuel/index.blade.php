@extends('layouts.app', ['page' => __('TVA Annuelle'), 'pageSlug' => 'tva-annuelle'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">{{ __('Déclarations TVA Annuelles') }}</h4>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                    <button class="btn btn-success" onclick="window.location.href='{{ route('tva-declaration.create') }}'">
                        <i class="fas fa-plus"></i>
                    </button>
                @endif
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('tva-declaration.annuelle') }}" class="form-inline mb-4">
                    <div class="form-group mr-2">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom d'entreprise..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="form-group mr-2">
                        <select name="periode_filter" class="form-control">
                            <option value="">Filtrer par année</option>
                            @foreach($periodes as $periode)
                                <option value="{{ $periode }}" {{ ($periode_filter ?? '') == $periode ? 'selected' : '' }}>{{ $periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <select name="sort_by" class="form-control">
                            <option value="">Trier par</option>
                            <option value="nom_asc" {{ ($sort_by ?? '') == 'nom_asc' ? 'selected' : '' }}>Entreprise (A-Z)</option>
                            <option value="nom_desc" {{ ($sort_by ?? '') == 'nom_desc' ? 'selected' : '' }}>Entreprise (Z-A)</option>
                            <option value="periode_asc" {{ ($sort_by ?? '') == 'periode_asc' ? 'selected' : '' }}>Année (Ancienne)</option>
                            <option value="periode_desc" {{ ($sort_by ?? '') == 'periode_desc' ? 'selected' : '' }}>Année (Récente)</option>
                            <option value="montant_asc" {{ ($sort_by ?? '') == 'montant_asc' ? 'selected' : '' }}>Montant (Croissant)</option>
                            <option value="montant_desc" {{ ($sort_by ?? '') == 'montant_desc' ? 'selected' : '' }}>Montant (Décroissant)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-default">Filtrer</button>
                    <a href="{{ route('tva-declaration.annuelle') }}" class="btn btn-sm btn-secondary ml-2">Réinitialiser</a>
                </form>

                <!-- Export Buttons -->
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="tim-icons icon-cloud-download-93 mr-1"></i> Exporter
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                                <h6 class="dropdown-header">Toutes les déclarations</h6>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('tva-declaration.annuelle.export.pdf', ['search' => $search ?? '', 'periode_filter' => $periode_filter ?? '', 'sort_by' => $sort_by ?? '']) }}">
                                    <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('tva-declaration.annuelle.export.excel', ['search' => $search ?? '', 'periode_filter' => $periode_filter ?? '', 'sort_by' => $sort_by ?? '']) }}">
                                    <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                </a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Page courante uniquement</h6>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('tva-declaration.annuelle.export.pdf', ['search' => $search ?? '', 'periode_filter' => $periode_filter ?? '', 'sort_by' => $sort_by ?? '', 'page' => $declarations->currentPage()]) }}">
                                    <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('tva-declaration.annuelle.export.excel', ['search' => $search ?? '', 'periode_filter' => $periode_filter ?? '', 'sort_by' => $sort_by ?? '', 'page' => $declarations->currentPage()]) }}">
                                    <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table tablesorter">
                        <thead class="text-primary">
                            <tr>
                                <th>{{ __('Entreprise') }}</th>
                                <th>{{ __('Période') }}</th>
                                <th>{{ __('Montant') }}</th>
                                <th>{{ __('Date de Déclaration') }}</th>
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                    <th class="text-center">{{ __('Actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($declarations as $declaration)
                                <tr>
                                    <td>{{ $declaration->entreprise->nom }}</td>
                                    <td>{{ $declaration->periode }}</td>
                                    <td>{{ number_format($declaration->montant, 2, ',', ' ') }} €</td>
                                    <td>{{ \Carbon\Carbon::parse($declaration->date_declaration)->format('d/m/Y') }}</td>
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                                                @if(Auth::user()->role === 'super_admin')
                                                    <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('tva-declaration.edit', $declaration->id) }}'">
                                                        Modifier
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal-{{ $declaration->id }}">
                                                        Supprimer
                                                    </button>
                                                @elseif(Auth::user()->role === 'admin')
                                                    <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('tva-declaration.edit', $declaration->id) }}'">
                                                        Modifier
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $declarations->links() }}
                </div>

                <!-- Delete Confirmation Modals -->
                @foreach($declarations as $declaration)
                <div class="modal fade" id="confirmDeleteModal-{{ $declaration->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('tva-declaration.delete', $declaration->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content" style="background-color: rgb(82, 95, 127); color: white;">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" style="color: aliceblue; font-size: 1rem; font-weight: bold;">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                            style="filter: invert(1) brightness(0) saturate(100%); cursor: pointer; border: none; background: none;">
                                    </button>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection