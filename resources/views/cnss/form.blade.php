@extends('layouts.app', ['page' => isset($cnss) ? __('Modifier Déclaration CNSS') : __('Nouvelle Déclaration CNSS'), 'pageSlug' => 'CNSS'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ isset($cnss) ? __('Modifier Déclaration CNSS') : __('Nouvelle Déclaration CNSS') }}</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ isset($cnss) ? route('cnss.update', $cnss) : route('cnss.store') }}" autocomplete="off">
                        @csrf
                        @if(isset($cnss))
                            @method('put')
                        @endif

                        @include('alerts.success')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('entreprise_id') ? ' has-danger' : '' }}">
                                    <label>{{ __('Entreprise') }}</label>
                                    <select name="entreprise_id" class="form-control{{ $errors->has('entreprise_id') ? ' is-invalid' : '' }}" required>
                                        <option value="">{{ __('Sélectionner une entreprise') }}</option>
                                        @foreach ($entreprises as $entreprise)
                                            <option value="{{ $entreprise->id }}" {{ old('entreprise_id', isset($cnss) ? $cnss->entreprise_id : '') == $entreprise->id ? 'selected' : '' }}>
                                                {{ $entreprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'entreprise_id'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('annee') ? ' has-danger' : '' }}">
                                    <label>{{ __('Année') }}</label>
                                    <input type="number" name="annee" class="form-control{{ $errors->has('annee') ? ' is-invalid' : '' }}" placeholder="{{ __('Année') }}" value="{{ old('annee', isset($cnss) ? $cnss->annee : '') }}" required>
                                    @include('alerts.feedback', ['field' => 'annee'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('Mois') ? ' has-danger' : '' }}">
                                    <label>{{ __('Mois') }}</label>
                                    <select name="Mois" class="form-control{{ $errors->has('Mois') ? ' is-invalid' : '' }}" required>
                                        @foreach(['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $mois)
                                            <option value="{{ $mois }}" {{ old('Mois', isset($cnss) ? $cnss->Mois : '') == $mois ? 'selected' : '' }}>
                                                {{ $mois }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'Mois'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('Nbr_Salries') ? ' has-danger' : '' }}">
                                    <label>{{ __('Nombre de Salariés') }}</label>
                                    <input type="number" name="Nbr_Salries" class="form-control{{ $errors->has('Nbr_Salries') ? ' is-invalid' : '' }}" placeholder="{{ __('Nombre de Salariés') }}" value="{{ old('Nbr_Salries', isset($cnss) ? $cnss->Nbr_Salries : '') }}" required>
                                    @include('alerts.feedback', ['field' => 'Nbr_Salries'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('etat') ? ' has-danger' : '' }}">
                                    <label>{{ __('État') }}</label>
                                    <select name="etat" class="form-control{{ $errors->has('etat') ? ' is-invalid' : '' }}" required>
                                        @foreach(['Déclaré', 'Non déclaré'] as $etat)
                                            <option value="{{ $etat }}" {{ old('etat', isset($cnss) ? $cnss->etat : 'Non déclaré') == $etat ? 'selected' : '' }}>
                                                {{ $etat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'etat'])
                                </div>
                            </div>
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