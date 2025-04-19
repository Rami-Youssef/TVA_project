@extends('layouts.app', ['page' => __('Nouvelle Société'), 'pageSlug' => 'entreprises'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Ajouter une Société') }}</h5>
                </div>
                <form method="post" action="{{ route('entreprise.store') }}" autocomplete="off" id="entrepriseForm">
                    <div class="card-body">
                        @csrf
                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('nom') ? ' has-danger' : '' }}">
                            <label>{{ __('Nom de la société') }}</label>
                            <input type="text" name="nom" class="form-control{{ $errors->has('nom') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Nom') }}" value="{{ old('nom') }}" required minlength="3"
                                   data-toggle="tooltip" data-placement="right" title="Nom de la société (minimum 3 caractères)">
                            @include('alerts.feedback', ['field' => 'nom'])
                        </div>

                        <div class="form-group{{ $errors->has('siege_social') ? ' has-danger' : '' }}">
                            <label>{{ __('Siège social') }}</label>
                            <input type="text" name="siege_social" class="form-control{{ $errors->has('siege_social') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Adresse du siège social') }}" value="{{ old('siege_social') }}" required minlength="3"
                                   data-toggle="tooltip" data-placement="right" title="Adresse complète du siège social">
                            @include('alerts.feedback', ['field' => 'siege_social'])
                        </div>

                        <div class="form-group{{ $errors->has('form_juridique') ? ' has-danger' : '' }}">
                            <label>{{ __('Forme juridique') }}</label>
                            <select name="form_juridique" class="form-control{{ $errors->has('form_juridique') ? ' is-invalid' : '' }}" required
                                    data-toggle="tooltip" data-placement="right" title="Forme juridique de l'entreprise">
                                <option value="">{{ __('Sélectionner une forme juridique') }}</option>
                                <option value="Société par Actions Simplifiée (SAS)" {{ old('form_juridique') == 'Société par Actions Simplifiée (SAS)' ? 'selected' : '' }}>SAS</option>
                                <option value="Société à Responsabilité Limitée (SARL)" {{ old('form_juridique') == 'Société à Responsabilité Limitée (SARL)' ? 'selected' : '' }}>SARL</option>
                                <option value="Société Anonyme (SA)" {{ old('form_juridique') == 'Société Anonyme (SA)' ? 'selected' : '' }}>SA</option>
                                <option value="Entreprise Individuelle (EI)" {{ old('form_juridique') == 'Entreprise Individuelle (EI)' ? 'selected' : '' }}>EI</option>
                                <option value="Auto-Entrepreneur" {{ old('form_juridique') == 'Auto-Entrepreneur' ? 'selected' : '' }}>Auto-Entrepreneur</option>
                            </select>
                            @include('alerts.feedback', ['field' => 'form_juridique'])
                        </div>

                        <div class="form-group{{ $errors->has('activite_principale') ? ' has-danger' : '' }}">
                            <label>{{ __('Activité principale') }}</label>
                            <input type="text" name="activite_principale" class="form-control{{ $errors->has('activite_principale') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Activité principale') }}" value="{{ old('activite_principale') }}" required minlength="3"
                                   data-toggle="tooltip" data-placement="right" title="Secteur d'activité principal de l'entreprise">
                            @include('alerts.feedback', ['field' => 'activite_principale'])
                        </div>

                        <div class="form-group{{ $errors->has('numero_societe') ? ' has-danger' : '' }}">
                            <label>{{ __('Numéro de société') }}</label>
                            <input type="text" name="numero_societe" class="form-control{{ $errors->has('numero_societe') ? ' is-invalid' : '' }}" 
                                   placeholder="{{ __('Numéro SIREN/SIRET') }}" value="{{ old('numero_societe') }}" required pattern="[0-9]+"
                                   data-toggle="tooltip" data-placement="right" title="Numéro SIREN (9 chiffres) ou SIRET (14 chiffres)">
                            @include('alerts.feedback', ['field' => 'numero_societe'])
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

@push('js')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Form validation
        $("#entrepriseForm").on('submit', function(e) {
            const numeroSociete = $('input[name="numero_societe"]').val();
            if (!/^[0-9]+$/.test(numeroSociete)) {
                e.preventDefault();
                alert("Le numéro de société doit contenir uniquement des chiffres");
                return false;
            }
            
            if (numeroSociete.length !== 9 && numeroSociete.length !== 14) {
                e.preventDefault();
                alert("Le numéro de société doit contenir soit 9 chiffres (SIREN) soit 14 chiffres (SIRET)");
                return false;
            }
            
            return true;
        });
    });
</script>
@endpush