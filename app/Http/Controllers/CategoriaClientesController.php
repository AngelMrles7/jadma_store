<?php

namespace App\Http\Controllers;

use App\Models\CategoriaClientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaClientesController extends Controller
{
    
    public function index()
    {
        return view('backend/categoriaClientes.index');
    }

    public function allCategori()
    {
        $allcategoria = CategoriaClientes::all();

        return datatables()->of($allcategoria)->toJson();
    }

   
    public function store(Request $request)
    {
        
        $validarCampos = [
            'nom_categoria' => 'required|string|unique:categoria_clients,nom_categoria|max:100|regex:/^\w\D[a-zA-Z-"ñÑ+.,áéíóú 0-9]+$/',
            'valor_compras' => 'required|numeric|regex:/^[1-9]\d{4,14}\.[0-9]?\d$/',
            'tiempo' => 'required|date|after_or_equal: today',
            'puntos_premio' => 'required|numeric|min:0'
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'nom_categoria.required' => 'El nombre de la categoria es requerido',
            'nom_categoria.unique' => 'El nombre de la categoria ya existe',
            'valor_compras.required' => 'El campo valor acumulado es requerido',
            'tiempo.required' => 'El campo periodo de tiempo es requerido',
            'nombre.regex' => 'El nombre del producto solo puede contener letras y números.',
            'nombre.max' => 'El nombre puede tener maximo 100 Caracteres',
            'valor_compras.regex' => 'El valor acumulado debe ser de minimo 5 o maximo 15 dijitos y solo puede contener números',
            'tiempo.date' => 'El periodo de tiempo no es una fecha válida',
            'after_or_equal' => 'El periodo de tiempo debe ser una fecha posterior o igual a la de hoy.'
        ]);
        $CategoriaClientes = $request->except('_token');
        
       if ($validacion->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validacion->errors(),
        ]);
       }else {
            CategoriaClientes::insert($CategoriaClientes);
            return response()->json([
                'status' => 200
            ]);
       }
    }


    public function edit($id)
    {
        $categoriaClientes = CategoriaClientes:: findOrFail($id);
        if($categoriaClientes){
            return response()->json([
                'status' => 200,
                'categoriaClientes' => $categoriaClientes
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'La categoria no existe'
            ]);
        }
    }


    public function update(Request $request,  $id)
    {

        $validarCampos = [
            'nom_categoria' => 'required|string|unique:categoria_clients,nom_categoria,'.$id.'|max:100|regex:/^\D[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'valor_compras' => 'required|numeric|regex:/^[1-9]\d{4,14}\.[0-9]?\d$/',
            'tiempo' => 'required|date|after_or_equal: today',
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'nom_categoria.required' => 'El nombre de la categoria es requerido',
            'nom_categoria.unique' => 'El nombre de la categoria ya existe',
            'valor_compras.required' => 'El  campo valor acumulado es requerido',
            'tiempo.required' => 'El campo periodo de tiempo es requerido',
            'nombre.regex' => 'El nombre del producto solo puede contener letras y números.',
            'nombre.max' => 'El nombre puede tener maximo 100 Caracteres',
            'valor_compras.regex' => 'El valor acumulado debe ser de minimo 5 o maximo 15 dijitos y solo puede contener números',
            'tiempo.date' => 'El periodo de tiempo no es una fecha válida',
            'after_or_equal' => 'El periodo de tiempo debe ser una fecha posterior o igual a la de hoy.'
        ]);
        $categoria = $request->except('_token');
    
        
       if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
       }else {
           
            $categoria = CategoriaClientes::where('id','=',$id)->first();
            $categoria -> nom_categoria = $request['nom_categoria'];
            $categoria -> valor_compras = $request['valor_compras'];
            $categoria -> tiempo = $request['puntos_premio'];
            $categoria -> tiempo = $request['tiempo'];
            $categoria -> save();

            return response()->json([
                'status' => 200
            ]);
        }
    }


}
