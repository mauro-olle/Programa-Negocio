@extends('admin')

@section('title', 'Editar {{$type}}')
@section('content2')
    <h1 class="mt-5 form-group col-md-12">Editar {{$type}}</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <p>Por favor, corrige los errores debajo</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', [$type, $user]) }}" method="POST">
        {{method_field('PUT')}}
        {{csrf_field()}}
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="inputEmail4">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $user->nombre) }}" >
            </div>
            
            @if($user->id_uType == "1")    
            {{-- <div class="form-group col-md-2">
                <label for="inputCity">Dirección</label>
                <input type="text" class="form-control" name="direccion" value="{{ old('direccion', $user->direccion) }}" >
            </div>
    
            <div class="form-group col-md-2">
                <label for="inputEmail4">Teléfono</label>
                <input type="number" class="form-control" name="telefono" value="{{ old('telefono', $user->telefono) }}" >
            </div> --}}
    
            <div class="form-group col-md-2">
                <label>Password</label>
                <input require type="password" class="form-control" name="password" value="{{ old('password', $user->password) }}" >
            </div>
            @endif
        </div>
    
        <div class="form-row">
            <input type="hidden" name="id_uType" value="{{ old('id_uType', $user->id_uType) }}" >
                
            <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <a href="/admin/{{$type}}s" class="btn btn-primary form-control">Volver al listado</a>
            </div>
                
            <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success form-control">Editar {{$type}}</button>
            </div>
        </div>
    </form>        
@endsection