<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    static function chekProductoPorMarca( int $idMarca )
    {
        //obj | null
        // return Producto::where('idMarca', $idMarca)->first();
        return Producto::where('idMarca', $idMarca)->count();
    }
}
