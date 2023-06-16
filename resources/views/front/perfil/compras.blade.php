@extends('welcome')


@section('css')

@stop

@section('content')
<h1 class="text-center">Tus Compras</h1>

<div class="container">
<table class="table" id="productos">
    <thead >
        <tr class="text-center">
            <th scope="col">Fecha de Compra</th>
            <th scope="col">Total</th>
            <th scope="col">Empresa transportadora</th>
            <th scope="col">Ciudad de entrega</th>
            <th scope="col">Estado de compra</th>
        </tr>
    </thead>
   
    <tbody> 
        @foreach ($historial as $h)
        <tr class="text-center">
          <td>{{$h->fechaVenta}}</td>
          <td>${{ number_format($h->valorTotal) }}</td>
          <td scope="row">{{$h->nombre}} </td>
          <td>{{$h->nombreCiudad}}</td>
          <td>{{ $h->tipo_estado}}</td>
        </tr>
        @endforeach
  </tbody>
  
</table> 
</div>
@stop

@section('js')
    
@stop