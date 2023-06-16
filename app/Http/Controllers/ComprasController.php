<?php

namespace App\Http\Controllers;

use App\Models\Compras;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/envios.index');
    }
    
    public function allCompras()
    {
        $compras = DB::table('compras')
        ->join('empresas_transportadora','id_empresa','=','compras.empresas_transportadora_id_empresa')
        ->join('ciudades as c','c.id','=','compras.ciudades_id')
        ->join('estados as est','est.id','=','compras.estados_id')
        ->join('clientes','clientes.id','=','compras.clientes_id')
        ->select('compras.*','empresas_transportadora.nombre','c.nombreCiudad','est.tipo_estado','clientes.num_ident')
        ->get();
      
        return datatables()->of($compras)->toJson();
    }

    public function tipo()
    {
        $estado = DB::table('estados')->select('id','tipo_estado')->get();
        return $estado;
    }

    public function edit($id)
    {
        $compra = Compras::select('estados_id')->where('id',$id)->get();
        if($compra){
            return response()->json([
                'status' => 200,
                'estado' => $compra
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'La compra no existe'
            ]);
        }
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
            'estados_id' => 'required',
        ];
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'estados_id.required' => 'Por favor ingrese el estado de la venta',
        ]);
        $estado = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            DB::table('compras')->where('id','=',$id)->update([
                'estados_id' => $request->estados_id
            ]);   
            return response()->json([
                'status' => 200
            ]);
        }
    }
    
}
