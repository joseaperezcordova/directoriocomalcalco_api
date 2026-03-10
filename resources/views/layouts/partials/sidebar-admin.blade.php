<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav">
            <div class="sidenav-menu-heading">Administrador</div>

            <!-- Dashboard -->
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                Dashboard
            </a>

            <div class="sidenav-menu-heading">Reportes</div>

            <a class="nav-link {{ request()->routeIs('admin.movimientos') ? 'active' : '' }}" href="{{ route('admin.movimientos') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                Movimientos
            </a>
            <!-- Inventario -->
            <a class="nav-link {{ request()->routeIs('admin.inventario') ? 'active' : '' }}"
               href="{{ route('admin.inventario') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                Inventario
            </a>
            <!-- Ventas -->
            <a class="nav-link {{ request()->routeIs('admin.ventas*') ? 'active' : '' }}"
               href="{{ route('admin.ventas') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                Ventas
            </a>
            <!-- Bitácora -->
            <a class="nav-link {{ request()->routeIs('admin.bitacora') ? 'active' : '' }}"
               href="{{ route('admin.bitacora') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                Bitácora
            </a>

            <!-- Cierres -->
            <a class="nav-link {{ request()->routeIs('admin.cierres') ? 'active' : '' }}"
               href="{{ route('admin.cierres') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                Lista Cierres
            </a>

            <div class="sidenav-menu-heading">Sistema</div>
            <!-- Configuración -->
            <a class="nav-link {{ request()->routeIs('admin.productos') ? 'active' : '' }}"
               href="{{ route('admin.productos') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                Productos
            </a>
            <!-- Usuarios -->
            <a class="nav-link {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}"
               href="{{ route('admin.usuarios') }}">
                <div class="nav-link-icon">
                    <i class="fas fa-users"></i>
                </div>
                Usuarios
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="sidenav-footer">
        <div class="small">Sesión iniciada como:</div>
        {{ auth()->user()->name ?? 'Administrador' }}
    </div>

</nav>
