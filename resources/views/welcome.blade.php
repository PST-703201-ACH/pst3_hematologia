<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hematología - Hospital J. M. de los Ríos</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Premium Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body>
    <header>
        <a href="/" class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Hospital Logo">
            <span>JM de los Ríos</span>
        </a>
        <div class="nav-actions">
            @auth
            <a href="{{ Auth::user()->getDashboardUrl() }}" class="btn btn-outline">Ir al Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
            @endauth
        </div>
    </header>

    <main>
        <div class="hero-content">
            <div class="badge">
                <span class="badge-pulse"></span>
                Servicio de Hematología
            </div>
            <h1>
                Sistema de Gestión<br>
                <span>Hematológica</span>
            </h1>
            <p class="hero-desc">
                Herramienta digital especializada para la administración, seguimiento y control de expedientes hematológicos pediátricos del Hospital de Niños Dr. J. M. de los Ríos. Facilitando la labor médica para salvaguardar la salud de nuestros niños.
            </p>
            <div class="hero-actions">
                @auth
                <a href="{{ Auth::user()->getDashboardUrl() }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">Acceder al Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">Iniciar Sesión</a>
                @endauth
            </div>
        </div>

        <div class="hero-image-container">
            <div class="card-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Hematología Logo">
                <h2>Hospital J. M. de los Ríos</h2>
                <p>Unidad de Hematología y Oncología Pediatrica</p>
            </div>
        </div>
    </main>

    <section class="context-section">
        <h2 class="section-title">Contexto & Trayectoria</h2>
        <div class="grid-context">
            <!-- Tarjeta 1: Historia -->
            <div class="card-info">
                <div class="card-info-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3>Fundación y Pioneros</h3>
                <p>
                    Fundado el <strong>2 de febrero de 1937</strong> como el Hospital Municipal de Niños de Caracas por el Dr. Gustavo H. Machado. Lleva su nombre en honor al ilustre Dr. José Manuel de los Ríos, precursor de la pediatría en Venezuela.
                </p>
            </div>

            <!-- Tarjeta 2: Hematología -->
            <div class="card-info">
                <div class="card-info-icon" style="background-color: rgba(225, 29, 72, 0.1); color: var(--color-accent);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <h3>Servicio de Referencia</h3>
                <p>
                    El Servicio de Hematología es el principal centro de referencia nacional para el diagnóstico, tratamiento e investigación de patologías hematológicas benignas y malignas en la población infantil venezolana.
                </p>
            </div>

            <!-- Tarjeta 3: Ubicación y Labor -->
            <div class="card-info">
                <div class="card-info-icon" style="background-color: rgba(2, 132, 199, 0.1); color: var(--color-primary);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <h3>Compromiso Social</h3>
                <p>
                    Ubicado en la Avenida Vollmer de San Bernardino, Caracas, el hospital abre sus puertas a niños y adolescentes de toda la geografía nacional, brindando atención médica especializada con mística y vocación de servicio.
                </p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} Hospital de Niños Dr. J. M. de los Ríos. Todos los derechos reservados. Desarrollado para el Servicio de Hematología.</p>
    </footer>
</body>

</html>