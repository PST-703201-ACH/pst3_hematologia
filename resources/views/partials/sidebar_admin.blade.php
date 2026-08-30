@php
    $usuarioActual = Auth::user();
    $personaActual = $usuarioActual?->persona;
    $nombreSidebar = trim(($personaActual->nombres ?? '') . ' ' . ($personaActual->apellidos ?? '')) ?: 'Usuario';
    $cedulaSidebar = $personaActual->cedula ?? 'Sin cédula';
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <img src="{{ asset('assets/adminlte/img/logo.png') }}"
             alt="S.H Dr. Walles C."
             class="brand-image img-circle elevation-3"
             style="opacity: .8">

        <span class="brand-text font-weight-light">S.H Dr. Walles C.</span>
    </a>

    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="mr-3">
                <i class="fas fa-user fa-3x text-secondary"></i>
            </div>

            <div class="d-flex flex-column">
                <h5 class="d-block text-success mb-0">
                    {{ $nombreSidebar }}
                </h5>
                <h5 class="d-block text-primary mb-0">
                    {{ $cedulaSidebar }}
                </h5>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <li class="nav-header">OPCIONES</li>

                <li class="nav-item">
                    <a href="#"
                       class="nav-link" 
                       onclick="event.preventDefault();" 
                       id="btnUsuarios">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#"
                       class="nav-link" 
                       onclick="event.preventDefault();" 
                       id="btnAuditoria">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Auditoria</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout"
                       class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Cerrar Sesion</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>