<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="images/user.png" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ request()->session()->get('usuario')->nombreCompleto() }}</div>
            <div class="email">gerardo.gomr@gmail.com</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="javascript:void(0);"><i class="material-icons">person</i>Mi cuenta</a></li>
                    <li role="seperator" class="divider"></li>
                    <li><a href="{{ url('logout') }}"><i class="material-icons">input</i>Salir del sistema</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">MENÚ PRINCIPAL</li>
            <li class="active">
                <a href="{{ url('/') }}">
                    <i class="material-icons">home</i>
                    <span>Inicio</span>
                </a>
            </li>
            <li>
                <a href="{{ url('productos') }}">
                    <i class="material-icons">text_fields</i>
                    <span>Productos</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <div class="copyright">
            &copy; {{ date('Y') }} La Casa del <b>Perfume</b>
        </div>
        <div class="version">
            <b>Version: </b> 1.0
        </div>
    </div>
    <!-- #Footer -->
</aside>