<?php

namespace App\Http\Controllers;

use App\Afip;
use Illuminate\Http\Request;
use App\Control;
use App\User;
use App\Order;
use App\Deuda;
use App\OrderProduct;
use App\Product;
use App\FormaPago;

class ControlController extends Controller
{
    public function inicio()
    {
        $caja_abierta = \DB::table('controls')->where('caja_abierta', 1)->where('id_desc', 1)->exists();
        
        $controls = Control::where('id_desc', 1)
                    ->where('caja_abierta', 1)
                    ->get();
        $titulo = "Caja inicial";
        
        return view('control.caja.inicio', compact('controls', 'titulo', 'caja_abierta'));
    }

    public function cierre()
    {
        \DB::table('controls')
            ->where('caja_abierta', 1)
            ->update(['caja_abierta' => 0]);
        
        \DB::table('orders')
        ->where('deHoy', 1)
        ->update(['deHoy' => 0]);
            
        \DB::table('deudas')
        ->where('deHoy', 1)
        ->update(['deHoy' => 0]);

        return redirect()->route('control.caja.inicio');
    }

    public function retiros()
    {
        $caja_abierta = \DB::table('controls')->where('caja_abierta', 1)->exists();
        if($caja_abierta)
        {
            $controls = \DB::table('controls')
                    ->where('caja_abierta', 1)
                    ->where('id_desc', '=', 6)
                    ->get();

            $titulo = "Retiros del día";
            return view('control.caja.retiros', compact('controls', 'titulo'));
        }
        else 
        {
            return view('control.cajaCerrada');
        }
    }

    public function historial_retiros(Request $request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;
        $controls = \DB::table('controls')
                    ->where('id_desc', '=', 6)
                    ->whereBetween('created_at', [$desde, $hasta])
                    ->get();
        
        $desde = date('d/m/y', strtotime($desde));
        $hasta = date('d/m/y', strtotime($hasta));
        $titulo = "Retiros desde el " . $desde . " hasta el " . $hasta;
        
        return view('control.caja.retiros', compact('controls', 'titulo'));
    }

    public function gastos()
    {
        $caja_abierta = \DB::table('controls')->where('caja_abierta', 1)->exists();
        if($caja_abierta)
        {
            if (\Request::is('*/varios')) 
            { 
                $nombre = "varios"; $id_desc = 3;  
            }
            else if(\Request::is('*/servicios'))
            {
                $nombre = "servicios"; $id_desc = 4;
            }
            else if(\Request::is('*/proveedores'))
            {
                $nombre = "proveedores"; $id_desc = 7;
            }
            // else if(\Request::is('*/comida'))
            // {
            //     $nombre = "comida"; $id_desc = 9;
            // }
            // else if(\Request::is('*/contador'))
            // {
            //     $nombre = "contador"; $id_desc = 10;
            // }
            else {}
            
            $controls = \DB::table('controls')
                        ->where('caja_abierta', 1)
                        ->where('id_desc', '=', $id_desc)
                        ->get();
                        
            $titulo = "Gastos de " . $nombre . " del día";
            
            return view('control.gastos.index', compact('controls', 'titulo', 'nombre', 'id_desc'));
        }
        else 
        {
            return view('control.cajaCerrada');
        }
    }

    public function historial_gastos(Request $request)
    {
        if (\Request::is('*/varios'))
        {
            $nombre = "varios"; $id_desc = 3;
        }
        else if(\Request::is('*/servicios'))
        { 
            $nombre = "servicios"; $id_desc = 4; 
        }
        else if(\Request::is('*/proveedores'))
        { 
            $nombre = "proveedores"; $id_desc = 7; 
        }
        // else if(\Request::is('*/comida'))
        // {
        //     $nombre = "comida"; $id_desc = 9;
        // }
        // else if(\Request::is('*/contador'))
        // {
        //     $nombre = "contador"; $id_desc = 10;
        // }
        else {}

        $desde = $request->desde;
        $hasta = $request->hasta;
        $controls = \DB::table('controls')
                    ->where('id_desc', '=', $id_desc)
                    ->whereBetween('created_at', [$desde, $hasta])
                    ->get();
        
        $desde = date('d/m/y', strtotime($desde));
        $hasta = date('d/m/y', strtotime($hasta));
        $titulo = "Gastos de " . $nombre . " desde " . $desde . " hasta " . $hasta;
        
        return view('control.gastos.index', compact('controls', 'titulo', 'nombre'));
    }

