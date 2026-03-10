<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav">

            <div class="sidenav-menu-heading">Encargado</div>

            <a class="nav-link {{ request()->routeIs('encargado.dashboard') ? 'active' : '' }}" href="{{ route('encargado.dashboard') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                Dashboard
            </a>
             <!-- Punto de venta -->
            <a class="nav-link {{ request()->routeIs('encargado.punto-venta') ? 'active' : '' }}" href="{{ route('encargado.punto-venta') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                Punto de Venta
            </a>
            <!-- Ventas -->
            <a class="nav-link {{ request()->routeIs('encargado.ventas*') ? 'active' : '' }}"
               href="{{ route('encargado.ventas') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                Ventas
            </a>
            <a class="nav-link {{ request()->routeIs('encargado.control-productos') ? 'active' : '' }}" href="{{ route('encargado.control-productos') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                Control de productos
            </a>
            <a class="nav-link {{ request()->routeIs('encargado.movimientos') ? 'active' : '' }}" href="{{ route('encargado.movimientos') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                Movimientos
            </a>
            <a class="nav-link {{ request()->routeIs('encargado.inventario') ? 'active' : '' }}" href="{{ route('encargado.inventario') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                Inventario
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Sesión iniciada como:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name ?? 'Estudiante' }}</div>
        </div>
    </div>
</nav>
