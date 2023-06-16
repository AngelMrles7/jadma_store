<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaClientes extends Model
{
    use HasFactory;
    protected $table="categoria_clients";
    public $timestamps = false;

    public function estado_categoria(){
        if ($this->tiempo > Carbon::now()) {
            return [
                'color' => 'danger',
                'text' => 'Expirado'
            ];
        }else {
            return [
                'color' => 'success',
                'text' => 'Activo'
            ];
        }
    }
}
