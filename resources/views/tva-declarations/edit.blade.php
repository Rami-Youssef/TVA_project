@extends('layouts.app', ['page' => __('Modifier Déclaration TVA'), 'pageSlug' => 'tva-declaration'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Modifier la Déclaration TVA') }}</h5>
                </div>
                <form method="post" action="{{ route('tva-declaration.update', $tvaDeclaration->id) }}" autocomplete="off">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('entreprise_id') ? ' has-danger' : '' }}">
                            <label>{{ __('Entreprise') }}</label>
                            <select name="entreprise_id" class="form-control{{ $errors->has('entreprise_id') ? ' is-invalid' : '' }}">
                                <option value="">Sélectionner une entreprise</option>
                                @foreach ($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}" {{ old('entreprise_id', $tvaDeclaration->entreprise_id) == $entreprise->id ? 'selected' : '' }}>
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
                                <option value="mensuelle" {{ old('type', $tvaDeclaration->type) == 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                                <option value="trimestrielle" {{ old('type', $tvaDeclaration->type) == 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                                <option value="annuelle" {{ old('type', $tvaDeclaration->type) == 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                            </select>
                            @include('alerts.feedback', ['field' => 'type'])
                        </div>

                        <div class="form-group{{ $errors->has('periode') ? ' has-danger' : '' }}">
                            <label>{{ __('Période') }}</label>
                            <input type="text" name="periode" class="form-control{{ $errors->has('periode') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Ex: 2025-01 ou 2025-Q1 ou 2025') }}" 
                                   value="{{ old('periode', $tvaDeclaration->periode) }}">
                            @include('alerts.feedback', ['field' => 'periode'])
                        </div>

                        <div class="form-group{{ $errors->has('montant') ? ' has-danger' : '' }}">
                            <label>{{ __('Montant (€)') }}</label>
                            <input type="number" step="0.01" name="montant" class="form-control{{ $errors->has('montant') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Montant') }}" 
                                   value="{{ old('montant', $tvaDeclaration->montant) }}">
                            @include('alerts.feedback', ['field' => 'montant'])
                        </div>

                        <div class="form-group{{ $errors->has('date_declaration') ? ' has-danger' : '' }}">
                            <label>{{ __('Date de Déclaration') }}</label>
                            <input type="date" name="date_declaration" class="form-control{{ $errors->has('date_declaration') ? ' is-invalid' : '' }}" 
                                   value="{{ old('date_declaration', $tvaDeclaration->date_declaration) }}">
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