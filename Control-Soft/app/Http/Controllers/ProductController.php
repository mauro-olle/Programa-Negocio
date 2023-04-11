<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ProductCategory::where('activa', 1)->orderBy('nombre')->get();
        $products = Product::orderBy('nombre')->paginate(20);
        $type = "producto";
        
        return view('products.index', compact('products','categories','type'));
    }
    
    // public function search(Request $request)
    // {
    //     $keyword = $request->keyword;
    //     $categories = ProductCategory::where('activa', 1)->orderBy('nombre')->get();
    //     $products = Product::where('nombre', 'LIKE', "%$keyword%")->orderBy('nombre')->paginate(5000);
    //     $type = "producto";
        
    //     return view('products.index', compact('products','categories','type'));
    // }

    public function filter(Request $request)
    {
        $categories = ProductCategory::where('activa', 1)->orderBy('nombre')->get();
        $products = Product::where('id_categoria', $request->id_categoria)->orderBy('nombre')->paginate(5000);
        $type = "producto";
        
        return view('products.index', compact('products','categories','type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = "producto";
        $categories = ProductCategory::where('activa', 1)->orderBy('nombre')->get();
        return view('products.create', compact('categories','type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            $file = $request->file('archivo');
            if($file != null)
            {
                $ext = $file->getClientOriginalExtension();//obtenemos la extension del archivo
                $filename = $request->codigo . "." . $ext;
                //guardamos
                Storage::disk('local')->put($filename,  \File::get($file));
            }
            else
            {
                $filename = "sinImagen.png";
            }
            
            Product::create([
                'nombre' => $request['nombre'],
                'id_categoria' => $request['id_categoria'],
                'codigo' => $request['codigo'],
                // 'ideal' => $request['ideal'],
                'pedido' => $request['pedido'],
                'quedan' => $request['pedido'],
                'costo' => $request['costo'],
                'aviso' => $request['aviso'],
                'monto' => $request['monto'],
                'archivo' => $filename
            ]);

            return redirect()->route('products.create')->with('message', 'El producto fue creado correctamente.');
        } 
        catch (\Throwable $th) 
        {
            if ($th->errorInfo[1] == 1062) 
            {
                $product = Product::where('codigo', $request->codigo)->first();
                if ($product->nombre == $request['nombre']) 
                {
                    return redirect()->route('products.create')->with('message', 'El código de barras ' . $product->codigo  . ' ya fue cargado');
                } 
                else 
                {
                    return redirect()->route('products.create')->with('error', 'El código de barras ' . $product->codigo  . ' ya existe, y pertenece a ' . $product->nombre);
                }
            } else {
                # code... Lo dejamos para futuros posibles errores
                dd('Error ' . $th->errorInfo[1] . '. Contacte a JABlack Soft e informe ESTE número. Puede volver ATRÁS y reintentarlo');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        
        if ($product == null) 
        {
            return view('errors.404');
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = "producto";
        $categories = ProductCategory::where('activa', 1)->orderBy('nombre')->get();
        $product = Product::find($id);
        return view('products.edit', compact('product','categories','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product, Request $request)
    {
        $data = request()->validate([
            'nombre' => 'required',
            'id_categoria' => 'required',
            'codigo' => 'required',
            // 'ideal' => 'required',
            'pedido' => 'required',
            'quedan' => 'required',
            'aviso' => 'required',
            'costo' => 'required',
            'monto' => 'required',
        ]);
        
        $file = $request->file('archivo');
        if ($file != null)
        {
            //obtenemos la extension del archivo
            $ext = $file->getClientOriginalExtension();
            $filename = $request->codigo . "." . $ext;
            $data['archivo'] = $filename;
            Storage::disk('local')->put($filename,  \File::get($file));
        }
        
        if($data['pedido'] == "0")
        {
            array_forget($data, 'pedido');
            array_forget($data, 'quedan');
        }
        else 
        {
            $data['quedan'] = $data['pedido'] + $data['quedan'];
        }
        
        $product->update($data);
        
        return redirect("/admin/productos/");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Product $product)
    {
        Storage::delete($product->archivo);
        $product->delete();
        
        return redirect('/admin/productos/');
    }

    public function actualizarPrecios(Request $request)
    {
        $porcentaje = $request->porcentaje;
        if ($request->id_categoria == 0) 
        {
            $products = Product::all();
        } 
        else 
        {
            $products = Product::where('id_categoria', $request->id_categoria )->get();
        }
        
        foreach ($products as $product) 
        {
            $costo = $product->costo;
            $monto = $product->monto;
            
            $newCosto = ceil(($costo + ($costo * $porcentaje / 100)));
            $newMonto = ceil($monto + ($monto * $porcentaje / 100));
            
            $product->costo = $newCosto;
            $product->monto = $newMonto;
            $product->save();
        }
        
        return redirect('/admin/productos/')->with('message', 'Los precios se han incrementado en un ' . $porcentaje . '%');
    }
}
