@extends('layouts.app', ['page' => __('Create Profile'), 'pageSlug' => 'profile'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('new Profile') }}</h5>
                </div>
                <form method="post" action="{{ route('user.store') }}" autocomplete="off">
                    <div class="card-body">
                            @csrf

                            @include('alerts.success')
                            <input type="text" name="id" class="form-control" hidden>
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}">
                                @include('alerts.feedback', ['field' => 'name'])
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label>{{ __('Email address') }}</label>
                                <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email address') }}">
                                @include('alerts.feedback', ['field' => 'email'])
                            </div>                            <div class="form-group{{ $errors->has('role') ? ' has-danger' : '' }}">
                                <label>{{ __('Role') }}</label>
                                <select name="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                @include('alerts.feedback', ['field' => 'role'])
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label>{{ __('Password') }}</label>
                                <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>
                                @include('alerts.feedback', ['field' => 'password'])
                            </div>
                            <div class="form-group">
                                <label>{{ __('Confirm Password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm New Password') }}" value="" required>
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
                

                    

                        
                    
        </div>
        
    </div>
@endsection
