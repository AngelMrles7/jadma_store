@extends('welcome')


@section('css')
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@stop

@section('content')
<div class="main">
    <div class="container">
        <div class="edit-template">
            <div class="main-section">
                <div class="side-nav">
                    <div class="optionPerfil">
                    
                        <div class="imgPerfil">
                            <img src="{{asset('img/logo/curriculum.png')}}" alt="Editar perfil" width="120px">
                        </div>
                        <h2>Mi perfil</h2>
                        <h2> 
                            @foreach ($user as $item)
                            {{$item->name}}
                            @endforeach
                        </h2>
                        <ul class="list">
                            <li><a href="{{Route('clientes.mostrarCompra',Auth::id())}}">Mis Compras</a></li>
                        </ul>
                    </div>
                </div>
                <div class="formulario">
                    <form action="{{route('clientes.actualizar',Auth::id())}}" method="post" class="form">
                        @csrf
                        @foreach ($datosClient as $d)
                        
                        <div class="div-input">
                            <label for="nombre">Número de identidad</label>
                            <input type="text" name="num_ident" id="" value="{{$d->num_ident}}">
                        </div>
                        <div class="div-input">
                            <label for="nombre">Apellido</label>
                            <input type="text" name="apellido" id="" value="{{$d->apellido}}"">
                        </div>
                        <div class="div-input">
                            <label for="correo">Correo Electronico</label>
                            @foreach ($user as $item)
                            <input type="text" name="email" id="correo" value="{{$item->email}}" disabled>
                            @endforeach
                            
                        </div>
                    
                        <div class="div-input">
                            <label for="direccion">Dirección</label>
                            <input type="text" name="direccion" id="direccion" value="{{$d->direccion}}">
                        </div>
                        <div class="div-input"> 
                            <label for="tel">Número telefonico</label>
                            <input type="text" name="telefono" id="tel" value="{{$d->telefono}}">
                        </div>
                        <div class="div-input">
                            <label for="fechaNaci">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fechaNaci" value="{{$d->fecha_nacimiento}}">
                        </div>
                        
                        <div class="div-input">
                            <label for="nombre">Puntos acumulados</label>
                            <input type="text"id="" value="{{$d->total_puntos}}" disabled>
                        </div>
                    
                        <div class="col text-center">
                            <input  type="submit" class="btn btn-dark regular-button" value="Editar">
                        </div>	
                      
                    @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
   
</div>
@stop

@section('js')
    {{-- <script>
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: "clientes/"+product_id+"/edit",
            data: Auth::id(),
            success: function(res){
                if (res.status == 400) {
                    alert(res.message);
                    $('#modalCRUD').modal('hide');
                    
                }
            }
        })
    </script> --}}
@stop