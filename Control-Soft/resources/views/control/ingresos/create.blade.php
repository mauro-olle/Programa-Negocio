@extends('control.index')

@include('afip.datosFactura')
    
@if ($regAfipFct != null)
    @include('afip.datosNdC')
@endif

@section('content3')
<style>
    #page-wrapper{
        position: relative;
        margin-right: -15px;
    }

    input[type="text"]::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: red;
        opacity: 1; /* Firefox */
    }
</style>
    <div id="main" >
        @if(session()->has('message'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <strong>Error!</strong>
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
            <div style="display: flex;">
                <div>
                    <h1 style="margin-top: auto;">{{ $titulo }}</h1>
                </div>
                @if($subtitulo != "La orden todavía no existe")
                    <div style="margin-left: auto; margin-top: 3px;">
                        @if($order->completada == 1)
                            @if($order->fiado != 0)
                                <label class="btn btn-danger">FIADO ${{ $order->fiado }}</label>
                            @endif
                            
                            @if($order->descuento > 0)
                                <label class="btn btn-danger">DESC. ${{ $order->descuento }}</label>
                            @endif
                            
                            @if ($order->id_forma_pago != 4)
                                <label class="btn btn-success">PAGADO ${{ $order->pago_efec + $order->pago_tarj }}</label>
                            @endif
                        @else
                            <button class="btn btn-success">TOTAL $ !{ totalSuma }!</button>
                            <template v-if="fdpagoElegida != 4">
                                <input v-if="totalSuma > 0" class="btn btn-default" v-on:click="resetPagocon()" placeholder="$PAGO" min="0" v-model="pagoCon" type="number" style="width: 120px;">
                                <button v-if="vuelto > 0" class="btn btn-primary">VUELTO $ !{ vuelto }!</button>   
                            </template>
                        @endif
                    </div>
                    @if($order->completada == 1)
                        <div style="margin-top: 3px; margin-left: 5px;">
                            @if(str_contains(URL::previous(), 'clientes'))
                                <a href="{{ URL::previous() }}" class="btn btn-primary"><b>VOLVER</b></a>
                            @else
                                <a href="/admin/control/ingresos/{{$tipo}}" class="btn btn-primary"><b>VOLVER</b></a>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
            <div style="display: flex;">
                <div style="margin-left: auto; margin-top: 3px;">
                    @if($order->completada == 1)
                        
                    @else
                        <div v-if="(totalSuma > 0 || descuento > 0) && fdpagoElegida == 1" class="input-group" style="width: 120px;">
                            <div class="input-group-addon" style="font-size: 20px;color: white;background-color: #ff0000;">$</div>
                            <input placeholder="DESC" v-on:click="resetDesc()" type="text" v-model="descuento" class="form-control" style="color: red; font-weight: bold; padding: 1px; font-size: 20px;text-align: center;" oninput="this.value = this.value.replace(/\D+/g, '')">
                        </div>
                    @endif
                </div>
            </div>
        @if($order->completada == 1)
            <p>
                <h4 class="mt-2 mb-3">{{ $subtitulo }}</h4>
            </p>
            <div style="margin-top: 0px;">
                <h4 style="margin-top: 10px;color: darkviolet;" class="mt-2 mb-3">{{ $pie }}</h4>
                @if($order->fiado != 0)
                <h4 style="margin-top: 10px;color: red;" class="mt-2 mb-3">Quedó debiendo ${{ $order->fiado }}</h4>
                @endif
            </div>
        @endif
        @if($subtitulo != "La orden todavía no existe")
            <div style="margin-top: 2px; margin-left: auto;">
                @if($order->completada != 1)
                    <div class="form-group col-md-12" style="margin-top: 0px;padding-left: 0px; padding-right: 0px;">
                        <form method="POST" action="/admin/control/{{$tipo}}/cerrar/{{ $order->id }}">
                            {!!csrf_field()!!}
                            
                            <div v-if="clienteElegido == 2" class="form-group col-md-2" style="padding-left: 0px;">
                                <label>Cliente</label>
                                <select v-model="clienteElegido" class="form-control pd6" name="id_cliente" value="{{ old('id_cliente') }}">
                                    <option v-for="cliente in clientes" v-bind:value="cliente.id">!{ cliente.nombre }!</option>
                                </select>
                            </div>
                            <div v-else class="form-group col-md-2" style="padding-left: 0px;">
                                <label>Cliente</label>
                                <select v-model="clienteElegido" class="form-control pd6" name="id_cliente" value="{{ old('id_cliente') }}">
                                    <option v-for="cliente in clientes" v-bind:value="cliente.id">!{ cliente.nombre }!</option>
                                </select>
                            </div>
    
                            <div v-if="clienteElegido != 2 && fdpagoElegida != 4 && fiado > 0" class="form-group col-md-2" style="padding-left: 0px;">
                                <label>Deberá</label>
                                <input readonly required type="number" min="0" class="form-control pd6 text-center" name="fiado" v-bind:value="fiado" style="background-color: #ffdcdc; color: red; font-weight: bold; font-size: large;">
                            </div>
    
                            <div class="form-group col-md-2" style="padding-left: 0px;">
                                <label>F. de pago</label>
                                <select required v-model="fdpagoElegida" class="form-control pd6" name="id_forma_pago" value="{{ old('id_forma_pago') }}">
                                    <template v-if="clienteElegido == 2">
                                        <option v-for="fdpago in fsdpago" v-bind:value="fdpago.id" v-if="fdpago.id != 4">
                                            !{ fdpago.nombre }!    
                                        </option>
                                    </template>
                                    <template v-else>
                                        <option v-for="fdpago in fsdpago" v-bind:value="fdpago.id">
                                            !{ fdpago.nombre }!    
                                        </option>
                                    </template>
                                </select>
                            </div>
                            <template v-if="fdpagoElegida == 3 && pagoCon > 0">
                                <div class="form-group col-md-2" style="padding-left: 0px;">
                                    <label>Efectivo</label>    
                                    <input required class="form-control pd6 text-center" type="number" min="0" name="pago_efec" v-on:click="resetPagoEfec()" placeholder="Efectivo" v-model.number="pagoEfec">
                                </div>
                                <div class="form-group col-md-2" style="padding-left: 0px;">
                                    <label>Tarjeta</label>    
                                    <input required class="form-control pd6 text-center" type="number" min="0" name="pago_tarj" v-on:click="resetPagoTarj()" placeholder="Tarjeta"  v-model="pagoEfecTarj">
                                </div>
                            </template>
                            
                            <input name="descuento" type="hidden" v-bind:value="this.descuento">

                            <div class="form-group col-md-2" style="padding-left: 0px; padding-right: 0px;">
                                <label>&nbsp;</label>
                                <template v-if="fdpagoElegida != 4 && (clienteElegido == 2 && pagoCon < totalSuma || fiado === void(0))">
                                    <button type="submit" disabled class="btn btn-danger btn-block"><b>CERRAR</b></button>
                                </template>
                                <template v-else>
                                    <button type="submit" class="btn btn-danger btn-block"><b>CERRAR</b></button>
                                </template>
                            </div>
                        </form>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group col-md-7" style="margin-top: 0px;padding-left: 0px; padding-right: 0px;">
                        <form autocomplete="off" v-on:submit.prevent="createSuborden" method="post">
                        {{-------------------  Productos  -------------------}}
                            <div class="form-group col-md-3" style="padding-left: 0px;">
                                <label>Cantidad</label>
                                <input required type="text" class="form-control text-center pd6" id="cantidad" name="cantidad" v-model="newCant" oninput="validate(this)">
                            </div>
                            
                            <div class="form-group col-md-4" style="padding-left: 0px;padding-right: 0px;">
                                <label>Código de barras</label>
                                <input required ref="codigo" type="search" class="form-control text-center pd6" id="codigo" name="codigo" v-model="newCod" oninput="this.value = this.value.replace(/\D+/g, '')">
                            </div>
                                        
                            {{-------------------  Agregar  -------------------}}    
                            <div class="form-group col-md-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success form-control">
                                    <span class="oi oi-plus"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            
            <table class="table table-striped" style="font-size: 14px; margin-bottom: 92px; z-index: 1; position: relative;">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Cant.</th>
                        <th scope="col">Producto</th>
                        <th scope="col">P.Unit.</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Foto</th>
                        @if($order->completada != 1)
                        <th scope="col">Borrar</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="subOrden in subOrdenes">
                        <td v-if="parseInt(subOrden.cantidad) == subOrden.cantidad">!{ subOrden.cantidad }! 
                            <b v-if="subOrden.cantidad == 1 && subOrden.unidad.substring(subOrden.unidad.length - 1) ==='s'">
                                !{ subOrden.unidad.substring(0, subOrden.unidad.length - 1) }!
                            </b>
                            <b v-else>
                                !{ subOrden.unidad }!
                            </b>
                        </td>
                        <td v-else>!{ subOrden.cantidad.toFixed(3) }! 
                            <b>!{ subOrden.unidad }!</b>
                        </td>
                        <td>!{ subOrden.nombre }!</td>
                        <td><b>$</b> !{ subOrden.monto }!</td>
                        <td><b>$</b> !{ Math.ceil(subOrden.monto * subOrden.cantidad) }!</td>
                        <td>!{ subOrden.created_at | formatDate }!</td>
                        <td>!{ subOrden.created_at | formatTime }!</td>
                        <td>
                            <img class="zoom" width="36px" v-bind:src="'/uploads/' + subOrden.archivo">
                        </td>
                        @if($order->completada != 1)
                            <td>
                                <button class="btn btn-danger" v-on:click.prevent="deleteSuborden(subOrden)" style="height: 34px;">
                                    <span class="oi oi-trash"></span>
                                </button>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            
            @if ($order->completada == 1)
                <div class="row">
                    <div class="col-md-12" style="position: absolute; bottom: 15px;">
                        <div class="row" style="margin-right: 15px;">
                            <div class="col-md-12">
                                <hr style="margin-top: 0px; margin-bottom: 30px;">
                            </div>
                            @if ($order->idAfipFct == 0)
                                <div class="col-md-6">
                                    <a href="/admin/verTicket/{{$order->id}}" target="print_popup" class="btn btn-info btn-lg btn-block" type="button" onclick="window.open(this.href,this.target,'width=650,height=650');return false;">TICKET</a>
                                </div>
                                <div class="col-md-6">
                                    <input class="btn btn-success btn-lg btn-block" type="button" value="FACTURAR" data-toggle="modal" data-target="#createFct">
                                </div>
                            @else
                                <div class="col-md-6">
                                    <a href="/admin/afip/verFactura/{{$order->idAfipFct}}" target="print_popup" class="btn btn-success btn-lg btn-block" type="button" onclick="window.open(this.href,this.target,'width=650,height=650');return false;">VER FACTURA</a>
                                </div>    
                                @if ($order->idAfipNdc == 0)
                                    <div class="col-md-6">
                                        <input class="btn btn-danger btn-lg btn-block" type="button" value="GENERAR NOTA DE CREDITO" data-toggle="modal" data-target="#createNdC">
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <a href="/admin/afip/verNotaDeCredito/{{$order->idAfipNdc}}" target="print_popup" class="btn btn-danger btn-lg btn-block" type="button" onclick="window.open(this.href,this.target,'width=650,height=650');return false;">VER NOTA DE CREDITO</a>
                                    </div>
                                @endif
                            @endif 
                        </div>
                    </div>
                </div>
                
            @endif
        @endif
    </div>
    <script>
        window.App = {
            id_order: {!! json_encode($order->id) !!}
        }
        var validate = function(e) {
            e.value = e.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            var t = e.value;
            e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 4)) : t;
        }
    </script>
@endsection
