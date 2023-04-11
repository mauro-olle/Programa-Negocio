<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id_encargado',
        'id_cliente',
        'id_type',
        'id_forma_pago',
        'pago_efec',
        'pago_tarj',
        'monto',
        'descuento',
        'completada',
        'deHoy',
        'id_deuda',
        'idAfipFct',
        'idAfipNdc'
    ];
}
