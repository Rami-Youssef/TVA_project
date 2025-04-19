<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ __('BD') }}</a>
            <a href="#" class="simple-text logo-normal">{{ __('Black Dashboard') }}</a>
        </div>
        <ul class="nav">
            
            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="tim-icons icon-money-coins" ></i>
                    <span class="nav-link-text" >{{ __('Categories de TVA') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('TVA Trimestrielle') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('TVA Mensuelle') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('TVA Annuelle') }}</p>
                            </a>
                        </li>
                    </ul>
                    
                </div>
            </li>
            <li @if ($pageSlug == 'tables') class="active " @endif>
                <a href="{{ route('entreprise.getAllEntreprises') }}">
                    <i class="bi bi-building"></i>
                    <p>{{ __('Liste de Socités') }}</p>
                </a>
            </li>
            @if (auth()->user()->role !== 'user')
            <li @if ($pageSlug == 'tables') class="active " @endif>
                <a href="{{ route('user.getAllUsers') }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('User List') }}</p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
