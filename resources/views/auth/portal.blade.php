<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hematología - Hospital J. M. de los Ríos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>

<body class="portal-page">
    <div class="portal-shell">
        <section class="portal-welcome" aria-label="Bienvenida al Servicio de Hematología">
            <header class="portal-header">
                <a href="/" class="portal-brand">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo del Hospital J. M. de los Ríos">
                    <span>Hospital J. M. de los Ríos</span>
                </a>

                @auth
                    <a href="{{ Auth::user()->getDashboardUrl() }}" class="portal-header-link">Ir al panel <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                @else
                    <a href="#portal-login" class="portal-header-link">Iniciar sesión <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                @endauth
            </header>

            <main class="portal-welcome-content">
                <section class="portal-hero">
                    <div class="portal-hero-copy">
                        <div class="portal-badge">
                            <span class="portal-badge-pulse"></span>
                            Servicio de Hematología
                        </div>
                        <h1>Sistema de Gestión <span>Hematológica</span></h1>
                        <p class="portal-hero-description">
                            Herramienta digital especializada para la administración, seguimiento y control de expedientes hematológicos pediátricos del Hospital de Niños Dr. J. M. de los Ríos. Facilitando la labor médica para salvaguardar la salud de nuestros niños.
                        </p>
                        <a class="portal-scroll-link" href="#portal-context">
                            Conoce nuestro servicio <i class="fas fa-arrow-down" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="portal-hospital-card">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="">
                        <h2>Hospital J. M. de los Ríos</h2>
                        <p>Unidad de Hematología y Oncología Pediátrica</p>
                    </div>
                </section>

                <section class="portal-context" id="portal-context">
                    <h2 class="portal-section-title">Contexto y trayectoria</h2>
                    <div class="portal-context-grid">
                        <article class="portal-info-card">
                            <div class="portal-info-icon">
                                <i class="fas fa-book-open" aria-hidden="true"></i>
                            </div>
                            <h3>Fundación y pioneros</h3>
                            <p>
                                Fundado el <strong>2 de febrero de 1937</strong> como el Hospital Municipal de Niños de Caracas por el Dr. Gustavo H. Machado. Lleva su nombre en honor al ilustre Dr. José Manuel de los Ríos, precursor de la pediatría en Venezuela.
                            </p>
                        </article>

                        <article class="portal-info-card">
                            <div class="portal-info-icon portal-info-icon-rose">
                                <i class="fas fa-heartbeat" aria-hidden="true"></i>
                            </div>
                            <h3>Servicio de referencia</h3>
                            <p>
                                El Servicio de Hematología es el principal centro de referencia nacional para el diagnóstico, tratamiento e investigación de patologías hematológicas benignas y malignas en la población infantil venezolana.
                            </p>
                        </article>

                        <article class="portal-info-card">
                            <div class="portal-info-icon portal-info-icon-sky">
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            </div>
                            <h3>Compromiso social</h3>
                            <p>
                                Ubicado en la Avenida Vollmer de San Bernardino, Caracas, el hospital abre sus puertas a niños y adolescentes de toda la geografía nacional, brindando atención médica especializada con mística y vocación de servicio.
                            </p>
                        </article>
                    </div>
                </section>

                <footer class="portal-footer">
                    &copy; {{ date('Y') }} Hospital de Niños Dr. J. M. de los Ríos. Todos los derechos reservados. Desarrollado para el Servicio de Hematología.
                </footer>
            </main>
        </section>

        <aside class="portal-login" id="portal-login" aria-label="Acceso al sistema">
            @guest
                <div class="portal-login-card">
                    <div class="portal-login-heading">
                        <span class="portal-login-mark"><i class="fas fa-user-md" aria-hidden="true"></i></span>
                        <h2>Bienvenido</h2>
                        <p>Inicia sesión para continuar</p>
                    </div>

                    <form action="{{ route('login') }}" method="post" id="formLogin">
                        @csrf

                        <div class="portal-field">
                            <label for="cedula">Usuario</label>
                            <div class="portal-input-wrap">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <input
                                    type="text"
                                    name="username"
                                    id="cedula"
                                    value="{{ old('username') }}"
                                    placeholder="Cédula de identidad"
                                    autocomplete="username"
                                    autofocus
                                    required
                                >
                            </div>
                            @error('username')
                                <span class="portal-field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="portal-field">
                            <label for="clave">Contraseña</label>
                            <div class="portal-input-wrap">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                <input
                                    type="password"
                                    name="password"
                                    id="clave"
                                    placeholder="Contraseña"
                                    autocomplete="current-password"
                                    required
                                >
                                <button type="button" class="portal-password-toggle" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
                                    <i class="fas fa-eye" id="eyeIcon" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="portal-field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="portal-login-options">
                            <label class="portal-remember">
                                <input type="checkbox" name="remember" value="1">
                                <span>Recordarme</span>
                            </label>
                            <button type="button" id="olBtn" class="portal-forgot-link">¿Olvidaste tu contraseña?</button>
                        </div>

                        <div id="alertLogin" aria-live="polite"></div>

                        <button type="submit" class="portal-submit">
                            Ingresar al sistema <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                        </button>
                    </form>

                    <p class="portal-login-note">Acceso exclusivo para personal autorizado</p>
                </div>
            @else
                <div class="portal-login-card portal-authenticated-card">
                    <span class="portal-login-mark"><i class="fas fa-check" aria-hidden="true"></i></span>
                    <h2>Ya tienes una sesión activa</h2>
                    <p>Continúa al panel de trabajo para acceder al sistema.</p>
                    <a href="{{ Auth::user()->getDashboardUrl() }}" class="portal-submit portal-dashboard-link">
                        Ir al panel <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            @endguest
        </aside>
    </div>

    @guest
        <div class="modal fade" id="cambioClave" tabindex="-1" role="dialog" aria-labelledby="modalCambioClaveLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCambioClaveLabel">Actualización de contraseña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formClave">
                            <label for="camCedula">Usuario</label>
                            <div class="input-group mb-3">
                                <input type="text" name="camCedula" id="camCedula" class="form-control" readonly>
                            </div>
                            <label for="camClave">Nueva contraseña</label>
                            <div class="input-group mb-3">
                                <input type="password" name="camClave" id="camClave" placeholder="Ingrese su nueva contraseña" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary fas fa-eye-slash" id="btnNueva" aria-label="Mostrar u ocultar contraseña"></button>
                                </div>
                            </div>
                            <label for="camVerificar">Confirmar nueva contraseña</label>
                            <div class="input-group mb-3">
                                <input type="password" name="camVerificar" id="camVerificar" placeholder="Ingrese otra vez su nueva contraseña" class="form-control">
                            </div>
                            <button class="btn btn-success" type="submit" id="btnClave">Actualizar contraseña</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="nuevaClave" tabindex="-1" role="dialog" aria-labelledby="modalNuevaClaveLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevaClaveLabel">Restablecer contraseña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formOlvido">
                            <label for="olCedula">Usuario</label>
                            <div class="input-group mb-3">
                                <input type="text" name="olClave" id="olCedula" placeholder="Número de cédula de identidad" class="form-control">
                            </div>
                            <button class="btn btn-success" type="submit" id="btnOlvide">Recuperar contraseña</button>
                            <p id="olEmail" aria-live="polite"></p>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCarga" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div>
                        <h5 class="mt-3 text-dark">Cargando...</h5>
                        <p class="text-muted">Por favor, no cierres la ventana.</p>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('assets/js/login.js') }}"></script>
    @endguest
</body>

</html>
