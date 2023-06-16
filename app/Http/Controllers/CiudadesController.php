<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use App\Models\Paises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CiudadesController extends Controller
{
    
    public function index()
    {
        return view('backend/ciudades.index');
    }

    public function allCiudades()
    {
        $ciudades = DB::table('ciudades')
        ->join("paises","paises.id","=","ciudades.paises_id") 
        ->select("ciudades.id","ciudades.nombreCiudad","paises.nombrePais")
        ->get();
       
        return datatables()->of($ciudades)->toJson();
    }

    public function paises()
    {
        $paises = Paises::select('id', 'nombrePais')->get();
        return $paises;
    }
    

    public function store(Request $request)
    {
        
        $validarCampos = [
            'nombreCiudad' => 'required|string|max:100|regex:/^\w\D[a-zA-Z-"ñÑ+.,áéíóú ]+$/',
            'paises_id' => 'required',
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'nombreCiudad.required' => 'Por favor ingrese el nombre de la ciudad',
            'nombreCiudad.regex' => 'El nombre de la ciudad solo puede contener letras.',
            'nombreCiudad.max' => 'El nombre puede tener maximo 100 Caracteres',
            'paises_id.required' => 'Por favor selecione un país',
        ]);
        $ciudad = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            Ciudades::insert($ciudad);
            return response()->json([
                'status' => 200
            ]);
        }
    }

    
    public function edit($id)
    {
        $ciudad = Ciudades::findOrfail($id);
        if($ciudad){
            return response()->json([
                'status' => 200,
                'ciudad' => $ciudad
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'La ciudad no existe'
            ]);
        }
    }

   
    public function update(Request $request, $id)
    {
        $validarCampos = [
            'nombreCiudad' => 'required|string|max:100|regex:/^\w\D[a-zA-Z-"ñÑ+.,áéíó ]+$/',
            'paises_id' => 'required',
        ];
        
        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'nombreCiudad.required' => 'Por favor ingrese el nombre de la ciudad',
            'nombreCiudad.regex' => 'El nombre de la ciudad solo puede contener letras.',
            'nombreCiudad.max' => 'El nombre puede tener maximo 100 Caracteres',
            'paises_id.required' => 'Por favor selecione un país',
        ]);
        $datoPais = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
        }else {
            Ciudades::where('id','=',$id)->update($datoPais);
            return response()->json([
                'status' => 200
            ]);
        }
    }

}
