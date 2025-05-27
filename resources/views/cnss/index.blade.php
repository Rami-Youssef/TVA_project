@extends('layouts.app', ['page' => __('Déclarations CNSS'), 'pageSlug' => 'CNSS'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Déclarations CNSS - {{ $monthName ?? date('F Y') }}</h4>
                        </div>
                        <div class="col-4 text-right">
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                <a href="{{ route('cnss.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i>                                </a>
                            @endif
                        </div>
                    </div>
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('cnss.index') }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par société..." value="{{ $search ?? '' }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="filter" class="form-control">
                                <option value="all" {{ ($filter ?? '') == 'all' ? 'selected' : '' }}>Toutes les déclarations</option>
                                <option value="declared" {{ ($filter ?? '') == 'declared' ? 'selected' : '' }}>Déclarées</option>
                                <option value="undeclared" {{ ($filter ?? '') == 'undeclared' ? 'selected' : '' }}>Non déclarées</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-default">Filtrer</button>
                        <a href="{{ route('cnss.index') }}" class="btn btn-sm btn-secondary ml-2">Réinitialiser</a>
                    </form>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-12 text-right">
                            <div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="tim-icons icon-cloud-download-93 mr-1"></i> Exporter
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                                    <h6 class="dropdown-header">Toutes les déclarations</h6>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('cnss.export.pdf', ['search' => $search ?? '', 'filter' => $filter ?? 'all']) }}">
                                        <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('cnss.export.excel', ['search' => $search ?? '', 'filter' => $filter ?? 'all']) }}">
                                        <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Page courante uniquement</h6>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('cnss.export.pdf', ['search' => $search ?? '', 'filter' => $filter ?? 'all', 'page' => $paginator->currentPage()]) }}">
                                        <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('cnss.export.excel', ['search' => $search ?? '', 'filter' => $filter ?? 'all', 'page' => $paginator->currentPage()]) }}">
                                        <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
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
                                @foreach ($paginator as $declaration)
                                    <tr>
                                        <td>{{ $declaration->entreprise->nom ?? 'N/A' }}</td>
                                        <td>{{ $declaration->french_month }}</td>
                                        <td>{{ $declaration->annee }}</td>
                                        <td>{{ $declaration->Nbr_Salries ?? 'Non déclaré' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $declaration->etat === 'valide' ? 'success' : 'warning' }}">
                                                {{ $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré' }}
                                            </span>
                                        </td>
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                                                    @if($declaration->exists !== false)
                                                        @if(Auth::user()->role === 'super_admin')
                                                            <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('cnss.edit', $declaration->id) }}'">
                                                                Modifier
                                                            </button>
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal-{{ $declaration->id }}">
                                                                Supprimer
                                                            </button>
                                                        @elseif(Auth::user()->role === 'admin')
                                                            <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('cnss.edit', $declaration->id) }}'">
                                                                Modifier
                                                            </button>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('cnss.create', ['entreprise_id' => $declaration->entreprise_id]) }}" class="btn btn-success btn-sm">
                                                            Déclarer
                                                        </a>
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
                        {{ $paginator->links() }}
                    </div>

                    <!-- Delete Confirmation Modals -->
                    @foreach ($paginator as $declaration)
                        @if($declaration->exists !== false)
                            <div class="modal fade" id="confirmDeleteModal-{{ $declaration->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('cnss.delete', $declaration->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content" style="background-color: rgb(82, 95, 127); color: white;">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title" style="color: aliceblue; font-size: 1rem; font-weight: bold;">Confirm Deletion</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                                        style="filter: invert(1) brightness(1) saturate(100%); cursor: pointer; border: none; background: none; font-size: 1.5rem; color: white;">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Please enter your password to confirm deletion.</p>
                                                <input type="password" name="password" class="form-control" style="background-color: #4f5e80; color: white;" required autofocus>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection