<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Ciudades;
use App\Models\Clientes;
use App\Models\Compras;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
        $ciudad = Ciudades::all();
        return view('front.formClient',compact('categorias','ciudad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validarCampos = [
            'tipo_dni'=>'required',
            'num_ident'=>'required|numeric|regex:/^[0-9]{7,10}$/',
            'apellido' => 'required|string|regex:/^[a-zA-Z-"ñÑ+.,áéíó ]+/',
            'ciudad' => 'required|string|regex:/^[a-zA-Z-"ñÑ+.,áéíó ]+/',
            'direccion' => 'required|string|max:100|regex:/^\w\D[a-zA-Z0-9-"ñÑ+.,áéíóú ]+$/',
            'telefono' => 'required|numeric|regex:/^[0-9]{7,10}$/',
            'fecha_nacimiento' => 'required'
        ];
        $mensaje = [
            'num_ident.required'=>'Por favor ingresa el número de documento',
            'fecha_nacimiento.required'=>'Ingresa tu fecha de nacimiento'
        ];

        $this -> validate($request, $validarCampos, $mensaje);
       
        $user = User::select('email')->where('id', Auth::id())->get();
        
        // $datos_client = $request->except('_token');
        foreach ($user as $value) {
            $email = $value->email;
        }
    
        $cliente = new Clientes();
        $cliente->id_users = Auth::id();
        $cliente->tipo_dni =  $request->input('tipo_dni');
        $cliente->num_ident = $request->input('num_ident');
        $cliente->email = $email; 
        $cliente-> apellido = $request->input('apellido');
        $cliente->ciudad = $request ->input('ciudad');
        $cliente->direccion = $request ->input('direccion');
        $cliente->telefono = $request ->input('telefono');
        $cliente->fecha_nacimiento = $request ->input('fecha_nacimiento');
        $cliente -> save();
        return redirect(route('carrito.index'))->with('creado', 'yes');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {     
        $user = User::select('name','email')->where('id','=',$id)->get();
        $datosClient = Clientes::select()->where('id_users','=',$id)->get();
        
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
        
        return view('front/perfil.index', compact('categorias','user','datosClient'));
    
    }

    public function mostrarCompra($id)
    {
        $id_client = Clientes::select('id')->where('id_users','=',$id)->get();
        if (count($id_client)>0) {
            $id_cliente = $id_client[0]->id;
            $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
            $historial = DB::table('compras')
            ->join('empresas_transportadora','id_empresa','=','compras.empresas_transportadora_id_empresa')
            ->join('ciudades as c','c.id','=','compras.ciudades_id')
            ->join('estados as est','est.id','=','compras.estados_id')
            ->select('compras.*','empresas_transportadora.nombre','c.nombreCiudad','est.tipo_estado')
            ->where('clientes_id','=',$id_cliente)->get();
            return view('/front/perfil.compras', compact('historial','categorias'));
        }else{
            return Redirect(route('clientes.create'));
        }
       
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validarCampos = [
            'tipo'=>'required',
            'num'=>'required|numeric',
            'apellido' => 'required|string',
            'ciudad' => 'required|string',
            'direccion' => 'required|string|max:100|regex:/^\w\D[a-zA-Z0-9-"ñÑ+.,áéíóú ]+$/',
            'telefono' => 'required|number',
            'date' => 'required'
        ];
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'tipo.required' => 'Por favor seleccione el tipo de Documento',
            'num.required'=>'Por favor ingresa el número de documento',
            'date.required'=>'Ingresa tu fecha de nacimiento'
        ]);

        $categoria = Clientes::where('id_users','=',$id)->first();
        $categoria -> num_ident = $request['num_ident'];
        $categoria -> apellido = $request['apellido'];
        $categoria -> direccion = $request['direccion'];
        $categoria -> telefono = $request['telefono'];
        $categoria -> fecha_nacimiento = $request['fecha_nacimiento'];
        $categoria -> save();
        return Redirect(route('clientes.show',$id))->with('act', 'ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
