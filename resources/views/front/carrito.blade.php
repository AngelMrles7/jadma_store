@extends('welcome')


@section('css')
<link rel="stylesheet" href="{{ asset('css/carritoStyle.css') }}">
@stop


@section('content')
<?php
    $carrito = session('cart'); 
?>
  <div class="main-cart">
    <div class="cart">
        <div>
            <h1 class="cart-titulo">Carrito de compras</h1>
            <div class="cart-content">
                <div class="cart-product">
                    <div class="product-deta">
                        <div class="detalles">
                            <div class="car-items">
                                <ul class="item-list">
                                  
                                    <?php $total = 0;
                                    $subtotal = 0;
                                    $iva = 0;
                                        if (isset($carrito) && count($carrito)>0) {
                                           
                                       
                                        foreach ($carrito as $key => $i ) { ?>
                                        
                                        <li class="list">
                                           
                                            <div class="list-deta">
                            
                                                <div class=" img-product">
                                                    <a href="{{Route('detalle', $key)}}">
                                                        <img class="imagen_product" src="<?php echo $i['foto']?>" alt="" height="15px" width="105px">
                                                    </a>
                                                </div>
                                                <div class="info-product">
                                                    <span class="item-name">
                                                        <?php echo $i['nombre']?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="item-precio">
                                                <div class="precio-product">
                                                    <?php 
                                                        $subtotal += $i['precio']*$i['cantidad'];
                                                        $iva = $subtotal * 0.19;
                                                        $total = $subtotal + $iva;
                                                    ?>
                                                    <span>Precio unitario</span>
                                                    <span>$  <?php  echo number_format($i['precio'])?></span>
                                                </div>
                                            </div>
                                            <div class="cantidad">
                                                <div class="aumentar">
                                                    <form action="">
                                                        <label for="cantiad">Cantidad</label>
                                                        <select class="form-control" name="" id="cantiad">
                                                            <option value=""><?php  echo $i['cantidad']?></option>

                                                        </select>
                                                    </form>
                                                </div>
                                                <div class="eliminar">
                                                    <form class="FormDelete" action="{{Route('carrito.destroy',$key)}}" method="post">
                                                        @csrf
                                                        {{method_field('DELETE')}}
                                                        <button type="submit" class="btn-close" aria-label="Close"></button>
                                                    </form> 
                                                </div>
                                            </div>
                                          
                                        </li>
                                    <?php  }}else{
                                        echo "<h4 class='mensaje'>"."Tu carrito de compras está vacío."."</h4";
                                    } ?>
                                
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-detalle">
                    <div class="detalle-content">
                        <div class="conten-suma">
                            <div class="detalle-suma">
                            <h2 class="mycart">Mi carrito</h2>
                                <div class="subtotal">
                                <span>Subtotal</span>
                                <span>$ <?php echo number_format($subtotal) ?></span>
                                </div>
                                <div class="iva">
                                    <span>Iva 19%</span>
                                    <span>$ <?php echo number_format($iva) ?></span>
                                    </div>
                            </div>
                            <hr class="linea">
                            <div class="total-pagar">
                                <div class="valor-total">
                                    <span>Total</span>
                                    <span>$ <?php echo number_format($total) ?></span>
                                </div>
                            </div>
                            <form action="{{Route('carrito.detallecompra')}}" method="post">
                                @csrf   
                                <input type="hidden" name="total" value='<?php echo number_format($total)  ?>'>
                                <div class="btn-pago">
                                    <button class="btn-pagar">Pagar</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

@stop

@section('js')
<script>
    @if (session('creado') == 'yes')
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Creado Correctamente',
            showConfirmButton: false,
            timer: 2600
        })
    @endif    
</script>
@stop