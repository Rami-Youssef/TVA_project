@extends('layouts.app', ['page' => __('Entreprise Profile'), 'pageSlug' => 'profile'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Edit Entreprise') }}</h5>
                </div>
                <form method="post" action="{{ route('entreprise.update', $entreprise->id) }}" autocomplete="off">
                    <div class="card-body">
                            @csrf
                            @method('put')

                            @include('alerts.success')
                            <input type="text" name="id" class="form-control" value="{{ $entreprise->id }}" hidden>
                            <div class="form-group{{ $errors->has('nom') ? ' has-danger' : '' }}">
                                <label>{{ __('Nom') }}</label>
                                <input type="text" name="nom" class="form-control{{ $errors->has('nom') ? ' is-invalid' : '' }}" placeholder="{{ __('Nom') }}" value="{{ $entreprise->nom }}">
                                @include('alerts.feedback', ['field' => 'nom'])
                            </div>

                            <div class="form-group{{ $errors->has('siege_social') ? ' has-danger' : '' }}">
                                <label>{{ __('Siège Social') }}</label>
                                <input type="text" name="siege_social" class="form-control{{ $errors->has('siege_social') ? ' is-invalid' : '' }}" placeholder="{{ __('Siège Social') }}" value="{{ $entreprise->siege_social }}">
                                @include('alerts.feedback', ['field' => 'siege_social'])
                            </div>

                            <div class="form-group{{ $errors->has('form_juridique') ? ' has-danger' : '' }}">
                                <label>{{ __('Forme Juridique') }}</label>
                                <input type="text" name="form_juridique" class="form-control{{ $errors->has('form_juridique') ? ' is-invalid' : '' }}" placeholder="{{ __('Forme Juridique') }}" value="{{ $entreprise->form_juridique }}">
                                @include('alerts.feedback', ['field' => 'form_juridique'])
                            </div>

                            <div class="form-group{{ $errors->has('activite_principale') ? ' has-danger' : '' }}">
                                <label>{{ __('Activité Principale') }}</label>
                                <input type="text" name="activite_principale" class="form-control{{ $errors->has('activite_principale') ? ' is-invalid' : '' }}" placeholder="{{ __('Activité Principale') }}" value="{{ $entreprise->activite_principale }}">
                                @include('alerts.feedback', ['field' => 'activite_principale'])
                            </div>

                            <div class="form-group{{ $errors->has('numero_societe') ? ' has-danger' : '' }}">
                                <label>{{ __('Numéro Société') }}</label>
                                <input type="text" name="numero_societe" class="form-control{{ $errors->has('numero_societe') ? ' is-invalid' : '' }}" placeholder="{{ __('Numéro Société') }}" value="{{ $entreprise->numero_societe }}">
                                @include('alerts.feedback', ['field' => 'numero_societe'])
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-user">
                <div class="card-body">
                    <p class="card-text">
                        <div class="author">
                            <div class="block block-one"></div>
                            <div class="block block-two"></div>
                            <div class="block block-three"></div>
                            <div class="block block-four"></div>
                            <a href="#">
                                <img class="avatar" src="{{ asset('black') }}/img/emilyz.jpg" alt="">
                                <h5 class="title">{{ auth()->user()->name }}</h5>
                            </a>
                            <p class="description">
                                {{ __('CEO/Co-Founder') }}
                            </p>
                        </div>
                    </p>
                    <div class="card-description">
                        {{ __('We build for the future, connecting businesses and empowering growth.') }}
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
