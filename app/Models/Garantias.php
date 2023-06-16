<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garantias extends Model
{
    use HasFactory;
    protected $table="garantias";
    public $timestamps = false;

    public function productos()
    {
        return $this -> hasMany('App\Models\Producto');/*Relacion de la tabla marcas a productos*/
    }
}