    public function ordenes()
    {
        $caja_abierta = \DB::table('controls')->where('caja_abierta', 1)->exists();
        if($caja_abierta)
        {
            // if (\Request::is('*/productos') || \Request::is('*/control')) 
            // { 
                $tipo = "productos";
                $id_type = 1;
            // }
            
            $orders = \DB::table('orders')->where('id_type', $id_type)->where('deHoy', 1)->orderBy('id', 'DESC')->get();
            $encargados = \DB::table('users')->select('id', 'nombre', 'activo')->where([['id_uType', 1], ['id',"!=", 1],])->orderBy('nombre')->get();
            $clientes = \DB::table('users')->select('id', 'nombre', 'activo')->where('id_uType', 2)->orderBy('nombre')->get();
            $formasPago = \DB::table('formas_pago')->select('id', 'nombre')->get();
            $titulo = "Ingresos por " . $tipo . " del día";
            
            return view('control.ingresos.index', compact('titulo', 'tipo', 'encargados', 'clientes', 'formasPago', 'id_type', 'orders'));
        }
        else 
        {
            return view('control.cajaCerrada');
        }
    }

    public function historial_ordenes(Request $request)
    {
        if (\Request::is('*/productos/historial'))
        {
            $tipo = "productos"; $id_type = 1;
        }
        // else if(\Request::is('*/servicios/historial'))
        // { 
        //     $tipo = "servicios"; $id_type = 2; 
        // }
        
        $desde = $request->desde;
        $hasta = $request->hasta;
        $orders = \DB::table('orders')
                    ->where('id_type', '=', $id_type)
                    ->whereBetween('created_at', [$desde, $hasta])
                    ->orderBy('id', 'DESC')
                    ->get();
        //dd($orders);
        $encargados = \DB::table('users')->select('id', 'nombre', 'activo')->where('id_uType', 1)->orderBy('nombre')->get();
        $clientes = \DB::table('users')->select('id', 'nombre')->where('id_uType', 2)->orderBy('nombre')->get();
        $formasPago = \DB::table('formas_pago')->select('id', 'nombre')->get();
            
        $desde = date('d/m/y', strtotime($desde));
        $hasta = date('d/m/y', strtotime($hasta));
        $titulo = "Ingresos desde " . $desde . " hasta " . $hasta;
        
        return view('control.ingresos.index', compact('titulo', 'tipo', 'encargados', 'clientes', 'formasPago', 'id_type', 'orders'));
    }

    public function store_orden(Request $request)
    {
        $order = Order::create([
            'id_encargado' => $request['id_encargado'],
            'id_cliente' => $request['id_cliente'],
            'id_type' => $request['id_type'],
            'id_forma_pago' => $request['id_forma_pago'],
            'monto' => $request['monto'],
            'pago_efec' => $request['pago_efec'],
            'pago_tarj' => $request['pago_tarj'],
            'descuento' => $request['descuento'],
            'completada' => $request['completada'],
            'deHoy' => $request['deHoy']
        ]);
        
        $id_order = $order->id;
        
        switch ($request['id_type']) 
        {
            case '1':
                return redirect()->route('control.ingresos.productos.agregar', compact('id_order'));
                break;
            
            default:
                # code...
                break;
        }
    }

    public function subordenes($id_order)
    {
        if (\Request::is('*/productos/*')) 
        { 
            $tipo = "productos";
            $id_type = 1;
        }
        
        $order = Order::find($id_order);
        if ($order!=null) 
        {
            $regAfipFct = Afip::find($order->idAfipFct);
            //dd($regAfipFct->tipoCbteNum);
            $encargado = User::find($order->id_encargado)->nombre;
            $cliente = User::find($order->id_cliente)->nombre;
            $formaPago = FormaPago::find($order->id_forma_pago)->nombre;
            $subtitulo = "Cliente: " . $cliente . " | Atendió: " . $encargado;
            
            if ($order->id_forma_pago != 3) 
            {
                $pie = "Forma de pago: " . $formaPago;
            }
            else 
            {
                $pie = "Forma de pago: " . $formaPago . " ( $" . $order->pago_efec . " / $" . $order->pago_tarj . " )";
            }

            $titulo = "Orden #" . $id_order;
        
            return view('control.ingresos.create', compact('titulo', 'subtitulo', 'pie', 'tipo', 'id_type', 'order', 'regAfipFct'));
        }
        else 
        {
            echo "<h1>La orden todavía no existe</h1>";
        }
        
        
    }

