<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal">{{ __('Gestion TVA') }}</a>
        </div>
        <ul class="nav">
            <li>
                <a data-toggle="collapse" href="#tva-menu" aria-expanded="true">
                    <i class="tim-icons icon-money-coins"></i>
                    <span class="nav-link-text">{{ __('TVA') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse @if (str_contains($pageSlug, 'tva-')) show @endif" id="tva-menu">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug === 'tva-mensuelle') class="active" @endif>
                            <a href="{{ route('tva-declaration.mensuelle') }}">
                                <i class="tim-icons icon-calendar-60"></i>
                                <p>{{ __('TVA Mensuelle') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug === 'tva-trimestrielle') class="active" @endif>
                            <a href="{{ route('tva-declaration.trimestrielle') }}">
                                <i class="tim-icons icon-chart-pie-36"></i>
                                <p>{{ __('TVA Trimestrielle') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug === 'tva-annuelle') class="active" @endif>
                            <a href="{{ route('tva-declaration.annuelle') }}">
                                <i class="tim-icons icon-chart-bar-32"></i>
                                <p>{{ __('TVA Annuelle') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li @if ($pageSlug === 'entreprises') class="active" @endif>
                <a href="{{ route('entreprise.getAllEntreprises') }}">
                    <i class="tim-icons icon-bank"></i>
                    <p>{{ __('Sociétés') }}</p>
                </a>
            </li>
            <li @if ($pageSlug === 'CNSS') class="active" @endif>
                <a href="{{ route('cnss.index') }}">
                    <i class="tim-icons icon-notes"></i>
                    <p>{{ __('CNSS') }}</p>
                </a>
            </li>

            @if (auth()->user()->role !== 'user')
            <li @if ($pageSlug === 'users') class="active" @endif>
                <a href="{{ route('user.getAllUsers') }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('Utilisateurs') }}</p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
