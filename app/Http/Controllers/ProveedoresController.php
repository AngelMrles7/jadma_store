<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/proveedores.index');
    }

    public function allProveedrs()
    {
        $proveedores = Proveedores::all();
    
        return datatables()->of($proveedores)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $validarCampos = [
            'name' => 'required|string|max:100|regex:/^\w\D[a-zA-Z0-9-"ñÑ+.,áéíóú ]+$/',
            'direccion' => 'required|string|max:500|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => 'required|numeric',
            'razonSocial' => 'required',
            'nit' => 'required',
        ];
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
          'name.required' => 'El nombre es requerido',
          'name.max' => 'El nombre no debe contener más de 100 caracteres.',
          'name.regex' => 'El nombre solo puede contener letras',
          'razonSocial.required' => 'El campo Razón social es requerido'
        ]);

        
        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            $proveedor= new Proveedores();
            $proveedor->name = $request->name;
            $proveedor->direccion = $request->direccion;
            $proveedor->email = $request->email;
            $proveedor->telefono = $request->telefono;
            $proveedor->razonSocial = $request->razonSocial;
            $proveedor->nit = $request->nit;
            $proveedor->save();
            return response()->json([
                'status' => 200
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedores $proveedores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $proveedor = Proveedores:: findOrFail($id);
        if($proveedor){
            return response()->json([
                'status' => 200,
                'proveedor' => $proveedor
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'El producto no existe'
            ]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $validarCampos = [
            'name' => 'required|string|max:100|regex:/^\w\D[a-zA-Z0-9-"ñÑ+.,áéíóú ]+$/',
            'direccion' => 'required|string|max:500|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => 'required|numeric',
            'razonSocial' => 'required',
            'nit' => 'required',
        ];
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
          'name.required' => 'El nombre es requerido',
          'name.max' => 'El nombre no debe contener más de 100 caracteres.',
          'name.regex' => 'El nombre solo puede contener letras',
          'razonSocial.required' => 'El campo Razón social es requerido'
        ]);
        $datosProveedor = $request -> except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            Proveedores::where('id','=',$id)->update($datosProveedor);
            return response()->json([
                'status' => 200
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Proveedores::destroy($id);
        return response()->json([
            'status' => 200
        ]);
    
    }
}
