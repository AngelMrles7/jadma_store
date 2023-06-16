@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1 class="text-center">Crear usuarios</h1>
@stop

@section('content')

    <div class="container">
        <form action="{{Route('users.store')}}" method="post">
            @if(count($errors)>0)
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <!-- Se hace un ciclo, donde se traen todos los errores ya sea uno o varios -->
                        @foreach($errors -> all() as $error)
                            <li>{{$error}}</li> 
                        @endforeach
                    </ul>
                </div>
            @endif
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input id='nombre' name="name" type="text" class="form-control" placeholder="Nombre de usuario">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electronico</label>
                <input type="email" name="email" id="correo" class="form-control"  placeholder="usuario@correo.com">
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña">
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Asiganar Rol</label>
                <select class="form-control" name="roles" id="rol">
                    @foreach($roles as $r)
                        <option value="{{$r -> id}}">{{$r->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="container-fluid h-100"> 
                <div class="row w-100 align-items-center">
                    <div class="col text-center">
                        <button  type="submit" class="btn btn-light regular-button">Enviar</button>
                    </div>	
                </div>
            </div>
        </form>
    </div>
   
@stop
@section('css')
    <link rel="stylesheet" href="{{asset('css/registro.css') }}">
@stop
@section('js')

@stop