<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use App\Models\Categorias;
use Illuminate\Http\Request;
use App\Models\Productos;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {   
        $banners = Banners::all();
        $productos = DB::table('productos')->where('existencia','>', 0)->limit(12)->get();
        $newProducts = DB::table('productos')->where('existencia','>', 0) ->orderBy('id','desc')->limit(4)->get();
      
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->limit(5)->get();
        return view('front.index', compact('productos', 'banners','categorias','newProducts'));
       
    }
    public function confirmar()
    {
        session()->forget('cart');
        return redirect()->route('main')->with('compra','ok');
    }


    public function mostrarProduct($id)
    {
        $productos = DB::table('productos')
        ->join("categorias", "categorias.id","=", "productos.categorias_id")
        ->select("productos.id","productos.nombre","productos.descripcion","productos.foto","productos.precio"
        )->where('productos.categorias_id','=',$id)->where('productos.existencia','>', 0)->get();
        $solocategoria = DB::table("categorias")->select('nombre')->where('id','=',$id)->get();
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();

        return view('/front.categorias', compact('productos','categorias','solocategoria'));
    }

    public function detalle($id){
        $categorias = Categorias::select('id','nombre')->where('estado','=', '1')->get();
        $productos= Productos::findOrfail($id);
        return view('front.detalle', compact('productos', 'categorias')); 
    }
}
