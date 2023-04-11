<?php

namespace App\Http\Controllers;

use App\OrderProduct;
use App\Product;
use App\Order;
use App\ProductCategory;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getSubordenes($id_order)
    {
        $orders_indiv = \DB::table('orders_products')
        ->select('orders_products.id','orders_products.cantidad','products.nombre','product_categories.unidad','orders_products.monto','orders_products.created_at','products.archivo')
        ->join('products','products.id','=','orders_products.id_producto')
        ->join('product_categories','product_categories.id','=','products.id_categoria')
        ->where('orders_products.id_order', $id_order)
        ->get();
        
        return  $orders_indiv;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_order)
    {
        $codigo = $request->codigo;
        $cant = $request->cantidad;
        
        $product = Product::where('codigo', $codigo)->first();
        if ($product == null) 
        {
            return response()->json(["titulo" => "Error",
                                    "message" => "El producto con el código " . $codigo  . " todavía no ha sido cargado!"],403);
        }
        
        $category = ProductCategory::where('id', $product->id_categoria)->first();
        $unidad = $category->unidad;
        
        if ($cant == 1 && substr($unidad, -1) == "s") //le quito la S a la unidad (Uds => Ud)
        {
            $unidad = substr($unidad, 0, -1);
        }
        
        if ($product->quedan >= $cant) 
        {
            $monto = $product->monto;
        
            OrderProduct::create([
                'id_order' => $id_order,
                'id_producto' => $product->id,
                'cantidad' => $cant,
                'monto' => $monto
            ]);
            
            Order::where('id', $id_order)->increment('monto', ceil($monto * $cant));
            $product->decrement('quedan', $cant);

            return response()->json(["message" => "+ " . $cant . " " . $unidad . " de " . $product->nombre ],200);
        } 
        else 
        {
            return response()->json([
                                    "titulo" => "Imposible vender " . $cant . " " . $unidad,
                                    "message" => "Solo quedan " . $product->quedan . " " . $unidad . " en STOCK"
                                    ],403);
        }

        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function show(OrderProduct $orderProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderProduct $orderProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderProduct $orderProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $subOrder = OrderProduct::findOrFail($id);
        $product = Product::findOrFail($subOrder->id_producto);
        
        $category = ProductCategory::where('id', $product->id_categoria)->first();
        $unidad = $category->unidad;

        if ($subOrder->cantidad == 1 && substr($unidad, -1) == "s") //le quito la S a la unidad (Uds => Ud)
        {
            $unidad = substr($unidad, 0, -1);
        }
        
        \DB::table('orders')->where('id', $subOrder->id_order)->decrement('monto', ceil($subOrder->monto * $subOrder->cantidad));
        //\DB::table('orders')->where('id', $subOrder->id_order)->update(['descuento' => 0]);
        \DB::table('products')->where('id', $subOrder->id_producto)->increment('quedan', $subOrder->cantidad);
        
        $subOrder->delete();
        
        return response()->json(["message" => "- " . $subOrder->cantidad . " " . $unidad . " de " . $product->nombre ],200);
    }
}