    public function store_suborden(Request $request, $id_order)
    {
        $codigo = $request->codigo;
        $cant = $request->cantidad;
        
        $product = Product::where('codigo', $codigo)->first();
        if ($product == null) 
        {
            return redirect()->route('control.ingresos.productos.agregar', compact('id_order'))->with('message', 'El producto con el código ' . $codigo  . ' todavía no ha sido cargado.');
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
            
            Order::where('id', $id_order)->increment('monto', $monto * $cant);
            $product->decrement('quedan', $cant);

            return redirect()->route('control.ingresos.productos.agregar', compact('id_order'));
        } 
        else 
        {
            return redirect()->route('control.ingresos.productos.agregar', compact('id_order'))->with('message', 'Quedan sólo ' . $product->quedan . ' unidades');
        }
        
        
    }

    public function descuento_orden(Request $request, $id_order)
    {
        $descuento = $request->descuento;
        $order = Order::find($id_order);
        $monto = $order->monto;
        $descuento = $monto * $descuento /100;
        
        \DB::table('orders')->where('id', $id_order)->update(['descuento' => $descuento]);
        
        if (\Request::is('*/productos/*')) 
        { 
            return redirect()->route('control.ingresos.productos.agregar', compact('id_order'));
        }
    }

    public function cerrar_orden(Request $request, $id_order)
    {
        //dd($request->all());
        $id_cliente = $request->id_cliente;
        $id_forma_pago = $request->id_forma_pago;
        $descuento = $request->descuento;
        
        $order = Order::find($id_order);
        $monto = $order->monto;

        if ($id_forma_pago == 1) 
        {
            if ($descuento == null) {
                $descuento = 0;
            }
            
            if ($request->exists('fiado')) 
            {
                $deuda = Deuda::create([
                    'id_cliente' => $id_cliente,
                    'id_encargado' => $order->id_encargado,
                    'monto' => $request->fiado,
                    'tipo' => 'D'
                ]);

                \DB::table('orders')
                ->where('id', $id_order)
                ->update(['pago_efec' => $monto - $request->fiado - $descuento,
                        'monto' => $monto,
                        'fiado' => $request->fiado,
                        'id_cliente' => $id_cliente,
                        'id_deuda' => $deuda->id,
                        'completada' => 1,
                        'descuento' => $descuento
                    ]
                );
            }
            else 
            {
                \DB::table('orders')
                ->where('id', $id_order)
                ->update([
                    'pago_efec' => $monto - $descuento, 
                    'id_cliente' => $id_cliente,
                    'completada' => 1,
                    'descuento' => $descuento
                    ]
                );
            }
        }
        elseif ($id_forma_pago == 2) 
        {
            if ($request->exists('fiado')) 
            {
                $deuda = Deuda::create([
                    'id_cliente' => $id_cliente,
                    'id_encargado' => $order->id_encargado,
                    'monto' => $request->fiado,
                    'tipo' => 'D'
                ]);

                $montoNeto = $monto - $request->fiado;

                \DB::table('orders')
                ->where('id', $id_order)
                ->update(['pago_tarj' => $montoNeto,
                        'monto' => $monto,
                        'fiado' => $request->fiado,
                        'id_forma_pago' => $id_forma_pago,
                        'id_cliente' => $id_cliente,
                        'id_deuda' => $deuda->id,
                        'completada' => 1
                    ]
                );
            }
            else 
            {
                \DB::table('orders')
                ->where('id', $id_order)
                ->update(['pago_tarj' => $monto, 
                        'id_cliente' => $id_cliente,
                        'id_forma_pago' => $id_forma_pago,
                        'completada' => 1
                    ]
                );
            }
        }
        elseif ($id_forma_pago == 3)
        {
            $pago_efec = $request->pago_efec;
            $pago_tarj = $request->pago_tarj;
            
            if ($request->exists('fiado')) 
            {
                $deuda = Deuda::create([
                    'id_cliente' => $id_cliente,
                    'id_encargado' => $order->id_encargado,
                    'monto' => $request->fiado,
                    'tipo' => 'D'
                ]);

                Order::where('id', $id_order)->update([
                    'id_cliente' => $id_cliente,
                    'monto' => $monto,
                    'id_forma_pago' => $id_forma_pago,
                    'pago_efec' => $pago_efec, 
                    'pago_tarj' => $pago_tarj,
                    'fiado' => $request->fiado,
                    'id_deuda' => $deuda->id,
                    'completada' => 1
                ]);
            }
            else 
            {
                Order::where('id', $id_order)->update([
                    'id_cliente' => $id_cliente,
                    'id_forma_pago' => $id_forma_pago,
                    'pago_efec' => $pago_efec, 
                    'pago_tarj' => $pago_tarj,
                    'completada' => 1
                ]);
            }
        }
        else 
        {
            $deuda = Deuda::create([
                'id_cliente' => $id_cliente,
                'id_encargado' => $order->id_encargado,
                'monto' => $monto,
                'tipo' => 'D'
            ]);
            
            Order::where('id', $id_order)->update([
                'id_cliente' => $id_cliente,
                'id_forma_pago' => $id_forma_pago,
                'monto' => $monto,
                'fiado' => $monto,
                'id_deuda' => $deuda->id,
                'completada' => 1
            ]);
        }
        
        return back();
        //return redirect()->route('control.ingresos.productos');
    }

