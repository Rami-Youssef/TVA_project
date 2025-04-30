@extends('layouts.app', ['page' => __('Sociétés'), 'pageSlug' => 'entreprises'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">{{ __('Liste des Sociétés') }}</h4>
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
          <button class="btn btn-success" onclick="window.location.href='{{ route('entreprise.create') }}'">
            <i class="fas fa-plus"></i>
          </button>
        @endif
      </div>
      <div class="card-body">
        <!-- Search and Filter Form -->
        <form action="{{ route('entreprise.getAllEntreprises') }}" method="GET" class="mb-4">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom..." value="{{ $search ?? '' }}">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="tim-icons icon-zoom-split"></i>
                  </button>
                  @if(isset($search) && $search)
                    <a href="{{ route('entreprise.getAllEntreprises') }}" class="btn btn-danger">
                      <i class="tim-icons icon-simple-remove"></i>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </form>
        
        <div class="table-responsive">
          <table class="table tablesorter">
            <thead class="text-primary">
              <tr>
                <th>{{ __('Nom') }}</th>
                <th>{{ __('Siège Social') }}</th>
                <th>{{ __('Forme Juridique') }}</th>
                <th>{{ __('Activité') }}</th>
                <th>{{ __('N° Société') }}</th>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                  <th class="text-center">{{ __('Actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach ($entreprises as $entreprise)
              <tr>
                <td>{{ $entreprise->nom }}</td>
                <td>{{ $entreprise->siege_social }}</td>
                <td>{{ $entreprise->form_juridique }}</td>
                <td>{{ $entreprise->activite_principale }}</td>
                <td>{{ $entreprise->numero_societe }}</td>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                <td class="text-center">
                  <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                    @if(Auth::user()->role === 'super_admin')
                      <button class="btn btn-info btn-sm" onclick="window.location.href='{{ route('cnss.create', ['entreprise_id' => $entreprise->id]) }}'">
                        CNSS
                      </button>
                      <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('entreprise.edit', $entreprise->id) }}'">
                        Edit
                      </button>
                      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal-{{ $entreprise->id }}">
                        Delete
                      </button>
                    @elseif(Auth::user()->role === 'admin')
                      <button class="btn btn-info btn-sm" onclick="window.location.href='{{ route('cnss.create', ['entreprise_id' => $entreprise->id]) }}'">
                        CNSS
                      </button>
                      <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('entreprise.edit', $entreprise->id) }}'">
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

        <!-- Delete Confirmation Modals -->
        @foreach ($entreprises as $entreprise)
        <div class="modal fade" id="confirmDeleteModal-{{ $entreprise->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" action="{{ route('entreprise.delete', $entreprise->id) }}">
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

<!-- Pagination Links -->
<div class="d-flex justify-content-center mt-4">
  {{ $entreprises->links() }}
</div>
@endsection
