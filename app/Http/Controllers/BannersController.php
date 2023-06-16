<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BannersController extends Controller
{
  
    public function index()
    {
     return view('backend/banners.index');
    }

    public function allBanners()
    {
        $banners = Banners::all();
       
        return datatables()->of($banners)->toJson();
    }   
    public function store(Request $request)
    {
        $validarCampos = [
            'descripcion' => 'required|string|max:500|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'imagen' => 'required|max:5000|mimes:jpeg,png,jpg,svg',
        ];

        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'imagen.required' => 'La Imagén es requerida',
            'imagen.mimes' => 'La imagen debe ser de formato (jpeg,png,jpg,svg)',
            'descripcion.regex' => 'La descripción solo puede contener letras y números.',
            'descripcion.max' => 'La descripción puede tener maximo 500 Caracteres',
        ]);

        $banners  = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
           }else {
            // //Se hace una condicion para saber si llega o si hay una foto en ese campo 
            if ($request -> hasFile('imagen')) {
                //si hay una foto, se altera el campo y se utiliza el nombre del campo y despues se inserta en el img
                $file = $request ->file('imagen');
                $rutaimg = 'img/banners/';
                $filename = time().'-'. $file->getClientOriginalName();
                    //se adjunta la foto en la carpeta public / img, y se inserta la informacion de la imagen a la BD
                $subirimg = $request ->file('imagen')->move($rutaimg, $filename);
                $banners['imagen'] = $rutaimg . $filename;
            }

            Banners::insert($banners);
            return response()->json([
                'status' => 200
            ]);
        }

    }   

   
    public function edit($id)
    {
        $banner = Banners:: findOrFail($id);
        if($banner){
            return response()->json([
                'status' => 200,
                'banner' => $banner
            ]);
        }else{
            return response()->json([
                'status'=> 400,
                'message' => 'El banner no existe'
            ]);
        }
    }

    
    public function update(Request $request,  $id)
    {
        //dd($request);
        $validarCampos = [
            'descripcion' => 'required|string|max:500|regex:/^[a-zA-Z0-9-"ñÑ+.,áéíó ]+/',
            'imagen' => '|max:10048|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,svg',
        ];

        $validacion = Validator::make($request->all(),$validarCampos, $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'imagen.mimes' => 'La imagen debe ser de formato (jpeg,png,jpg,svg)',
            'descripcion.regex' => 'La descripción solo puede contener letras y números.',
            'descripcion.max' => 'La descripción puede tener maximo 500 Caracteres',
        ]);

        $datosBanner  = $request->except('_token');

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validacion->errors(),
            ]);
       }else {
            $banner = Banners::find($id);
          
            if ($banner) {

                // //Se hace una condicion para saber si llega o si hay una foto en ese campo 
                if ($request -> hasFile('imagen')) {
                    $path = 'img/banners/'.$banner->imagen;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    //si hay una foto, se altera el campo y se utiliza el nombre del campo y despues se inserta en el img
                    $file = $request ->file('imagen');
                    $rutaimg = 'img/banners/';
                    $filename = $file->getClientOriginalName();
                    //se adjunta la foto en la carpeta public / img, y se inserta la informacion de la imagen a la BD
                    $subirimg = $request ->file('imagen')->move($rutaimg, $filename);
                    $datosBanner['imagen'] = $rutaimg . $filename;
                }
               
                Banners::where('id','=',$id)->update($datosBanner);
                return response()->json([
                    'status' => 200
                ]);

            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Banner no existe'
                ]);
            }
        }    
    }

 
    public function destroy($id)
    {
        $banner = Banners::findOrFail($id);
        //Se hace una validacion para borrar la foto.
  
         if (file_exists($banner->imagen)) {
            
            //Se elimina el registro en la bd
           
            unlink("$banner->imagen");
            Banners::destroy($id);
            return response()->json([
                'status' => 200
            ]);
        }else{
            Banners::destroy($id);
            return response()->json([
                'status' => 200
            ]);
        }
    }
}
