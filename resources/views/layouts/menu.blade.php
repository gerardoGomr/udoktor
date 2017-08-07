<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="{{ asset(isset($profilePicture) ? $profilePicture : 'images/user.png') }}" width="48" height="48" alt="User" class="user-picture">
        </div>
        <div class="info-container">
            @php
                $cuenta = '';
                $url    = '';
                if (Auth::user()->isServiceProvider()) {
                    $cuenta = 'Prestador de Servicios';
                    $url    = 'prestador-servicios';
                }

                if (Auth::user()->isClient()) {
                    $cuenta = 'Cliente';
                    $url    = 'clientes';
                }
            @endphp
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->getFullName()->fullName() }}</div>
            <div class="email"><b>{{ $cuenta }}</b></div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="/{{ $url }}/perfil"><i class="material-icons">person</i>Perfil</a></li>
                    <li role="seperator" class="divider"></li>
                    <li><a href="/logout"><i class="material-icons">input</i>Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">
        @yield('menu')
    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <div class="copyright">
            &copy; {{ date('Y') }} <b>Udoktor</b>
        </div>
        <div class="version">
            <b>Version: </b> 1.0
        </div>
    </div>
    <!-- #Footer -->
</aside>