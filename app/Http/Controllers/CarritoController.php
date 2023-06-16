<?php

namespace App\Http\Controllers;
use App\Models\Ciudades;
use App\Models\Empresa_transportadora;
use App\Models\Categorias;
use App\Models\Compras;
use COM;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;

use function GuzzleHttp\Promise\all;


class CarritoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
        
        return view('front.carrito',compact('categorias')); 
    }

  
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $product = DB::select('select * from productos where id='.$request->id_pro);
        $carrito = $request->session()->get('cart');
        
         
        if (isset($carrito[$request->id_pro])) {
            $carrito[$request->id_pro]['cantidad'] += $request->cantidad;
        }else {
            if ($request->cantidad > 1) {
                $carrito[$product[0]->id] = array(
                    'id_product' => $product[0]->id,
                    'nombre' => $product[0]->nombre,
                    'foto' => $product[0]->foto,
                    'precio' => $product[0]->precio,
                    'cantidad' => $request->cantidad,
                );
            }else{
                $carrito[$product[0]->id] = array(
                    'id_product' => $product[0]->id,
                    'nombre' => $product[0]->nombre,
                    'foto' => $product[0]->foto,
                    'precio' => $product[0]->precio,
                    'cantidad' => 1,
                );
            }
        
        }
    
        session(['cart' => $carrito]);

        // dd($carrito = $request->session()->all());

       return Redirect(route('carrito.index'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
       //
    }
    public function show( $id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carrito  $carrito
     * @return \Illuminate\Http\Response
     */
    public function convertirCOPTOUSD() {
        $data=(array) json_decode(file_get_contents("https://trm-colombia.vercel.app/?date=".date("Y-m-d"), true));
        $precio=($data["data"]->value);
        return $precio;
    }

    public function destroy($id)
    { 
        //Se obtine el valor de la session de carrito.
        $carrito =  session('cart');
        //se elimina el item de la session
        unset($carrito[$id]);
        session(['cart' => $carrito]);
        return redirect(route('carrito.index'));
    }
  

    public function updatePuntos(Request $request){
        $id_sebas = Auth::id();
        $puntos =  DB::select('select total_puntos from clientes where id_users = :id', ['id' => $id_sebas ]);
        $puntosCliente = $puntos[0]->total_puntos;
        $validar = false;
        if ($puntos > 0) {
            $subtotal = 0;
            $carrito =  session('cart');
            foreach ($carrito as $key => $value) {
                $subtotal += ($value['cantidad']*$value['precio']);
            }
            $iva = $subtotal * 0.19;
            $total = $subtotal +($subtotal * 0.19);
            $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
            $from="COP";
            $to="USD";
            $dollarPesoCol=$this->convertirCOPTOUSD();
            
            $total=(float) $total;
            $dolares=round($total/$dollarPesoCol,2);
            $ciudades=Ciudades::select('id','nombreCiudad')->get();
            $empresas=Empresa_transportadora::select('id_empresa','nombre')->get();
           
            if ($request ->puntos >= 0 && $request ->puntos <= $puntosCliente) {
                //Llamar a la variable de ssesion y descontar el valor de Totsl
                $validar = true;
                $total = ($subtotal +($subtotal * 0.19))-$request->puntos;
                $puntosUtili = $request->puntos;
                $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
                $from="COP";
                $to="USD";
                $dollarPesoCol=$this->convertirCOPTOUSD();
                
                $total=(float) $total;
                $dolares=round($total/$dollarPesoCol,2);
    
                return  view('front.detallecompra',['iva' =>$iva,'total'=>$total, 'puntosUtili' =>$puntosUtili], compact('subtotal','dolares', 'categorias','id_sebas','ciudades','validar','empresas','puntosCliente'));
            }else{
                
                return  view('front.detallecompra',['iva' =>$iva,'total'=>$total], compact('subtotal','dolares', 'categorias','id_sebas','ciudades','empresas', 'validar','puntosCliente'));
            }
            
        }else{
           
            return redirect()->back();
        }
    }

    public function detallecompra(Request $request){
      
        if ($request -> total == 0) {
            return Redirect(route('carrito.index'));
        }else{
            
            //Se definen las variales que van a ser utilizadas más adelante
            $subtotal = 0;
            $total = 0;
            //Se obtiene el valor de la session
            $carrito = $request->session()->get('cart');
            $id_sebas = Auth::id();

            if ($id_sebas == null) {
                return Redirect(route('login'));
            }else{
               
                foreach ($carrito as $key => $value) {
                    $subtotal += ($value['cantidad']*$value['precio']);
                }

                $total = $subtotal +($subtotal * 0.19);
    
              
                $id_cliente = DB::select('select id, total_puntos from clientes where id_users='.$id_sebas);
                
                if (count($id_cliente)>0) {
                    $puntos =  DB::select('select total_puntos from clientes where id_users = :id', ['id' => $id_sebas ]);
                    $puntosCliente = $puntos[0]->total_puntos;
                    $iva = $subtotal * 0.19;
                    $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
                    $from="COP";
                    $to="USD";
                    $precio=$request->total;
                    $dollarPesoCol=$this->convertirCOPTOUSD();
                    $puntosUtili = 0;
                    $total=(float) $total;
                    $dolares=round($total/$dollarPesoCol,2);
        
                    $ciudades=Ciudades::select('id','nombreCiudad')->get();
        
                    $empresas=Empresa_transportadora::select('id_empresa','nombre')->get();
                    return view('front.detallecompra',['iva' =>$iva,'total'=>$total, 'puntosUtili' =>$puntosUtili], compact('subtotal','dolares', 'categorias','id_sebas','ciudades','empresas','puntosCliente'));
                }else{
                    return Redirect(route('clientes.create'));
                }
            }
        }
        
    }

  
}