    public function store(Request $request)
    {
        Control::create([
            'admin' => $request['admin'],
            'monto' => $request['monto'],
            'id_desc' => $request['id_desc'],
            'detalle' => $request['detalle'],
            'caja_abierta' => $request['caja_abierta']
        ]);
        
        switch ($request['id_desc']) 
        {
            case '1':
                return redirect()->route('control.caja.inicio');
                break;
            
            // case '2':
            //     return redirect()->route('control.comisiones')->with('message', 'La comisión fue pagada correctamente.');
            //     break;
            
            case '3':
                return redirect()->route('control.gastos.varios');
                break;
            
            case '4':
                return redirect()->route('control.gastos.servicios');
                break;
            
            // case '5':
            //     return redirect()->route('control.sueldos')->with('message', 'El sueldo fue pagado correctamente.');
            //     break;
            
            case '6':
                return redirect()->route('control.caja.retiros');
                break;
            
            case '7':
                return redirect()->route('control.gastos.proveedores');
                break;

            // case '8':
            //     return redirect()->route('control.adelantos')->with('message', 'El adelanto fue pagado correctamente.');
            //     break;

            // case '9':
            //     return redirect()->route('control.gastos.comida');
            //     break;
            
            // case '10':
            //     return redirect()->route('control.gastos.contador');
            //     break;
            
            default:
                # code...
                break;
        }
    }

    public function delete($id)
    {
        Control::destroy($id);
        //return redirect()->route('control.caja.inicio');
        return redirect()->back();
    }

