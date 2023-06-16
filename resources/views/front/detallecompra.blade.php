@extends('welcome')

@section('css')
<link rel="stylesheet" href="{{ asset('css/deta_compra.css') }}">
@stop

@section('content')
    <?php
        $carrito = session('cart');
        $x = json_encode($carrito);
    ?>
    <section class="deta-main">
        <div class="deta-compra">
            <div>
                <div class="deta-content">
                    <div class="deta-product">
                        <div class="factura mb-4">
                            <h3 class="pb-2 mb-4 border-bottom border-2">Resumen de pedido</h3>
                            <table class="table table-light">
                                <tr class="table-dark">
                                    <th>Productos</th>
                                    <th>Total</th>
                                </tr>
                                <?php ?>
                                <?php 
                                    foreach ($carrito as $key => $i ) { ?>
                              
                                    <tr>
                                        <td>
                                            <span><?php echo $i['nombre']?></span>
                                            <span> X <?php echo $i['cantidad']?></span>
                                        </td>
                                        <td>
                                           <span>$ <?php echo number_format($i['precio']* $i['cantidad'] )?></span>
                                        </td>
                                    </tr>
                             
                                <?php }?>
                                    <tr>
                                        <td>Impuesto 19%</td>
                                        <td>
                                            <span>$ {{number_format($iva)}}</span>
                                        </td>
                                    </tr>
                                    <tr class="table-dark">
                                        <td>Total</td>
                                        <td>$ {{number_format($total)}}</td>
                                    </tr>
                                
                            </table>
                        </div>
                        <div class="deta-envio">
                            @if($puntosCliente > 0)
                                <div class="envio-ciudad mb-3">
                                
                                    <div class="info-punt mb-3">
                                       <h5> Tus puntos: {{$puntosCliente}}</h5>
                                    </div>
                                    <form action="{{Route('carrito.updatePuntos')}}" method="post">
                                        @csrf
                                       
                                        <label class="form" for="ciudades">¿Utilizar puntos?</label>
                                        <input  value="{{old('puntos')}}" clas="form-control" type="number" min="0" name="puntos">
                                        <input  type="submit" class="btn btn-dark regular-button" value="utilizar">
                                        @if(isset($validar))
                                            @if($validar == false)
                                                <div class="text-danger"> 
                                                    Opps!! No cuentas con los puntos ingresados.
                                                </div>
                                            @endif
                                        @endif
                                      
                                    </form>
                                   
                                </div>
                            @endif
                            <div class="envio-ciudad form-floating  mb-3">
                                
                                <select class="form-select form-select-sm" name="ciudades" id="ciudades" onchange="select()">
                                    @foreach($ciudades as $ciu)
                                        <option value={{$ciu->id}}>{{$ciu->nombreCiudad}}</option>
                                    @endforeach
                                </select>
                                <label class="floatingSelect" for="ciudades">Ciudad de envio</label>
                            </div>
                            
                            <div class="envio-empresa form-floating mb-3">
                                <select class="form-select form-select-sm" name="empresa" id="emp" onchange="empresaSelect()">
                                    @foreach($empresas as $e)
                                        <option value={{$e->id_empresa}}>{{$e->nombre}}</option>
                                    @endforeach
                                </select>
                                <label class="floatingSelect" for="emp">Empresa transportadora</label>
                            </div>
                        </div>
                    </div>
                    <div class="deta-pago">
                        <h3 class="pb-2 mb-4 border-bottom border-2">Medio de pago</h3>
                        <div class="container-paypal">
                            <div id="paypal-button-container"></div>

                            <!-- Include the PayPal JavaScript SDK -->
                            <script src="https://www.paypal.com/sdk/js?client-id=ATBzpQRj1SsoA7onVXPaeJlzuLxgKfQQUFy_9tfc_WNsvaeAQegc_rIISAPPtVCOWi8y7FGB2EMjl8eD&currency=USD"></script>
                        
                            <script>
                                var idCiudad = document.getElementById("ciudades");
                                var idEmpresa = document.getElementById("emp");
                                var ciudad = idCiudad.options[idCiudad.selectedIndex].value;
                                var empresa = idEmpresa.options[idEmpresa.selectedIndex].value;
                                function select() {
                                   return ciudad = document.getElementById('ciudades').options[document.getElementById('ciudades').selectedIndex].value;
                                }
                               
                                function empresaSelect() {
                                   return empresa = document.getElementById('emp').options[document.getElementById('empresa').selectedIndex].value;
                                }
                                var carrito = @json($carrito);
                              
            
                                // Render the PayPal button into #paypal-button-container
                                paypal.Buttons({
                                   
                                    // Call your server to set up the transaction
                                    createOrder: function(data, actions) {
                                        
                                        return fetch('../api/paypal/order/create', {
                                            method: 'post',
                                            body:JSON.stringify({
                                                "value": {{$dolares}},
                                                'id':"{{$id_sebas}}",
                                                'empresanombre':empresa,
                                                'ciudadnombre':ciudad,
                                                'puntUtilizados':{{$puntosUtili}},
                                                'total':{{$total}},
                                                'carrito':carrito
                                                
                                            })
                                        }).then(function(res) {
                                            return res.json();
                                        }).then(function(orderData) {
                                            return orderData.id;
                                        });
                                    },
                        
                                    // Call your server to finalize the transaction
                                    onApprove: function(data, actions) {
                                        return fetch('../api/paypal/order/capture', {
                                            method: 'post',
                                            body: JSON.stringify({
                                                orderId : data.orderID,
                                                'id':"{{$id_sebas}}",
                                                'empresanombre':empresa,
                                                'ciudadnombre':ciudad,
                                                'puntUtilizados':{{$puntosUtili}},
                                                'total':{{$total}},
                                                'carrito':carrito
                                                
                                            })
                                        }).then(function(res) {
                                            return res.json();
                                        }).then(function(orderData) {
                                            // Three cases to handle:
                                            //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
                                            //   (2) Other non-recoverable errors -> Show a failure message
                                            //   (3) Successful transaction -> Show confirmation or thank you
                        
                                            // This example reads a v2/checkout/orders capture response, propagated from the server
                                            // You could use a different API or structure for your 'orderData'
                                            var errorDetail = Array.isArray(orderData.details) && orderData.details[0];
                        
                                            if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                                                return actions.restart(); // Recoverable state, per:
                                                // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
                                            }
                                            
                                            if (errorDetail) {
                                                var msg = 'Sorry, your transaction could not be processed.';
                                                if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                                                if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                                                return alert(msg); // Show a failure message (try to avoid alerts in production environments)
                                            }
                        
                                            // Successful capture! For demo purposes:
                                            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                                            var transaction = orderData.purchase_units[0].payments.captures[0];
                                            // alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
                                            
                                            // Replace the above to show a success message within this page, e.g.
                                            // const element = document.getElementById('paypal-button-container');
                                            // element.innerHTML = '';
                                            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                                        actions.redirect('{{Route("confirmar")}}');
                                        });
                                    }
                        
                                }).render('#paypal-button-container');
                            </script>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@section('js')

@stop
    

