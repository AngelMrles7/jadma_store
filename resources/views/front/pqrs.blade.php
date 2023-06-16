@extends('welcome')

@section('css')
    <link href="{{asset('css/pqrs.css')}}" rel="stylesheet" />
@stop

@section('content')
    <div class="container pqrs">
        <h1 class="text-center">Formulario PQRS</h1>
        @if(count($errors)>0)
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach($errors -> all() as $error)
                        <li>{{$error}}</li> 
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('pqrs.store')}}" method="post">
            @csrf
            <input type="hidden" name="id_usu" value='{{Auth::id()}}'>

            <div class="mb-3">            
                <label for="tipo" class="form-label">Seleccione el tipo de peticion</label>
                <select class="form-control" name="tipo" id='tipo' required><br><br>
                    <option value="">Seleccione</option>
                    @foreach ($tipos as $t)
                        <option value="{{$t->id}}">{{$t->nombre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="meeting-time" class="form-label">Fecha del evento</label>
                <input class="form-control" type="date" id="meeting-time" name="fecha" placeholder="Ingresa la fecha y hora del evento" required>    
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea id='descripcion' class="form-control" name="descripcion" rows="10" cols="50" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="contact"  class="form-label">¿Como desea que lo contactemos?</label>
                <select class="form-control" name="contacto" id="contact" required><br><br>
                    <option value="">Seleccione</option>
                    <option value="email">Correo electrónico</option>
                    <option value="telefono">Número de teléfono</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="img"  class="form-label">Soportes del caso</label>
                <input class="form-control" id="img" type="file" name="soporte">
            </div>
            <div class="container-fluid h-100"> 
                <div class="row w-100 align-items-center">
                    <div class="col text-center">
                        <button  type="submit" class="btn btn-dark regular-button">Enviar</button>
                    </div>	
                </div>
            </div>
          
        </form>
    </div>
@stop

@section('js')

@stop