    public function movimientos()
    {
        $caja_inicial = Control::where('id_desc', 1)
                        ->where('caja_abierta', 1)
                        ->value(\DB::raw("sum(monto)")) + 0;

        // $ingXmercaderias = \DB::table('orders_products')
        //                 ->join('orders', 'orders_products.id_order', '=', 'orders.id')
        //                 ->join('products', 'orders_products.id_producto', '=', 'products.id')
        //                 ->where([['deHoy', 1],
        //                         ['completada', 1],
        //                         ['id_forma_pago', '!=', 4],])
        //                 ->value(\DB::raw("sum(round(orders_products.monto * orders_products.cantidad))")) + 0;

        $ingXmercaderias = Order::where([['deHoy', 1], ['completada', 1]])
                        ->value(\DB::raw("sum(monto)")) + 0;
        $ingXprod_efec = Order::where('deHoy', 1)
                        ->value(\DB::raw("sum(pago_efec)")) + 0;
        $ingXprod_tarj = Order::where('deHoy', 1)
                        ->value(\DB::raw("sum(pago_tarj)")) + 0;
        $ingXpago_deudas = Deuda::where('deHoy', 1)
                        ->where('tipo', 'P')
                        ->value(\DB::raw("sum(monto)")) + 0;
        $descuentos = \DB::table('orders')
                        ->where([['deHoy', 1], ['completada', 1]])
                        ->value(\DB::raw("sum(descuento)")) + 0;
        $fiado = \DB::table('orders')
                        ->where([['deHoy', 1], ['completada', 1]])
                        ->value(\DB::raw("sum(fiado)")) + 0;

        $gastosVarios = Control::where('caja_abierta', 1)
                        ->where('id_desc', 3)
                        ->value(\DB::raw("sum(monto)")) + 0;
        $gastXserv = Control::where('caja_abierta', 1)
                        ->where('id_desc', 4)
                        ->value(\DB::raw("sum(monto)")) + 0;
        $gastXprov = Control::where('caja_abierta', 1)
                        ->where('id_desc', 7)
                        ->value(\DB::raw("sum(monto)")) + 0;
        $retiros = Control::where('caja_abierta', 1)
                        ->where('id_desc', 6)
                        ->value(\DB::raw("sum(monto)")) + 0;
        $total_efec = $caja_inicial + $ingXprod_efec + $ingXpago_deudas - $gastosVarios - $gastXserv - $gastXprov - $retiros;
        $total_tarj = $ingXprod_tarj;
        
        $titulo = "Movimientos del turno";
        
        return view('control.movimientos.index', compact(
            'titulo', 
            'caja_inicial', 
            'ingXmercaderias', 
            'ingXpago_deudas', 
            'fiado',
            'descuentos',
            'gastosVarios', 
            'gastXserv', 
            'gastXprov', 
            'retiros', 
            'total_efec', 
            'total_tarj'
        ));
    }

    public function historial_movimientos(Request $request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;
        
        $caja_inicial = Control::where('id_desc', 1)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        
        // $ingXmercaderias = \DB::table('orders_products')
        //                 ->join('orders', 'orders_products.id_order', '=', 'orders.id')
        //                 ->join('products', 'orders_products.id_producto', '=', 'products.id')
        //                 ->where([['id_forma_pago', '!=', 4],])
        //                 ->whereBetween('orders.created_at', [$desde, $hasta])
        //                 ->value(\DB::raw("sum(orders_products.monto * orders_products.cantidad)")) + 0;
        $ingXmercaderias = Order::where('completada', 1)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        $ingXprod_efec = Order::where('id_type', 1)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(pago_efec)")) + 0;
        $ingXprod_tarj = Order::where('id_type', 1)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(pago_tarj)")) + 0;
        $ingXpago_deudas = Deuda::where('tipo', 'P')
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        ////////////////////////////////////////////////////////////////////////////VER FIADO
        $fiado = Order::where([['id_type', 1]])
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(fiado)")) + 0;
        $descuentos = Order::where([['id_type', 1]])
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(descuento)")) + 0;
        $gastosVarios = Control::where('id_desc', 3)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        $gastXserv = Control::where('id_desc', 4)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        $gastXprov = Control::where('id_desc', 7)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        $retiros = Control::where('id_desc', 6)
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->value(\DB::raw("sum(monto)")) + 0;
        
        $total_efec = $caja_inicial + $ingXprod_efec + $ingXpago_deudas - $gastosVarios - $gastXserv - $gastXprov - $retiros;
        $total_tarj = $ingXprod_tarj;
                        
        $desde = date('d/m/y', strtotime($request->desde));
        $hasta = date('d/m/y', strtotime($request->hasta));
        $titulo = "Movimientos desde " . $desde . " hasta " . $hasta;
        
        return view('control.movimientos.index', compact(
            'titulo', 
            'caja_inicial', 
            'ingXmercaderias', 
            'ingXpago_deudas', 
            'fiado', 
            'descuentos',
            'gastosVarios', 
            'gastXserv', 
            'gastXprov', 
            'retiros', 
            'total_efec', 
            'total_tarj'
        ));
    }
}
