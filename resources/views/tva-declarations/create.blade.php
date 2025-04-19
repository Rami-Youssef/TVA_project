@extends('layouts.app', ['page' => __('Nouvelle Déclaration TVA'), 'pageSlug' => 'tva-declaration'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Ajouter une Déclaration TVA') }}</h5>
                </div>
                <form method="post" action="{{ route('tva-declaration.store') }}" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('entreprise_id') ? ' has-danger' : '' }}">
                            <label>{{ __('Entreprise') }}</label>
                            <select name="entreprise_id" class="form-control{{ $errors->has('entreprise_id') ? ' is-invalid' : '' }}">
                                <option value="">Sélectionner une entreprise</option>
                                @foreach ($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                        {{ $entreprise->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'entreprise_id'])
                        </div>

                        <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                            <label>{{ __('Type de TVA') }}</label>
                            <select name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                <option value="">Sélectionner le type</option>
                                <option value="mensuelle" {{ old('type') == 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                                <option value="trimestrielle" {{ old('type') == 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                                <option value="annuelle" {{ old('type') == 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                            </select>
                            @include('alerts.feedback', ['field' => 'type'])
                        </div>

                        <div class="form-group{{ $errors->has('periode') ? ' has-danger' : '' }}">
                            <label>{{ __('Période') }}</label>
                            <input type="text" name="periode" class="form-control{{ $errors->has('periode') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Ex: 2025-01 ou 2025-Q1 ou 2025') }}" value="{{ old('periode') }}">
                            @include('alerts.feedback', ['field' => 'periode'])
                        </div>

                        <div class="form-group{{ $errors->has('montant') ? ' has-danger' : '' }}">
                            <label>{{ __('Montant (€)') }}</label>
                            <input type="number" step="0.01" name="montant" class="form-control{{ $errors->has('montant') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Montant') }}" value="{{ old('montant') }}">
                            @include('alerts.feedback', ['field' => 'montant'])
                        </div>

                        <div class="form-group{{ $errors->has('date_declaration') ? ' has-danger' : '' }}">
                            <label>{{ __('Date de Déclaration') }}</label>
                            <input type="date" name="date_declaration" class="form-control{{ $errors->has('date_declaration') ? ' is-invalid' : '' }}" 
                                   value="{{ old('date_declaration', date('Y-m-d')) }}">
                            @include('alerts.feedback', ['field' => 'date_declaration'])
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Enregistrer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection