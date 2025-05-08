@extends('layouts.app', ['class' => 'login-page', 'page' => __('Reset password'), 'contentClass' => 'login-page'])

@section('content')
    <div class="col-lg-5 col-md-7 ml-auto mr-auto">
        <form class="form" method="post" action="{{ route('password.update') }}">
            @csrf

            <div class="card card-login card-white">
                <div class="card-header">
                    <img src="{{ asset('black') }}/img/card-primary.png" alt="">
                    <h1 class="card-title">{{ __('Reset password') }}</h1>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <input type="hidden" name="token" value="{{ $token }}">

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
                            <input type="password" name="password" id="reset-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}">
                            <span class="password-toggle">
                                <i class="tim-icons icon-satisfied toggle-password" data-target="reset-password"></i>
                            </span>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>
                        <div class="input-group password-field">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password_confirmation" id="reset-confirm-password" class="form-control" placeholder="{{ __('Confirm Password') }}">
                            <span class="password-toggle">
                                <i class="tim-icons icon-satisfied toggle-password" data-target="reset-confirm-password"></i>
                            </span>
                        </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-lg btn-block mb-3">{{ __('Reset Password') }}</button>
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
    /* Remove opacity: 0 and transition to make always visible */
}
/* Remove the hover state that was making the icon visible */
.eye-icon svg {
    fill: rgba(225, 78, 202, 0.8); /* Set to permanent pink color (#e14eca with 80% opacity) */
    width: 20px;
    height: 20px;
    transition: fill 0.3s ease;
}
.eye-icon svg:hover, .eye-icon.visible svg {
    fill: #e14eca; /* Pure pink/purple color on hover and when visible */
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
