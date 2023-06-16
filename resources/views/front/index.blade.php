@extends('welcome')


@section('css')
<link href="{{asset('css/banner.css')}}" rel="stylesheet" />
<link href="{{asset('css/card.css')}}" rel="stylesheet" />
@stop


@section('content')

  <div id="myCarousel" class="carousel carousel-dark container px-4 px-lg-5 mt-5" data-bs-ride="carousel">
    
    <div class="carousel-inner">
      @forelse ($banners as $item)
        <div class="carousel-item @if($loop->index == 0) active @endif">
          <div class="img-carrousel">
            <img src="{{asset($item->imagen)}}" class="bd-placeholder-img img-banner" alt="">
          </div>
         
        </div>
      @empty
      @endforelse
    </div>
    <button class="carousel-control-prev text-dark" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon text-dark" aria-hidden="true"></span>
      <span class="visually-hidden">Next
      </span>
    </button>
  </div>


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
                            <input type="hidden" name="id_pro" value='{{$p->id}}'>
                            <input type="number" name="cantidad" value='1' hidden>
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

  <section class="container px-4 px-lg-5 mt-4">
    <div class="conten-products ">
      <div class="text-product mb-5">
        <h2 class="text-center">Nuevos Productos </h2>
      </div>
      <div class="products row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        @foreach($newProducts as $n)
        <div class="col mb-5">
          <a href="{{Route('detalle', $n->id)}}">
            <div class="card h-100">
                <!-- Product image-->
                <img class="card-img-top" src="{{asset($n->foto)}}" alt="imagen de producto" />
                <!-- Product details-->
                <div class="card-body p-2">
                   
                    <div class="text-center">
                        <!-- Product name-->
                        <h5 class="fw-bolder text-justify">{{$n->nombre}}</h5>
                    </div>
                    <div class="text-center">
                        <!-- Product price-->
                        <span>$ {{ number_format($n->precio) }}</span>
                    </div>
                </div>
               <!-- Product actions-->
               <div class="card-footer p-3 pt-0 border-top-0 bg-transparent ">
                    <div class="d-flex justify-content-center" >
                        <form action="{{route('carrito.store')}}" method="post">
                            @csrf
                            <input type="hidden" name="id_pro" value='{{$n->id}}'>
                            <input type="number" name="cantidad" value='1' hidden>
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
  </section>



@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if (session('compra') == 'ok')
      Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Gracias por su compra',
          showConfirmButton: false,
          timer: 2200
      })
  @endif    
  
</script>


@stop