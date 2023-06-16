<!doctype html>
<html lang="es">
    <head>
        
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario de registro</title>
        
        <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        
        <!-- Link hacia el archivo de estilos css -->
        <link rel="stylesheet" href="css/login.css">
        
        <style type="text/css">
            
        </style>
        
        <script type="text/javascript">
        
        </script>
        
    </head>
    
    <body>
    
        <div id="contenedor">
            
            <div id="contenedorcentrado">
                <div class="container">
                    <form id="loginform"  method="POST" class="{{ route('register') }}">
                        <br><br>
                        @csrf
                        <label for="name" class="col-form-label text-md-end">Nombre</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <br>
                        <label for="floatingInput" class="col-form-label" >Correo electronico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        <br>
                        <label for="floatingPassword" class="col-form-label">Contraseña</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <br>
                        <label for="password-confirm" class="col-md-4 col-form-label">{{ __('Confirma contraeña') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <br>

                        <div class="checkbox mb-3 text-center">
                            <label>
                                <input type="checkbox" value="remember-me"> Recuerdame
                            </label>
                        </div>
                        <br>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="w-100 btn btn-lg btn btn-outline-light text-center">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>                        
                        <p class="mt-5 mb-3 text-center">&copy; JADMA STORE</p>
                    </form>
                </div>

                <div class="division2"></div>

                    <div id="derecho2">
                    <a href="/"> <img src="{{asset('img/logo/logo_jadma.png')}}" width="220" height="170"></a>
                    </div>
              
                </div>
            </div>
        </div>
        
    </body>
</html>