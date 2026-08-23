    @extends('adminlte::auth.login')

    @section('auth_header')
    @stop

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/login-estilos.css') }}">
    @endsection

    @section('auth_body')
    <div class="text-center mb-4">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="100" width="100" class="mb-2 rounded-circle bg-white p-1 shadow-sm" style="object-fit: contain;">
    <h4 class="font-weight-bold text-dark">Iniciar sesion</h4>
</div>
    <form action="{{ route('login') }}" method="post" id="formLogin">
        @csrf

        <label>Usuario</label>
        <div class="input-group mb-3">
            <input type="text" name="username" id="cedula" class="form-control @error('username') is-invalid @enderror"
                value="{{ old('username') }}" placeholder="Cedula de identidad" autofocus autocomplete="off">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

        </div>

        <label>Contraseña</label>
        <div class="input-group mb-3">
            <input type="password" name="password" id="clave" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña">

            <div class="input-group-append">
                <div class="input-group-text" style="cursor: pointer;" id="togglePassword">
                    <span class="fas fa-eye" id="eyeIcon"></span>
                </div>
            </div>
        </div>

        <div class="input-group mb-2">
            <button type="button" id="olBtn" class="btn btn-secundar">Olvidaste tu contraseña?
                </button>
                <br>
        </div>

        <div id="alertLogin">

        </div>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn') }}">
                    <span class="fas fa-sign-in-alt"></span>
                </button>
            </div>
        </div>
    </form>

    <div class="modal fade" id="cambioClave" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Actualizacion de contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formClave">
                        <label>Usuario</label>
                        <div class="input-group mb-3">
                            <input type="text" name="camCedula" id="camCedula" class="form-control" readonly>
                        </div>
                        <label>Nueva contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" name="camClave" id="camClave" placeholder="Ingrese su nueva contraseña" class="form-control">
                            <button type="button" class="btn btn-secondary fas fa-eye-slash" id="btnNueva"></button>
                        </div>
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
    @stop

    <div class="modal fade" id="nuevaClave" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Restablecer contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formOlvido">
                        <label>Usuario</label>
                        <div class="input-group mb-3">
                            <input type="text" name="olClave" id="olCedula" placeholder="Numero de cedula de identidad" class="form-control">
                        </div>
                        <button class="btn btn-success" type="submit" id="btnOlvide">Recuperar contraseña</button>
                        <p id="olEmail">

                        </p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCarga" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1" style="z-index: 1060 !important;">
        <div class="modal-dialog modal-dialog-centered" style="z-index: 1061 !important;">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">Cargando...</h5>
                    <p class="text-muted">Por favor, no cierres la ventana.</p>
                </div>
            </div>
        </div>
    </div>

    @section('js')
    <script src="{{ asset('js/login.js') }}"></script>
    @stop

    @section('auth_footer')

    @stop
