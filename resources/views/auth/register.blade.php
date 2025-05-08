@extends('layouts.app', ['class' => 'register-page', 'page' => __('Register Page'), 'contentClass' => 'register-page'])

@section('content')
    <div class="row">
        <div class="col-md-5 ml-auto">
            <div class="info-area info-horizontal mt-5">
                <div class="icon icon-warning">
                    <i class="tim-icons icon-wifi"></i>
                </div>
                <div class="description">
                    <h3 class="info-title">{{ __('Marketing') }}</h3>
                    <p class="description">
                        {{ __('We\'ve created the marketing campaign of the website. It was a very interesting collaboration.') }}
                    </p>
                </div>
            </div>
            <div class="info-area info-horizontal">
                <div class="icon icon-primary">
                    <i class="tim-icons icon-triangle-right-17"></i>
                </div>
                <div class="description">
                    <h3 class="info-title">{{ __('Fully Coded in HTML5') }}</h3>
                    <p class="description">
                        {{ __('We\'ve developed the website with HTML5 and CSS3. The client has access to the code using GitHub.') }}
                    </p>
                </div>
            </div>
            <div class="info-area info-horizontal">
                <div class="icon icon-info">
                    <i class="tim-icons icon-trophy"></i>
                </div>
                <div class="description">
                    <h3 class="info-title">{{ __('Built Audience') }}</h3>
                    <p class="description">
                        {{ __('There is also a Fully Customizable CMS Admin Dashboard for this product.') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-7 mr-auto">
            <div class="card card-register card-white">
                <div class="card-header">
                    <img class="card-img" src="{{ asset('black') }}/img/card-primary.png" alt="Card image">
                    <h4 class="card-title">{{ __('Register') }}</h4>
                </div>
                <form class="form" method="post" action="{{ route('register') }}">
                    @csrf

                    <div class="card-body">
                        <div class="input-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-single-02"></i>
                                </div>
                            </div>
                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>
                        <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-email-85"></i>
                                </div>
                            </div>
                            <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>
                        <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }} password-field">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password" id="reg-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}">
                            <div class="eye-icon toggle-password" data-target="reg-password">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="eye-open">
                                    <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"/>
                                    <path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="eye-closed">
                                    <path d="M12 19c-4.711 0-7.48-3.168-8.797-5.037a1 1 0 0 1 0-1.926C4.52 10.168 7.289 7 12 7c4.711 0 7.48 3.168 8.797 5.037a1 1 0 0 1 0 1.926C19.48 15.832 16.711 19 12 19zm0-11c-3.349 0-5.491 1.923-6.822 3.501C6.509 13.077 8.651 15 12 15c3.349 0 5.491-1.923 6.822-3.499C17.491 9.923 15.349 8 12 8zm0 5a2 2 0 1 0-.001-4.001A2 2 0 0 0 12 13z"/>
                                    <path d="M3.707 2.293a1 1 0 0 0-1.414 1.414l17 17a1 1 0 0 0 1.414-1.414l-17-17z"/>
                                </svg>
                            </div>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>
                        <div class="input-group password-field">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password_confirmation" id="confirm-password" class="form-control" placeholder="{{ __('Confirm Password') }}">
                            <div class="eye-icon toggle-password" data-target="confirm-password">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="eye-open">
                                    <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"/>
                                    <path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="eye-closed">
                                    <path d="M12 19c-4.711 0-7.48-3.168-8.797-5.037a1 1 0 0 1 0-1.926C4.52 10.168 7.289 7 12 7c4.711 0 7.48 3.168 8.797 5.037a1 1 0 0 1 0 1.926C19.48 15.832 16.711 19 12 19zm0-11c-3.349 0-5.491 1.923-6.822 3.501C6.509 13.077 8.651 15 12 15c3.349 0 5.491-1.923 6.822-3.499C17.491 9.923 15.349 8 12 8zm0 5a2 2 0 1 0-.001-4.001A2 2 0 0 0 12 13z"/>
                                    <path d="M3.707 2.293a1 1 0 0 0-1.414 1.414l17 17a1 1 0 0 0 1.414-1.414l-17-17z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="form-check text-left">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox">
                                <span class="form-check-sign"></span>
                                {{ __('I agree to the') }}
                                <a href="#">{{ __('terms and conditions') }}</a>.
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-round btn-lg">{{ __('Get Started') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            // Toggle password visibility
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.add('visible');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('visible');
            }
        });
    });
});
</script>

<style>
.password-field {
    position: relative;
}
.eye-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    width: 20px;
    height: 20px;
}
.eye-icon svg {
    fill: rgba(225, 78, 202, 0.8);
    width: 20px;
    height: 20px;
    transition: fill 0.3s ease;
}
.eye-icon svg:hover, .eye-icon.visible svg {
    fill: #e14eca;
}
.eye-icon .eye-closed {
    display: none;
}
.eye-icon.visible .eye-open {
    display: none;
}
.eye-icon.visible .eye-closed {
    display: block;
}
</style>
@endpush
