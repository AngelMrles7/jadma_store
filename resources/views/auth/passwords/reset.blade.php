<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recuperar contraseña</title>
        
        <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        
        <!-- Link hacia el archivo de estilos css -->
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
            
        <style type="text/css"></style>
        
        <script type="text/javascript">
        
        </script>
        
    </head>
    
    <body>
    
        <div id="contenedor">
            
            <div id="contenedorcentrado">
                <div class="container">
                    <form id="loginform"  method="POST" action="{{ route('password.update') }}">
                        <br><br>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <label for="email" class=" col-form-label">{{ __('Correo electrónico') }}</label>
                    
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        
                        <br>
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">       
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
       
                        <br>

                        <label for="password-confirm" class="col-form-label text-md-end">{{ __('Confirmar contraseña') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <br>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>                        <br><br>

                        <p class="mt-5 mb-3 text-center">&copy; JADMA STORE</p>
                    </form>
                </div>

                <div class="division3"></div>

                <div id="derecho">
                    <a href="/"> <img src="{{asset('img/logo/logo_jadma.png')}}" width="220" height="170"></a>
                </div>
              
                </div>
            </div>
        </div>
        
    </body>
</html>