<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'price',
        'description',
        'status',
        'reference_number'
    ];

    public static function getprice($value){

        return $value;
        
    }

}
