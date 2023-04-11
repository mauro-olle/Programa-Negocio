<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class SearchProductController extends Controller
{
    public function search($keywords)
    {
        $productos = \DB::table('products')
        ->select(
            'products.id',
            'product_categories.nombre as categoria',
            'products.codigo',
            'products.nombre',
            // 'products.ideal',
            'products.pedido',
            'products.quedan',
            'products.aviso',
            'products.costo',
            'products.monto',
            'products.archivo')
        ->join('product_categories','product_categories.id','=','products.id_categoria')
        ->where('products.nombre', 'LIKE', "%$keywords%")
        ->orderBy('products.nombre', 'asc')
        ->get();

        return response()->json($productos);
    }
}
