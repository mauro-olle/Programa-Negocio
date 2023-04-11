@extends('admin')

@section('title', 'Editar {{ $type }}')
@section('content2')
    
    <h1 class="form-group col-md-12">Editar {{ $type }}</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <p>Por favor, corrige los errores debajo:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" files="true" action="/admin/{{$type}}s/{{$product->id}}" enctype="multipart/form-data">
        {{method_field('PUT')}}
        {{csrf_field()}}
        <div class="form-row">
            <div class="form-group col-md-2">
                <label>Nombre</label>
                <input maxlength="55" type="text" class="form-control" name="nombre" value="{{ old('nombre', $product->nombre) }}" >
            </div>

            <div class="form-group col-md-2">
                <label>Categoria</label>
                <select class="form-control" name="id_categoria" value="{{ old('id_categoria', $product->id_categoria) }}">
                    <option selected disabled>Elegir categoría</option>
                    @foreach($categories as $category)
                        @if($category->id == $product->id_categoria)
                            <option selected value="{{$category->id}}">{{$category->nombre}}</option>
                        @else
                            <option value="{{$category->id}}">{{$category->nombre}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
    
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label>Código de barras</label>
                    <input type="text" class="form-control" name="codigo" value="{{ old('codigo', $product->codigo) }}" >
                </div>
            </div>
            
            {{-- <div class="form-row">
                <div class="form-group col-md-1">
                    <label>Ideal</label>
                    <input required type="number" min="1" class="form-control" name="ideal" value="{{ old('ideal', $product->ideal) }}" >
                </div>
            </div> --}}
    
            <div class="form-row">
                <div class="form-group col-md-1">
                    <label>Agregar</label>
                    <input type="number" min="0" class="form-control" name="pedido" value="0" >
                </div>
            </div>
            
            <input type="hidden" class="form-control" name="quedan" value="{{ old('quedan', $product->quedan) }}" >

            <div class="form-row">
                <div class="form-group col-md-1">
                    <label>Aviso</label>
                    <input type="number" class="form-control" name="aviso" value="{{ old('aviso', $product->aviso) }}" >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-1">
                    <label>Costo</label>
                    <input class="form-control" name="costo" value="{{ old('costo', $product->costo) }}" >
                </div>
            </div>
            
            <div class="form-group col-md-1">
                <label>Venta</label>
                <input class="form-control" name="monto" value="{{ old('monto', $product->monto) }}" >
            </div>
            
            <div class="form-group col-md-1">
                <label>Imagen</label>
                <label class="btn btn-default btn-file col-md-12">
                    Elegir<input type="file" style="display: none;" name="archivo">
                </label>
            </div>

            {{-- <div class="form-group col-md-6">
                <label>&nbsp;</label>
                <a href="/admin/{{ $type }}s" class="btn btn-primary form-control">Volver</a>
            </div> --}}
                
            <div class="form-group col-md-1">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success form-control">
                    <span class="oi oi-check" style="display: inline-block; top: 2px;"></span>
                </button>
            </div>
            
        </div>
    </form>        
@endsection