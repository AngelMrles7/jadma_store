<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoPqrs extends Model
{
    use HasFactory;
    protected $table = 'tipopqrs';
    public $timestand = false;
}
