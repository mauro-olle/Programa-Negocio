@extends('admin')
@section('content2')
    <div id="productos">
        {{-- !{ $data }! --}}
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <strong>Operación Exitosa!</strong>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        
        <div class="d-flex justify-content-between align-items-end">
            
            <h1 class="mt-2 mb-3">Listado de {{ $type }}s</h1>
            
            <p>
                <div class="form-inline">
                    <div class="form-group">
                        <a href="{{route('products.create')}}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-plus"></span>
                            Nuevo {{ $type }}
                        </a>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <span class="glyphicon glyphicon-filter"></span> <b> Filtrar</b>
                        </button>
                    </div>
                    {{-- @if (Auth::user()->id == 1 --}}
                        <div class="form-group">
                            <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
                                <span class="glyphicon glyphicon-usd"></span> <b> Actualizar</b>
                            </button>        
                        </div>
                    {{-- @endif --}}
                    <div class="input-group">
                        <input type="search" v-model="input" class="form-control" placeholder="Buscar productos...">
                        <div class="input-group-addon">
                            <span aria-hidden="true" class="glyphicon glyphicon-search"></span>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <form method="GET" action="{{ url('/admin/productos/buscar') }}">
                            <input autofocus type="search" name="keyword" class="form-control" placeholder="Buscar productos...">
                            <button type="submit" style="display: none;" class="btn btn-primary">Buscar</button>
                        </form>
                    </div> --}}
                </div>
            </p>
        </div>
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <p>
                    <form class="form-inline" method="POST" action="/admin/productos/filtro">
                        {!!csrf_field()!!}
                        
                        <div class="form-group">
                            <select class="form-control" name="id_categoria" value="{{ old('id_categoria') }}">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success"><span class="oi oi-check"></span></button>
                        <a href="{{ route('products.index') }}" class="btn btn-danger"><span class="oi oi-x"></span><b> Borrar filtro</b></a>
                    </form>
                </p>
            </div>
        </div>
        {{-- @if (Auth::user()->id == 1 --}}
            <div class="collapse" id="collapseExample2">
                <div class="card card-body">
                    <p>
                        <form class="form-inline" method="POST" action="/admin/productos/actualizarprecios">
                            {!!csrf_field()!!}
                            
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span aria-hidden="true"><b>+</b></span>
                                </div>
                                <input style="width: 46px; padding: 6px 0px 6px 6px; text-align: center;" type="number" required min="1" class="form-control" name="porcentaje">
                                <div class="input-group-addon">
                                    <span aria-hidden="true"><b>%</b></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <select class="form-control" name="id_categoria">
                                    <option selected value=0>Actualizar todas</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success"><span class="oi oi-check"></span></button>
                        </form>
                    </p>
                </div>
            </div>
        {{-- @endif --}}
        <table class="table">
            <thead class="thead-dark"></thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Código barra</th>
                    {{-- <th scope="col">Ideal</th> --}}
                    <th scope="col">Ingreso</th>
                    <th scope="col">Quedan</th>
                    <th scope="col">Costo</th>
                    <th scope="col">Venta</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Editar</th>
                </tr>
            </thead>
            <tbody v-if="keywords == null || keywords == ''">
                @foreach ($products as $product)
                    @foreach ($categories as $category)
                        @if($product->id_categoria == $category->id)
                            @php
                                $catNombre = $category->nombre;
                                $unidad = $category->unidad;
                            @endphp 
                        @endif
                    @endforeach
                    
                    @if ($product->quedan > $product->aviso)
                    <tr>
                    @else
                    <tr style="background-color: pink;">  
                    @endif
                        <td>{{ $product->nombre }}</td>
                        <td>
                            {{ $catNombre }}
                        </td>
                        <td>{{ $product->codigo }}</td>
                        {{-- <td>{{ $product->ideal }} <b>uds.</b></td> --}}
                        <td>
                            @if ((int)$product->pedido == $product->pedido)
                            {{ $product->pedido }} 
                            @else
                            {{ sprintf("%.3f", $product->pedido) }} 
                            @endif
                            
                            <b>{{ $unidad }}</b>
                        </td>
                        @if ($product->quedan > $product->aviso)
                            <td>
                                @if ((int)$product->quedan == $product->quedan)
                                {{ $product->quedan }} 
                                @else
                                {{ sprintf("%.3f", $product->quedan) }} 
                                @endif
                                
                                <b>{{ $unidad }}</b>
                            </td>
                        @else
                            <td style="color: red;">
                                @if ((int)$product->quedan == $product->quedan)
                                {{ $product->quedan }} 
                                @else
                                {{ sprintf("%.3f", $product->quedan) }} 
                                @endif
                                
                                <b>{{ $unidad }}</b>
                            </td>
                        @endif
                        <td><b>$</b> {{ $product->costo }}</td>
                        <td><b>$</b> {{ $product->monto }}</td>
                        <td>
                            <img class="zoom" width="36px" src="../../uploads/{{$product->archivo}}">
                        </td>
                        <td>
                            {{-- <form action="{{ route('products.delete', $product) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }} --}}
                                {{-- <a href="{{ route('products.show', $product) }}" class="btn btn-success"><span class="oi oi-eye"></span></a>  --}}
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning"><span class="oi oi-pencil"></span></a>
                                {{-- <button class="btn btn-danger" type="submit"><span class="oi oi-trash"></span></button>  --}}
                            {{-- </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tbody v-else>
                <tr v-for="product in products">
                    <td v-html="highlight(product.nombre)"></td>
                    <td>!{ product.categoria }!</td>
                    <td>!{ product.codigo }!</td>
                    {{-- <td>!{ product.ideal }! <b>uds.</b></td> --}}
                    <td>!{ product.pedido }! <b>uds.</b></td>
                    <td v-if="product.quedan > product.aviso">!{ product.quedan }! <b>uds.</b></td>
                    <td v-else style="color: red;">!{ product.quedan }! <b>uds.</b></td>
                    <td><b>$</b> !{ product.costo }!</td>
                    <td><b>$</b> !{ product.monto }!</td>
                    <td>
                        <img class="zoom" width="36px" v-bind:src="'/uploads/' + product.archivo">
                    </td>
                    <td>
                        <a v-bind:href="'/admin/productos/' + product.id + '/editar'" class="btn btn-warning"><span class="oi oi-pencil"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="keywords == null || keywords == ''">
            {{ $products->links() }}
        </div>
    </div>
@endsection
