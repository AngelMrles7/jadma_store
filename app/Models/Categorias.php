<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;
    protected $table="categorias";
    public $timestamps = false;
    
    public function productos()
    {
        return $this -> hasMany('App\Models\Producto');/*Relacion de la tabla Garantia a productos*/
    }
}
