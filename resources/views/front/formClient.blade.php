@extends('welcome')

@section('css')

@stop

@section('content')
    <div class="container mb-4">
        <h1 class="text-center">Registro cliente</h1>
    
        <form action="{{Route('clientes.store')}}" method="post">
            @if(count($errors)>0)
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach($errors -> all() as $error)
                            <li>{{$error}}</li> 
                        @endforeach
                    </ul>
                </div>
            @endif
            @csrf
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de documento</label>
                <select  id='tipo' class="form-control" name="tipo_dni" required>
                    <option value="{{old('tipo_dni')}}">Seleccione el tipo de documento</option>
                    <option value="cc">Cedula de ciudadania</option>
                    <option value="ti">Tarjeta de identidad</option>
                </select>
            </div>  
            <div class="mb-3">
                <label for="num" class="form-label">Documento de Identidad</label><br>
                <input id='num' class="form-control" type="number" name="num_ident" placeholder="Ingresa tu Numero de documento" value="{{old('num_ident')}}"required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label><br>
                <input id='apellido' class="form-control" type="text" name="apellido" placeholder="Ingresa tu apellido"  value="{{old('apellido')}}" required>
            </div>
    
            <div class="mb-3">
                <label for="ciudad" class="form-label">Ciudad de residencia</label><br>
                <input id='ciudad' class="form-control" type="text" name="ciudad" placeholder="Ingresa tu ciudad" value="{{old('ciudad')}}"required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Direccion de residencia</label><br>
                <input id='direccion' class="form-control" type="text" name="direccion" placeholder="Ingresa tu direccion de residencia" value="{{old('direccion')}}"required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Número de contacto</label><br>
                <input id='telefono' class="form-control" type="number" name="telefono" placeholder="Ingresa tu numero de telefono" value="{{old('telefono')}}"required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Fecha de Nacimiento</label><br>
                <input id='date' class="form-control" type="date" name="fecha_nacimiento" value="{{old('fecha_nacimiento')}}"required>
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