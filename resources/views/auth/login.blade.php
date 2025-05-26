@extends('layouts.app', ['class' => 'login-page', 'page' => __('Login Page'), 'contentClass' => 'login-page'])

@section('content')
    
    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
        <form class="form" method="post" action="{{ route('login') }}">
            @csrf

            <div class="card card-login card-white">
                <div class="card-header">
                    <img src="{{ asset('black') }}/img/card-primary.png" alt="">
                    <h1 class="card-title">{{ __('Log in') }}</h1>
                </div>
                <div class="card-body">
                    <br>
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
                        <input type="password" placeholder="{{ __('Password') }}" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                        <div class="eye-icon toggle-password" data-target="password">
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
                </div>
                <div class="card-footer">
                    <button type="submit" href="" class="btn btn-primary btn-lg btn-block mb-3">{{ __('Get Started') }}</button>
                </div>
            </div>
        </form>
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
