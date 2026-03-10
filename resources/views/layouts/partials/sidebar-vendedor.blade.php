<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav">

            <div class="sidenav-menu-heading">Vendedor</div>

            <!-- Dashboard -->
            <a class="nav-link {{ request()->routeIs('vendedor.dashboard') ? 'active' : '' }}"
               href="{{ route('vendedor.dashboard') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                Dashboard
            </a>

            <!-- Punto de venta -->
            <a class="nav-link {{ request()->routeIs('vendedor.punto-venta') ? 'active' : '' }}" href="{{ route('vendedor.punto-venta') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                Punto de Venta
            </a>
            <!-- Ventas -->
            <a class="nav-link {{ request()->routeIs('vendedor.ventas*') ? 'active' : '' }}"
               href="{{ route('vendedor.ventas') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                Ventas
            </a>

            <!-- Inventario -->
            <a class="nav-link {{ request()->routeIs('vendedor.inventario') ? 'active' : '' }}" href="{{ route('vendedor.inventario') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                Inventario
            </a>

            <!-- Corte de caja -->
            <a class="nav-link {{ request()->routeIs('vendedor.corte*') ? 'active' : '' }}"
               href="{{ route('vendedor.corte') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                Corte de Caja
            </a>

        </div>
    </div>

    <div class="sidenav-footer">
        <div class="small">Sesión iniciada como:</div>
        {{ auth()->user()->name ?? 'Vendedor' }}
    </div>

</nav>