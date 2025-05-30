@extends('layouts.app', ['page' => __('Liste des États'), 'pageSlug' => 'etats'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Liste des États CNSS</h4>
                        </div>
                    </div>
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('etats.index') }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom d'entreprise..." value="{{ $search ?? '' }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="etat_filter" class="form-control">
                                <option value="">Filtrer par état</option>
                                <option value="valide" {{ request('etat_filter') == 'valide' ? 'selected' : '' }}>Déclarées</option>
                                <option value="non_valide" {{ request('etat_filter') == 'non_valide' ? 'selected' : '' }}>Non Déclarées</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="sort_by" class="form-control">
                                <option value="">Trier par</option>
                                <option value="nom_asc" {{ request('sort_by') == 'nom_asc' ? 'selected' : '' }}>Entreprise (A-Z)</option>
                                <option value="nom_desc" {{ request('sort_by') == 'nom_desc' ? 'selected' : '' }}>Entreprise (Z-A)</option>
                                <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>Date (Ancienne)</option>
                                <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>Date (Récente)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-default">Filtrer</button>
                        <a href="{{ route('etats.index') }}" class="btn btn-sm btn-secondary ml-2">Réinitialiser</a>
                    </form>
                </div>
                <div class="card-body">
                    @include('alerts.success')                    <!-- Export Buttons -->
                    <div class="row">
                        <div class="col-12 text-right">
                            <div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="tim-icons icon-cloud-download-93 mr-1"></i> Exporter
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                                    <h6 class="dropdown-header">Toutes les déclarations</h6>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('etats.export.pdf', ['search' => $search ?? '', 'etat_filter' => request('etat_filter'), 'sort_by' => request('sort_by')]) }}">
                                        <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('etats.export.excel', ['search' => $search ?? '', 'etat_filter' => request('etat_filter'), 'sort_by' => request('sort_by')]) }}">
                                        <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Page courante uniquement</h6>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('etats.export.pdf', ['search' => $search ?? '', 'etat_filter' => request('etat_filter'), 'sort_by' => request('sort_by'), 'page' => $declarations->currentPage()]) }}">
                                        <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('etats.export.excel', ['search' => $search ?? '', 'etat_filter' => request('etat_filter'), 'sort_by' => request('sort_by'), 'page' => $declarations->currentPage()]) }}">
                                        <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="">
                        <table class="table tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Mois</th>
                                    <th>Année</th>
                                    <th>Nombre de Salariés</th>
                                    <th>État</th>
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                        <th class="text-center">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($declarations as $declaration)
                                    <tr>
                                        <td>{{ $declaration->entreprise->nom ?? 'N/A' }}</td>
                                        <td>{{ $declaration->french_month }}</td>
                                        <td>{{ $declaration->annee }}</td>
                                        <td>{{ $declaration->Nbr_Salries }}</td>
                                        <td>
                                            <span class="badge badge-{{ $declaration->etat === 'valide' ? 'success' : 'warning' }}">
                                                {{ $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré' }}
                                            </span>
                                        </td>
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                                                    @if(Auth::user()->role === 'super_admin')
                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('cnss.edit', $declaration->id) }}'">
                                                            Modifier
                                                        </button>                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal-{{ $declaration->id }}">
                                                            Supprimer
                                                        </button>
                                                    @elseif(Auth::user()->role === 'admin')
                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('cnss.edit', $declaration->id) }}'">
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
                    <!-- Delete Confirmation Modals -->
                    @foreach ($declarations as $declaration)
                        <div class="modal fade" id="confirmDeleteModal-{{ $declaration->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('cnss.delete', $declaration->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content" style="background-color: rgb(82, 95, 127); color: white;">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title" style="color: aliceblue; font-size: 1rem; font-weight: bold;">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                    style="filter: invert(1) brightness(0) saturate(100%); cursor: pointer; border: none; background: none;">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Please enter your password to confirm deletion.</p>
                                            <input type="password" name="password" class="form-control" style="background-color: #4f5e80; color: white;" required autofocus>
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
                </div>                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-center" aria-label="...">
                        {{ $declarations->appends(request()->except('page'))->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection