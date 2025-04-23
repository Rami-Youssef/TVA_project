@extends('layouts.app', ['page' => __('Nouvelle Déclaration CNSS'), 'pageSlug' => 'CNSS'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Nouvelle Déclaration CNSS</h4>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('cnss.store') }}" autocomplete="off">
                    @csrf
                    @include('alerts.success')

                    <div class="form-group{{ $errors->has('entreprise_id') ? ' has-danger' : '' }}">
                        <label>{{ __('Entreprise') }}</label>
                        <select name="entreprise_id" class="form-control{{ $errors->has('entreprise_id') ? ' is-invalid' : '' }}" required>
                            <option value="">Sélectionnez une entreprise</option>
                            @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'entreprise_id'])
                    </div>

                    <div class="form-group{{ $errors->has('Mois') ? ' has-danger' : '' }}">
                        <label>{{ __('Mois') }}</label>
                        <select name="Mois" class="form-control{{ $errors->has('Mois') ? ' is-invalid' : '' }}" required>
                            <option value="">Sélectionnez un mois</option>
                            <option value="1">Janvier</option>
                            <option value="2">Février</option>
                            <option value="3">Mars</option>
                            <option value="4">Avril</option>
                            <option value="5">Mai</option>
                            <option value="6">Juin</option>
                            <option value="7">Juillet</option>
                            <option value="8">Août</option>
                            <option value="9">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Décembre</option>
                        </select>
                        @include('alerts.feedback', ['field' => 'Mois'])
                    </div>

                    <div class="form-group{{ $errors->has('annee') ? ' has-danger' : '' }}">
                        <label>{{ __('Année') }}</label>
                        <input type="number" name="annee" class="form-control{{ $errors->has('annee') ? ' is-invalid' : '' }}" 
                               value="{{ date('Y') }}" required>
                        @include('alerts.feedback', ['field' => 'annee'])
                    </div>

                    <div class="form-group{{ $errors->has('Nbr_Salries') ? ' has-danger' : '' }}">
                        <label>{{ __('Nombre de Salariés') }}</label>
                        <input type="number" name="Nbr_Salries" class="form-control{{ $errors->has('Nbr_Salries') ? ' is-invalid' : '' }}" 
                               required min="0">
                        @include('alerts.feedback', ['field' => 'Nbr_Salries'])
                    </div>

                    <div class="form-group{{ $errors->has('etat') ? ' has-danger' : '' }}">
                        <label>{{ __('État') }}</label>
                        <select name="etat" class="form-control{{ $errors->has('etat') ? ' is-invalid' : '' }}" required>
                            <option value="en_attente">En attente</option>
                            <option value="valide">Validé</option>
                            <option value="refuse">Refusé</option>
                        </select>
                        @include('alerts.feedback', ['field' => 'etat'])
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Enregistrer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection