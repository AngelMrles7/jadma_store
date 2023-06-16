@extends('welcome')


@section('css')
    <link href="{{asset('css/detalle.css')}}" rel="stylesheet" />
    <link href="{{asset('js/detalle.js')}}" rel="stylesheet" />
@stop


@section('content')
 

       
        <!-- Product section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="{{asset($productos->foto)}}" width="700" height="600"/></div>
                    <div class="col-md-6">
                        <div class="small mb-1">Existencia {{$productos->existencia}}</div>
                        <h1 class="display-5 fw-bolder">{{$productos->nombre}}</h1>
                        <div class="fs-5 mb-5">
                            <!-- <span class="text-decoration-line-through">$45.00</span> -->
                            <span>$ {{ number_format($productos->precio) }}</span>
                        </div>
                        <p class="lead">{{$productos->descripcion}}</p>
                        <div class="d-flex">
                            <form action="{{route('carrito.store')}}" method="post">
                                @csrf
                                <input type="hidden" name="id_pro" value='{{$productos->id}}'>
                                <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem" name="cantidad"/>
                                <br>
                               
                                <button type="submit" class="btn btn-outline-dark mt-auto">
                                <i class="bi-cart-fill me-1"></i>
                                    Agregar al Carrito
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- Related items section-->    


@stop

@section('js')


@stop