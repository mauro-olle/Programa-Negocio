@extends('admin')

@section('content2')
    
    <div class="d-flex justify-content-between align-items-end">
        @if($activas == true)
        <h1 class="mt-2 mb-3">Listado de {{ $type }}s</h1>
        @else
        <h1 class="mt-2 mb-3">Listado de {{ $type }}s borradas</h1>
        @endif
        <p>
            @if($activas == true)
            <a href="{{route('categories.create')}}" class="btn btn-primary">Nueva {{ $type }}</a>
                {{-- @if (Auth::user()->id == 1 --}}
                    {{-- <a href="{{route('categories.recycleBin')}}" class="btn btn-danger"><span class="oi oi-trash"></span>Papelera</a> --}}
                {{-- @endif         --}}
            @else
            <a href="{{route('categories.index')}}" class="btn btn-primary">Volver</a>
            @endif
        </p>
    </div>
    
    <table class="table">
        <thead class="thead-dark"></thead>
            <tr>
                <th scope="col">Nombre</th>
                <th class="col">Unidad</th>
                {{-- @if (Auth::user()->id == 1 --}}
                    <th scope="col">
                        {{-- Acciones --}}
                        Editar
                    </th>
                {{-- @else
                    @if($activas == true)
                        <th scope="col">Editar</th>
                    @else
                        <th scope="col"></th>
                    @endif --}}
                {{-- @endif --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                @if ($category->activa == 1)
                <tr>
                @else
                <tr style="background-color: #ff8e8e;">
                @endif
                    <td>{{ $category->nombre }}</td>
                    <td>{{ $category->unidad }}</td>
                    <td>
                        @if($category->activa == 1)
                            <form action="{{ route('categories.delete', $category) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning"><span class="oi oi-pencil"></span></a>
                                {{-- @if (Auth::user()->id == 1 --}}
                                    {{-- <button class="btn btn-danger" type="submit"><span class="oi oi-trash"></span></button> --}}
                                {{-- @endif --}}
                            </form>
                        @else
                            <form action="{{ route('categories.resurrect', [$category]) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                {{-- @if (Auth::user()->id == 1 --}}
                                    <button class="btn btn-danger" type="submit"><span class="oi oi-action-undo"></span> Recuperar</button>
                                {{-- @endif --}}
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
      </table>
@endsection