<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Clientes;
use App\Models\Pqrs;
use App\Models\tipoPqrs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PqrsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = tipoPqrs::all();
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
        return view('front.pqrs',compact('categorias','tipos'));
    }

    public function allPqrs()
    {
        $tipos = DB::table('pqrs')
        ->join("clientes","clientes.id","=","pqrs.id_cliente")
        ->leftJoin("users as c", "c.id", "=", "clientes.id_users")
        ->leftJoin("users as us", "us.id", "=", "pqrs.users_id")
        ->join("tipopqrs","tipopqrs.id","=","pqrs.tipopqrs_id")
        ->select("c.name as nameCl","us.name" ,"pqrs.*","tipopqrs.nombre")
        ->get();
        
        return datatables()->of($tipos)->toJson();
    }
  
    public function gestion()
    {
        return view('backend/pqrs.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::id() == null) {
           return view('auth/login');
        }else{
            $date = Carbon::now();
            $date = $date->format('Y-m-d');
    
            $validarCampos = [
                'tipo' => 'required',
                'descripcion' => 'required|string|max:600|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
                'contacto' => 'required',
            ];
            $mensaje = [
                'tipo.required' => 'Por favor seleccione una opción',
                'tipo.contacto' => 'Por favor seleccione una opción',
                'descripcion.required' => 'Por favor agregar la descripción para poder dar solución',
                'descripcion.regex' => 'La descripción solo puede contener letras y números',
                'descripcion.max' => 'La descripción puede tener maximo 600 Caracteres',
                'contacto.required' => 'Por favor indicar el modo de pago'
            ];
            
            $this -> validate($request, $validarCampos, $mensaje);
            $id_client = Clientes::select('id')->where('id_users',Auth::id())->get();
           if (count($id_client)>0) {
              
                foreach ($id_client as $value) {
                    $id = $value->id;
                }
                $pqrs = new Pqrs();
                $pqrs->id_cliente = $id;
                $pqrs->	tipopqrs_id =  $request->input('tipo');
                $pqrs->fecha = $date;
                $pqrs-> fecha_event = $request->input('fecha');
                $pqrs->descripcion = $request ->input('descripcion');
                $pqrs->imagen = $request ->input('soporte');
                $pqrs->contacto = $request ->input('contacto');
                $pqrs -> save();
                return redirect('/')->with('crear', 'ok');
           }else{
                return Redirect(route('clientes.create'));
           }
        
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pqrs  $pqrs
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $datos = Pqrs::findOrfail($id);
        
        if($datos){
            return response()->json([
                'status' => 200,
                'user' => $datos,
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'La pqrs no existe'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pqrs  $pqrs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validarCampos = [
            'respuesta' => 'required|string|max:100|regex:/^\w\D[a-zA-Z-"ñÑ+.,áéíó ]+$/',
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'respuesta.required' => 'Por favor ingrese la respuesta al caso.',
            'respuesta.regex' => 'La respuesta solo puede contener letras.',
            'respuesta.max' => 'La respuesta puede tener maximo 100 Caracteres',
        ]);

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            DB::table('pqrs')->where('id','=',$id)->update([
                'respuesta' => $request->respuesta,
                'users_id' => $request->users_id
            ]);    
            return response()->json([
                'status' => 200
            ]);
        }
      
    }

   
}
