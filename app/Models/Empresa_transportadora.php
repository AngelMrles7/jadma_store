<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa_transportadora extends Model
{
    use HasFactory;
    protected $table = "empresas_transportadora";

    public $timestamps = false;
}
