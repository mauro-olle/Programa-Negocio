@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="nav nav-pills nav-fill">
                            {{-- <li class="nav-item">
                                <a class="nav-link active" href="/admin">Inicio</a>
                            </li> --}}
                            
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/clientes">Clientes</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/encargados">Encargados</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/productos">Productos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/admin/categorias">Categorias</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/control">Control</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/admin/afip"><b>AFIP</b></a>
                            </li>

                            @php
                            $tipo = "productos"    
                            @endphp
                            <li>
                                <form method="POST" action="/admin/control/ingresos/{{$tipo}}" style="margin: initial;">
                                    {!!csrf_field()!!}
                                    <input type="hidden" class="form-control" name="id_cliente" value=2>
                                    <input type="hidden" class="form-control" name="id_forma_pago" value=1>
                                    <input type="hidden" class="form-control" name="id_encargado" value="{{ Auth::user()->id }}">
                                    <input type="hidden" class="form-control" name="id_type" value=1>
                                    <input type="hidden" class="form-control" name="deHoy" value=1>
                                    <input type="hidden" class="form-control" name="completada" value=0>
                                    <input type="hidden" class="form-control" name="monto" value=0>
                                    <input type="hidden" class="form-control" name="pago_efec" value=0>
                                    <input type="hidden" class="form-control" name="pago_tarj" value=0>
                                    <input type="hidden" class="form-control" name="descuento" value=0>
                                    
                                    <button type="submit" class="btn btn-success btn-lg btn-block" style="padding: 7px 35px">VENDER</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    
                        @yield('content2')
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection