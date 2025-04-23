@extends('layouts.app', ['page' => __('Modifier Déclaration CNSS'), 'pageSlug' => 'CNSS'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Modifier Déclaration CNSS</h4>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('cnss.update', $cnss) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    @include('alerts.success')

                    <div class="form-group{{ $errors->has('entreprise_id') ? ' has-danger' : '' }}">
                        <label>{{ __('Entreprise') }}</label>
                        <select name="entreprise_id" class="form-control{{ $errors->has('entreprise_id') ? ' is-invalid' : '' }}" required>
                            <option value="">Sélectionnez une entreprise</option>
                            @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}" {{ $cnss->entreprise_id == $entreprise->id ? 'selected' : '' }}>
                                    {{ $entreprise->name }}
                                </option>
                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'entreprise_id'])
                    </div>

                    <div class="form-group{{ $errors->has('Mois') ? ' has-danger' : '' }}">
                        <label>{{ __('Mois') }}</label>
                        <select name="Mois" class="form-control{{ $errors->has('Mois') ? ' is-invalid' : '' }}" required>
                            <option value="">Sélectionnez un mois</option>
                            <option value="1" {{ $cnss->Mois == 1 ? 'selected' : '' }}>Janvier</option>
                            <option value="2" {{ $cnss->Mois == 2 ? 'selected' : '' }}>Février</option>
                            <option value="3" {{ $cnss->Mois == 3 ? 'selected' : '' }}>Mars</option>
                            <option value="4" {{ $cnss->Mois == 4 ? 'selected' : '' }}>Avril</option>
                            <option value="5" {{ $cnss->Mois == 5 ? 'selected' : '' }}>Mai</option>
                            <option value="6" {{ $cnss->Mois == 6 ? 'selected' : '' }}>Juin</option>
                            <option value="7" {{ $cnss->Mois == 7 ? 'selected' : '' }}>Juillet</option>
                            <option value="8" {{ $cnss->Mois == 8 ? 'selected' : '' }}>Août</option>
                            <option value="9" {{ $cnss->Mois == 9 ? 'selected' : '' }}>Septembre</option>
                            <option value="10" {{ $cnss->Mois == 10 ? 'selected' : '' }}>Octobre</option>
                            <option value="11" {{ $cnss->Mois == 11 ? 'selected' : '' }}>Novembre</option>
                            <option value="12" {{ $cnss->Mois == 12 ? 'selected' : '' }}>Décembre</option>
                        </select>
                        @include('alerts.feedback', ['field' => 'Mois'])
                    </div>

                    <div class="form-group{{ $errors->has('annee') ? ' has-danger' : '' }}">
                        <label>{{ __('Année') }}</label>
                        <input type="number" name="annee" class="form-control{{ $errors->has('annee') ? ' is-invalid' : '' }}" 
                               value="{{ $cnss->annee }}" required>
                        @include('alerts.feedback', ['field' => 'annee'])
                    </div>

                    <div class="form-group{{ $errors->has('Nbr_Salries') ? ' has-danger' : '' }}">
                        <label>{{ __('Nombre de Salariés') }}</label>
                        <input type="number" name="Nbr_Salries" class="form-control{{ $errors->has('Nbr_Salries') ? ' is-invalid' : '' }}" 
                               value="{{ $cnss->Nbr_Salries }}" required min="0">
                        @include('alerts.feedback', ['field' => 'Nbr_Salries'])
                    </div>

                    <div class="form-group{{ $errors->has('etat') ? ' has-danger' : '' }}">
                        <label>{{ __('État') }}</label>
                        <select name="etat" class="form-control{{ $errors->has('etat') ? ' is-invalid' : '' }}" required>
                            <option value="en_attente" {{ $cnss->etat === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="valide" {{ $cnss->etat === 'valide' ? 'selected' : '' }}>Validé</option>
                            <option value="refuse" {{ $cnss->etat === 'refuse' ? 'selected' : '' }}>Refusé</option>
                        </select>
                        @include('alerts.feedback', ['field' => 'etat'])
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Mettre à jour') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection