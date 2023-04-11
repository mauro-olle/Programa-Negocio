@extends('admin')

@section('content2')
    
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong>Operación Exitosa!</strong>
            {{ session()->get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <h1 class="form-group col-md-12">Crear {{ $type }}</h1>
    
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/admin/{{$type}}s">
        {!!csrf_field()!!}
        <div class="form-row">
            <div class="form-group col-md-2">
                <label>Nombre</label>
                <input required type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" >
            </div>
            
            <div class="form-group col-md-2">
                <label>Unidad de medida</label>
                <select required class="form-control" name="unidad">
                    <option value="" selected disabled hidden>Seleccione</option>
                    <option value="Uds">Unidades</option>
                    <option value="Kg">Kilogramos</option>
                </select>
            </div>
            
            <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success form-control">Agregar {{ $type }}</button>
            </div>
            
            <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <a href="/admin/{{$type}}s" class="btn btn-primary form-control">Volver</a>
            </div>
        </div>
    </form>
    
@endsection