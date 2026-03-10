<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav">

            <div class="sidenav-menu-heading">Seguridad</div>

            <a class="nav-link {{ request()->routeIs('seguridad.dashboard') ? 'active' : '' }}" href="{{ route('seguridad.dashboard') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('seguridad.control-productos') ? 'active' : '' }}" href="{{ route('seguridad.control-productos') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                Control de productos
            </a>
            <a class="nav-link {{ request()->routeIs('seguridad.movimientos') ? 'active' : '' }}" href="{{ route('seguridad.movimientos') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                Movimientos
            </a>
            <a class="nav-link {{ request()->routeIs('seguridad.inventario') ? 'active' : '' }}" href="{{ route('seguridad.inventario') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                Inventario
            </a>
        </div>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Sesión iniciada como:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name ?? 'Estudiante' }}</div>
        </div>
    </div>

</nav>