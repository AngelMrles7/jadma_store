@extends('welcome')


@section('css')
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{asset('css/index.css')}}" rel="stylesheet" />
    <link href="{{asset('css/card.css')}}" rel="stylesheet" />
    <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
@stop


@section('content')

        <!-- Header-->
       
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5 ">
                @foreach($solocategoria as $s)
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">{{$s->nombre}}</h1>
                    {{-- <p class="lead fw-normal text-white-50 mb-0">aqui van </p> --}}
                </div>
                @endforeach
            </div>
        </header>
        <!-- Section-->
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach($productos as $p)
                <div class="col mb-5">
                  <a href="{{Route('detalle', $p->id)}}">
                    <div class="card h-100">
                        <!-- Product image-->
                        <img class="card-img-top" src="{{asset($p->foto)}}" alt="imagen de producto" />
                        <!-- Product details-->
                        <div class="card-body p-2">
                           
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder text-justify">{{$p->nombre}}</h5>
                            </div>
                            <div class="text-center">
                                <!-- Product price-->
                                <span>$ {{ number_format($p->precio) }}</span>
                            </div>
                        </div>
                       <!-- Product actions-->
                       <div class="card-footer p-3 pt-0 border-top-0 bg-transparent ">
                            <div class="d-flex justify-content-center" >
                                <form action="{{route('carrito.store')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id_usu" value='{{Auth::id()}}'>
                                    <input type="hidden" name="id_pro" value='{{$p->id}}'>
                                    <input type="number" name="cantidad" value='1' hidden>
                                    <br>
                                    <button type="submit" class="btn btn-outline-dark mt-auto">
                                        <i class="bi-cart-fill me-1"></i>
                                            Agregar al carrito
                                    </button>
                                </form>
    
                            </div>
                        </div>
                    </div>
                  </a>
                </div>
                @endforeach
            </div>
        </div>
     
@stop

@section('js')
<link href="{{asset('js/scripts.js')}}" rel="stylesheet" />

@stop