<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id_categoria',
        'codigo',
        'nombre',
        'pedido',
        // 'ideal',
        'quedan',
        'aviso',
        'costo',
        'monto',
        'archivo',
    ];
}
