@extends('layouts.app', ['page' => __('Liste des Suivis'), 'pageSlug' => 'suivi'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Sélectionner une entreprise pour voir ses déclarations CNSS</h4>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('suivi.index') }}" method="GET">
                                <div class="input-group d-flex justify-content-center align-items-center">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher une entreprise..." value="{{ $search ?? '' }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="tim-icons icon-zoom-split"></i>
                                        </button>
                                        @if(isset($search) && $search)
                                            <a href="{{ route('suivi.index') }}" class="btn btn-danger">
                                                <i class="tim-icons icon-simple-remove"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <!-- Export Buttons -->
                    <div class="row mb-3">
                        <div class="col-12 text-right">
                            <div class="btn-group">
                                <a href="{{ route('suivi.export.pdf', ['search' => $search ?? '']) }}" class="btn btn-sm btn-info">
                                    <i class="tim-icons icon-paper"></i> PDF
                                </a>
                                <a href="{{ route('suivi.export.excel', ['search' => $search ?? '']) }}" class="btn btn-sm btn-success">
                                    <i class="tim-icons icon-chart-bar-32"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if(count($entreprises) > 0)
                        <div class="row">
                            @foreach ($entreprises as $entreprise)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $entreprise->nom }}</h5>
                                            <p class="card-text">
                                                <strong>Activité:</strong> {{ $entreprise->activite_principale ?? 'N/A' }}<br>
                                                <strong>ICE:</strong> {{ $entreprise->ice ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $entreprise->siege_social ?? '' }}</small>
                                            </p>
                                            <div class="mt-auto">
                                                <a href="{{ route('suivi.show', $entreprise->id) }}" class="btn btn-primary btn-sm btn-block">
                                                    Voir les déclarations CNSS
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">Aucune entreprise trouvée.</p>
                    @endif
                    
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $entreprises->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection