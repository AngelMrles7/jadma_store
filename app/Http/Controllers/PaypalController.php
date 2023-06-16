<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Carrito;
use Illuminate\Http\Request;
use App\Models\Ciudades;
use App\Models\Empresa_transportadora;

use Srmklive\PayPal\Service\PayPal;

use App\Models\puntos;
use App\Models\Order;
use App\Models\compras;
use App\Models\detalles_compras;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;

class PaypalController extends Controller
{
    public function create(Request $request){

        $data=json_decode($request->getContent(), true);

        $provider= \PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token= $provider->getAccessToken();
        $provider->setAccessToken($token);
        $precio= Order::getprice($data['value']);
        $cliente_sebas = $data['id'];
        
        // dd($cliente_sebas);
        
        $order= $provider->createOrder([
            "intent"=>"CAPTURE",
            "purchase_units"=>[
                [
                    "amount"=>[
                        "currency_code"=> "USD",
                        "value"=>$precio
                    ]
                ]
                ]
        ]);

        
        return response()->json($order);
    }

    public function capture(Request $request){

        $data=json_decode($request->getContent(), true);

        $orderid=$data['orderId'];

        $provider= \PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token= $provider->getAccessToken();
        $provider->setAccessToken($token);
        $cliente_sebas = $data['id'];
        $nombreempresa=$data['empresanombre'];
        $nombreciudad=$data['ciudadnombre'];
        $puntUtilizados =$data['puntUtilizados'];
        $total =$data['total'];
        $carrito = $data['carrito'];
  

        $result =$provider->capturePaymentOrder($orderid);
        if ($result['status']=="COMPLETED"){
        
            $id= Clientes::select('id','total_puntos')->where("id_users","=",$cliente_sebas)->get();
            $idcliente = $id[0]->id;
            
            $date = Carbon::now();
            $date = $date->format('Y-m-d','UTC-5');
             
            // compra -------------  
            $compras = new compras();
            $fecha=Carbon::now();
            $compras->fechaVenta =  $date ;
            $compras->valorTotal=$total;
            $compras->empresas_transportadora_id_empresa=$nombreempresa;
            $compras->ciudades_id=$nombreciudad;
            $compras->estados_id=1;
            $compras->clientes_id=$idcliente;
            $compras->save();

            $compraActual = Compras::latest('id')->where('clientes_id',$idcliente)->first();
            
            //DETALLEEE ***************** *    ***** * ** * * *
            foreach ($carrito as $key => $value) {
                $product = DB::table('productos')->select('existencia')->where('id',$key)->get();
                $cantProduct =  $product[0]->existencia - $value['cantidad'];

                $detalles_compra= new detalles_compras();
                $detalles_compra->precioUnitario = $value['precio'];
                $detalles_compra->cantidaProduct = $value['cantidad'];
                $detalles_compra->subTotal = $value['precio']*$value['cantidad'];
                $detalles_compra->compras_id=$compraActual->id;
                $detalles_compra->productos_id = $key;
                $detalles_compra->tipoPago='Paypal';
                $detalles_compra->save();
                $puntosupdate=DB::table('productos')->where('id','=',$key)->update(['existencia'=>$cantProduct]); 

            }

            //Puntos
            $puntosporpeso= DB::table('puntos')->select('puntos')->get();
            $valorcompra=(int)$total;

            foreach ($puntosporpeso as $key) {
                $puntosporpeso=$key->puntos;
                $puntosporpeso=(int)$puntosporpeso;
            }

            $puntoscliente=($valorcompra/$puntosporpeso);
            $date = Carbon::now();
            $date = $date->format('Y-m-d');
    
            $categoriaCliente = DB::table('categoria_clients')->select("puntos_premio")
            ->where('tiempo','>',$date)
            ->where('valor_compras','<',$total)->get();
            
            if (count($categoriaCliente)>0) {
                // Actualizar el cliente en el campo puntos
                $totalPuntos =  $id[0]->total_puntos  + $categoriaCliente[0]->puntos_premio;
                $puntosupdate=DB::table('clientes')->where('id_users','=',$cliente_sebas)->update(['total_puntos'=>$totalPuntos]); 
            }
            

            $DBpuntoscliente=DB::table('clientes')->select('total_puntos')->where('id_users','=',$cliente_sebas)->get();
            foreach ($DBpuntoscliente as $key) {
                $DBpuntoscliente=$key->total_puntos;
                $DBpuntoscliente=(int)$DBpuntoscliente;
            }
            $DBpuntoscliente=$DBpuntoscliente+$puntoscliente;

            $puntosupdate=DB::table('clientes')->where('id_users','=',$cliente_sebas)->update(['total_puntos'=>$DBpuntoscliente]);
            if ($puntUtilizados >0) {
                $puntosClient=DB::table('clientes')->select('total_puntos')->where('id_users','=',$cliente_sebas)->get();
                $totalPunt = $puntosClient[0]->total_puntos -$puntUtilizados;
                $puntosupdate=DB::table('clientes')->where('id_users','=',$cliente_sebas)->update(['total_puntos'=>$totalPunt]);
            }
          
        }

        return response()->json($result);
    }
}
