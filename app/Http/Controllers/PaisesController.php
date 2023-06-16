<?php

namespace App\Http\Controllers;

use App\Models\Paises;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaisesController extends Controller
{
    
    public function index()
    {
        return view('/backend/paises.index');
    }

    public function allPaises()
    {
        $paises = Paises::all();

        return datatables()->of($paises)->toJson();
    }
  
    public function store(Request $request)
    {
        $validarCampos = [
            'nombrePais' => 'required|string|max:100|regex:/^\w\D[a-zA-Z-"ñÑ+.,áéíó ]+$/'
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'nombre.regex' => 'El nombre del producto solo puede contener letras y números.',
            'nombre.max' => 'El nombre puede tener maximo 100 Caracteres',
        ]);
        $paises = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            Paises::insert($paises);
            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function edit($id)
    {
        $pais = Paises:: findOrFail($id);
        if($pais){
            return response()->json([
                'status' => 200,
                'paises' => $pais
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'El País no existe'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validarCampos = [
            'nombrePais' => 'required|string|max:100|regex:/^\w\D[a-zA-Z-"ñÑ+.,áéíóú ]+$/'
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'nombre.regex' => 'El nombre del producto solo puede contener letras y números.',
            'nombre.max' => 'El nombre puede tener maximo 100 Caracteres',
        ]);
        $paises = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            Paises::where('id','=',$id)->update($paises);
            return response()->json([
                'status' => 200
            ]);
        }
    }

    // Un país no se puede eliminar.
    // public function destroy( $id)
    // {
    //     try 
    //     {
    //         Paises::destroy($id);
    //         return response()->json([
    //             'status' => 200
    //         ]);
    //     } 
    //     catch (Exception $e) 
    //     {
    //         if($e->getCode()=="23000"){
    //             return response()->json([
    //                 'status' => 400,
    //                 'message' => 'Error: Hay ciudades relacionadas a este país, por favor borre las ciudades primero'
    //             ]);
    //         }
    //     }
        
    // }
}
