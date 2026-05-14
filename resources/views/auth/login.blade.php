    @extends('adminlte::auth.login')

    @section('auth_header')
    @stop

    @section('css')

        <style>
            body.login-page, body.register-page {
                background: url("{{ asset('img/fondo-login.jpg') }}") no-repeat center center fixed !important;
                background-size: cover !important;
            }
            .login-logo { display: none !important; }
            .login-box {
                width: 320px !important;
                max-width: 90% !important;
            }

            .login-box .card {
                border-radius: 15px !important;
                overflow: hidden;
                background: rgba(255, 255, 255, 0.75) !important;

            }
            .login-box .card {
                background: rgba(255, 255, 255, 0.50) !important;
                
                -webkit-backdrop-filter: blur(10px) !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2) !important;
            }

            .login-box .card-header,
            .login-box .card-body,
            .login-card-body {
                background: transparent !important;
            }

            .login-box .input-group-text {
            background: rgba(255, 255, 255, 0.5) !important;
            border-left: none !important;
            }
        </style>

    @endsection

    @section('auth_body')
        <div class="text-center mb-4">
            <img src="{{ asset(config('adminlte.logo_img')) }}" alt="Logo" height="50" class="mb-2">
            <h4 class="font-weight-bold text-dark">{!! config('adminlte.logo') !!}</h4>
        </div>
        <form action="{{ route('login') }}" method="post">
            @csrf

            {{-- Username field --}}
            <div class="input-group mb-3">
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" placeholder="Cedula de identidad" autofocus autocomplete="off">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Contraseña">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Login button --}}
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn') }}">
                        <span class="fas fa-sign-in-alt"></span>
                    </button>
                </div>
            </div>
        </form>

 <div class="modal fade" id="nuevaClave" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-dark"> {{-- text-dark asegura la legibilidad sobre la tarjeta transparente --}}
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Restablecer contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modalOlvido">
                    <input type="text" name="olClave" id="olCedula" placeholder="Cedula" class="form-control">
                    <button class="btn btn-success" type="button" id="btnOlvide"></button>
                    <p id="olEmail"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    @stop

    @section('auth_footer')
        {{-- No mostramos el link de registro tal como se solicitó --}}
    @stop
