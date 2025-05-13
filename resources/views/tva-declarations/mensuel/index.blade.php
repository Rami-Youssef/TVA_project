@extends('layouts.app', ['page' => __('TVA Mensuelle'), 'pageSlug' => 'tva-mensuelle'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">{{ __('Déclarations TVA Mensuelles') }}</h4>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                    <button class="btn btn-success" onclick="window.location.href='{{ route('tva-declaration.create') }}'">
                        <i class="fas fa-plus"></i>
                    </button>
                @endif
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form action="{{ route('tva-declaration.mensuelle') }}" method="GET" class="mb-4">
                    <div class="input-group d-flex justify-content-center align-items-center">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par société..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="tim-icons icon-zoom-split"></i>
                            </button>
                            @if(isset($search) && $search)
                                <a href="{{ route('tva-declaration.mensuelle') }}" class="btn btn-danger">
                                    <i class="tim-icons icon-simple-remove"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Export Buttons -->
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <div class="btn-group">
                            <a href="{{ route('tva-declaration.mensuelle.export.pdf', ['search' => $search ?? '']) }}" class="btn btn-sm btn-info">
                                <i class="tim-icons icon-paper"></i> PDF
                            </a>
                            <a href="{{ route('tva-declaration.mensuelle.export.excel', ['search' => $search ?? '']) }}" class="btn btn-sm btn-success">
                                <i class="tim-icons icon-chart-bar-32"></i> Excel
                            </a>
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
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal-{{ $user->id }}">
                                                        Delete
                                                    </button>
                                                @elseif(Auth::user()->role === 'admin')
                                                    <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('tva-declaration.edit', $declaration->id) }}'">
                                                        Edit
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
            </div>
        </div>
    </div>
</div>
@endsection