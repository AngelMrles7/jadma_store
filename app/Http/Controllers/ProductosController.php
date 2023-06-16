<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Marcas;
use App\Models\Garantias;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductosController extends Controller
{
    
    public function index()
    {
        return view('backend/productos.index');
    }

    public function allproduct()
    {
        //Inner join
        $productos = DB::table('productos')
        ->join("garantias", "garantias.id", "=", "productos.garantias_id")
        ->join("marcas", "marcas.id", "=", "productos.marcas_id")
        ->join("categorias", "categorias.id", "=", "productos.categorias_id")
        ->select("productos.*","garantias.tipo","marcas.nombre as marca","categorias.nombre as categoria")
        ->get();
    
        return datatables()->of($productos)->toJson();
    }
   
    public function garantias()
    {
        $garantia = Garantias::select('id','tipo')->get();
        return $garantia;
    }

    public function marcas()
    {
        $marca = Marcas::select('id','nombre')->where('estado','=', '1')->get();
        return $marca;
    }

    public function categorias()
    {
        $categoria = Categorias::select('id','nombre')->where('estado','=', '1')->get();
        return $categoria;
    }

   
    public function store( Request $request)
    {
        $validarCampos = [
            'nombre' => 'required|string|max:100|regex:/^\w\D[a-zA-Z0-9-"ñÑ+.,áéíóú ]+$/',
            'descripcion' => 'required|string|max:500|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'foto' => 'required|max:10048|mimes:jpeg,png,jpg,svg',
            'precio' => 'required|numeric|regex:/^[1-9]\d{4,14}\.[0-9]?\d$/',
            'existencia' => 'required|numeric|regex:/^[1-9]\d{0,4}$/',
            'serial' => 'required|numeric|regex:/^[0-9]\d{3,14}$/',
            'garantias_id' => 'required',
            'marcas_id' => 'required',
            'categorias_id' => 'required',
        ];
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'foto.required' => 'La foto es requerida',
            'foto.mimes' => 'La imagen debe ser de formato (jpeg,png,jpg,svg)',
            'nombre.regex' => 'El nombre del producto solo puede contener letras y números.',
            'nombre.max' => 'El nombre puede tener maximo 100 Caracteres',
            'descripcion.regex' => 'La descripción solo puede contener letras y números.',
            'descripcion.max' => 'la descripción puede tener maximo 500 Caracteres',
            'precio.regex' => 'El precio puede tener minimo 5 o maximo 15 dijitos y solo puede contener números',
            'existencia.regex' => 'La cantidad  puede tener minimo 1 o maximo 5 dijitos y solo puede contener números',
            'serial.regex' => 'El serial puede tener minimo 4 o maximo 15 dijitos y solo puede contener números'
        ]);
        $productos = $request->except('_token');
        
       if ($validacion->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validacion->errors(),
        ]);
       }else {
            // //Se hace una condicion para saber si llega o si hay una foto en ese campo 
            if ($request -> hasFile('foto')) {
                //si hay una foto, se altera el campo y se utiliza el nombre del campo y despues se inserta en el img
                $file = $request ->file('foto');
                $rutaimg = 'img/productos/';
                $filename = time().'-'. $file->getClientOriginalName();
                 //se adjunta la foto en la carpeta public / img, y se inserta la informacion de la imagen a la BD
                $subirimg = $request ->file('foto')->move($rutaimg, $filename);
                $productos['foto'] = $rutaimg . $filename;
            }

            Productos::insert($productos);
            return response()->json([
                'status' => 200
            ]);
       }
    }

    public function edit($id)
    {   
        $producto = Productos:: findOrFail($id);
        if($producto){
            return response()->json([
                'status' => 200,
                'producto' => $producto
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'El producto no existe'
            ]);
        }
        
    }

    
    public function update(Request $request, $id)
    {   
        
        $validarCampos = [
            'nombre' => 'required|string|max:100|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'descripcion' => 'required|string|max:500|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'foto' => 'max:10048|mimes:jpeg,png,jpg,svg',
            'precio' => 'required|numeric|regex:/^[1-9]\d{4,14}\.[0-9]?\d$/',
            'existencia' => 'required|numeric|regex:/^[0-9]\d{0,4}$/',
            'serial' => 'required|numeric|regex:/^[0-9]\d{3,14}$/'
        
        ];
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'foto.mimes' => 'La imagen debe ser de formato (jpeg,png,jpg,svg)',
            'nombre.regex' => 'El nombre del producto solo puede contener letras y números.',
            'nombre.max' => 'El nombre puede tener maximo 100 Caracteres',
            'descripcion.regex' => 'La descripción solo puede contener letras y números.',
            'descripcion.max' => 'La descripción puede tener maximo 500 Caracteres',
            'precio.regex' => 'El precio puede tener minimo 5 o maximo 15 dijitos y solo puede contener números',
            'existencia.regex' => 'La cantidad  puede tener minimo 1 o maximo 5 dijitos y solo puede contener números',
            'serial.regex' => 'El serial puede tener minimo 4 o maximo 15 dijitos y solo puede contener números'
        ]);
        
        $datosProduct = $request -> except('_token');
       if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
       }else {
            $producto = Productos::find($id);
          
            if ($producto) {

                // //Se hace una condicion para saber si llega o si hay una foto en ese campo 
                if ($request -> hasFile('foto')) {
                    $path = 'img/productos/'.$producto->foto;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    //si hay una foto, se altera el campo y se utiliza el nombre del campo y despues se inserta en el img
                    $file = $request ->file('foto');
                    $rutaimg = 'img/productos/';
                    $filename = $file->getClientOriginalName();
                    //se adjunta la foto en la carpeta public / img, y se inserta la informacion de la imagen a la BD
                    $subirimg = $request ->file('foto')->move($rutaimg, $filename);
                    $datosProduct['foto'] = $rutaimg . $filename;
                }
                
                if ($request->input('marcas_id') != null) {
                    $datosProduct['marcas_id'] = $request->input('marcas_id');
                }else{
                    $datosProduct['marcas_id'] = $producto['marcas_id'];
                }

                if ($request->input('garantias_id') != null) {
                    $datosProduct['garantias_id'] = $request->input('garantias_id');
                }else{
                    $datosProduct['garantias_id'] = $producto['garantias_id'];
                }

                if ($request->input('categorias_id') != null) {
                    $datosProduct['categorias_id'] = $request->input('categorias_id');
                }else{
                    $datosProduct['categorias_id'] = $producto['categorias_id'];
                }
                Productos::where('id','=',$id)->update($datosProduct);
                return response()->json([
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Producto no existe'
                ]);
            }
        }    
    }
}
