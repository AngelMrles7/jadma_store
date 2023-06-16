@extends('layouts.app')


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
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        
        <style type="text/css">
            
        </style>
        
        <script type="text/javascript">
        
        </script>
        
    </head>
    
    <body>
    
        <div id="contenedor">
            
            <div id="contenedorcentrado">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="container">
                    <form method="POST" action="{{ route('password.email') }}">
                        <br><br>
                        @csrf
                        <label for="email" class="col-form-label text-white">{{ __('Correo electrónico ') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <br><br>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                        <br><br>

                        <p class="mt-5 mb-3 text-center">&copy; JADMA STORE</p>
                    </form>
                </div>

                <div class="division1"></div>

                <div id="derecho">
                    <a href="/"> <img src="{{asset('img/logo/logo_jadma.png')}}" width="220" height="170"></a>
                </div>
              
                </div>
            </div>
        </div>
        
    </body>
</html>


