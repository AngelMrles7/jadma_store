
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title> Inciar Session</title>    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        
        <!-- Link hacia el archivo de estilos css -->
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        
        <style type="text/css"></style>
        
        <script type="text/javascript"></script>
    </head>
    
    <body>
        <div id="contenedor">
            <div id="contenedorcentrado">
                <div class="container">
                    <form  action="{{ route('login') }}" method="POST"  id="loginform" >
                        @csrf
                        <br><br>
                        <!-- ingresar usuario -->
                        <label for="floatingInput" class="col-form-label" >Correo electronico</label>
                        <!-- input -->
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <br>
                        <!-- contraseña -->
                        <label for="floatingPassword" class="col-form-label">Contraseña</label>
                        <!-- ingresar contraseña -->
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                        <br>

                        <div class="checkbox mb-3 text-center">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                            {{ __('Recuerdame') }}
                            </label>
                        </div>
                     
                        <button type="submit" class="w-100 btn btn-lg btn btn-outline-light text-center">
                            {{ __('Ingresar') }}
                        </button>
                        <br>
                        <br>
                        <div>
                            <a  class="mb-3 w-100 btn btn-lg btn btn-outline-light text-center" href="{{ route('register') }}" class="dropdown-item ">Registrarse</a>
                        </div>
                       
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste la contraseña?') }}
                            </a>
                        @endif

                        <!-- footer -->
                        <p class="mt-5 mb-3 text-center">&copy; JADMA STORE</p>
                       
                    </form>
                </div>

                <div class="division"></div>

                <!-- imagen del lado derechos -->
                <div id="derecho">
                  <img src="img/logo/logo_jadma.png" width="200" height="150">
                </div>

            </div>
        </div>
    </body>
</html>

