<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="/css/light-bootstrap.css" rel="stylesheet">
		<link href="/css/toastr.min.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<style type="text/css" media="print">
			@page 
			{
				size:  auto;   /* auto is the initial value */
				margin: 0mm;  /* this affects the margin in the printer settings */
			}

			html
			{
				background-color: #FFFFFF; 
				margin: 0px;  /* this affects the margin on the html before sending to printer */
			}
		</style>
	</head>

	<body style="font-family:arial;">
		<div class="container-fluid">
			<div class="row">
				<div class="contenedor" style="font-weight: 500;">
					<div class="text-center">
						<p style="font-size: 11pt; margin-top: 1rem;">
							COMPROBANTE ELECTRONICO ORIGINAL
						</p>
					</div>
					<div style="margin-bottom: 1.5rem;">
						<img class="logo" src="/Don-Agustin.svg" style="width: 75%; padding: 0pt;">
					</div>
					{{-- <div class="page-header text-center">
						<h1>
							<div>
								<p style="font-size: 19pt">de Mauro Agustín Duarte</p>
							</div>
						</h1>
					</div> --}}
					<p>
						<address class="text-center"  style="font-size:15px;font-weight:bolder;">
							CP 4230 | Entre Ríos 1340 | Frias, Santiago del Estero
							<br>
							CUIT: 20-30353895-0 | Ingresos Brutos: 20313538950
							<br>
							{{-- IVA:  --}}
							Responsable Monotributo | Inicio Actividades: 01/12/19
						</address>
					</p>
					<hr>
					<div class="row">
						<div class="flex41 text-left pad15">
							<address style="font-size:13pt">
								@if (isset($caenum))
									<b>{{ ucfirst(strtolower($tipoCbteNom))}} Nº</b>
									<br>
									{{$compNum}}
								@else
									Comprobante no 
									<br>
									válido como factura
								@endif
							</address>
						</div>
						<div class="flex16 pad15">
							<h1 class="text-center" style="font-size:36pt">
								@if (isset($caenum))
									<strong>{{ $tipoCbte }}</strong>
								@else
									<strong>X</strong>
								@endif
							</h1>
						</div>
						<div class="flex41 text-right pad15">
							<address style="font-size:13pt;margin-right: 5px;">
								<strong>Fecha: </strong>{{ date("d/m/y", strtotime($cbteFch)) }}
								<p>
								<strong>Hora: </strong>{{ date("H:i", strtotime($cbteFch)) }} hs
							</address>
						</div>
					</div>
					@if (isset($caenum))
						<address >
							@if ($docTipo == 80)
								Señor(es): <strong>{{ $nombreRS }}</strong>
								<br>
								CUIT: <strong>{{ $_cuit }}</strong>
								<br>
								IVA: <strong>Responsable Inscripto</strong>
							@elseif($docTipo == 99)
								Señor(es): <strong>Consumidor Final</strong>
								<br>
								IVA: <strong>Consumidor Final</strong>
							@endif

							@if ($cbte == "NDC")
								<br>Factura {{ $tipoCbte }}: <strong> {{ $facturaAsoc }} </strong>
							@endif
						</address>
					@endif
					
					<table class="table table-sm table-bordered table-hover">
						<thead>
							<tr>
								<th>Descripción</th>
								<th class="text-center" style="width: 1%;">Cant.</th>
								<th class="text-center" style="width: 15%;">P. Unit.</th>
								<th class="text-center" style="width: 1%;">Importe</th>
							</tr>
						</thead>
						<tbody style="font-size:16pt;">
							@if ($concepto == "Detalle") 
								@foreach ($orders_products as $item)
									<tr>
										<td>{{ ucfirst(strtolower($item->nombre)) }}</td>
										@if ((int)$item->cantidad == $item->cantidad)
										<td class="text-center">{{ $item->cantidad }}</td>
										@else
										<td class="text-center">{{ sprintf("%.3f", $item->cantidad) }}</td>
										@endif
										<td class="text-center"><b>$</b>{{ sprintf("%.2f", $item->monto) }}</td>
										<td class="text-center"><b>$</b>{{ sprintf("%.2f", ceil($item->monto * $item->cantidad)) }}</td>
									</tr>
								@endforeach
							@else {{--  Si CONCEPTO == VARIOS o si se eligió generar una Nota de Crédito --}}
								@if (isset($caenum) && $detalleOpcional && $montoOpcional) 
									<tr>
										<td>{{ ucfirst(strtolower($concepto)) }}</td>
										<td class="text-center">1</td>
										<td class="text-center"><b>$</b>{{ sprintf("%.2f", ($impTotal - $montoOpcional)) }}</td>
										<td class="text-center"><b>$</b>{{ sprintf("%.2f", ($impTotal - $montoOpcional)) }}</td>
									</tr>
								@else 
									<tr>
										<td>{{ $concepto }}</td>
										<td class="text-center">1</td>
										<td class="text-center"><b>$</b>{{ $impTotal }}</td>
										<td class="text-center"><b>$</b>{{ $impTotal }}</td>
									</tr>
								@endif
							@endif
							
							@if (isset($caenum) && $detalleOpcional && $montoOpcional)
								<tr>
									<td>{{ ucfirst(strtolower($detalleOpcional)) }}</td>
									<td class="text-center">1</td>
									<td class="text-center"><b>$</b>{{ sprintf("%.2f", $montoOpcional) }}</td>
									<td class="text-center"><b>$</b>{{ sprintf("%.2f", $montoOpcional) }}</td>
								</tr>
							@endif
							
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							@if (isset($caenum) && $docTipo == 80 && $tipoCbte != "C") 
								<tr>
									<td colspan="3" class="text-right"><strong>Subtotal Neto</strong></td> 
									<td class="text-center"><b>$</b>{{ $impNeto }}</td>
								</tr>
								<tr>
									<td colspan="3" class="text-right"><strong>IVA</strong></td>
									<td class="text-center"><b>$</b>{{ $impIVA }}</td>
								</tr>
							@endif
							@if ($descuento > 0 && ((isset($caenum) && $cbte != "NDC") || !isset($caenum)))
							<tr>
								<td colspan="3" class="text-right">
									<strong>Descuento</strong>
								</td>
								<td class="text-right"><b>$</b>{{ $descuento }}</td>
							</tr>
							@endif
							<tr>
								<td colspan="3" class="text-right">
									@if (isset($caenum))
									<strong>Total Factura</strong>
									@else
									<strong>Total</strong>
									@endif
								</td>
								<td class="text-right"><b>$</b>{{ $impTotal }}</td>
							</tr>
						</tbody>
					</table>

					@if (isset($caenum))
						<address class="text-left"  style="font-size:15pt;">
							Forma de pago: <strong>{{ $tipoPago }}</strong>
						</address>
						
						<hr>
						
						<div class="row">
							<div class="pad15 mw50 padT10">
								<img class="mw100" src="/logo-afip.svg">
							</div>
							<div class="pad15 mw50" style="padding-left: 10px;">
								<div>
									<strong>comprobante autorizado</strong>
									<small>
										<strong style="font-size: large;">CAE: {{$caenum}}</strong>
										<br>
										<strong style="font-size:10pt;">Vencimiento CAE: {{ date("d/m/Y", strtotime($caefvt)) }}</strong>
									</small>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 margTB" style="zoom: 74%; padding-left: 20px; text-align: center; font-size: 17pt;">
								@if (isset($codigoBarra))
									@php
										$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
										echo $generator->getBarcode($codigoBarra, $generator::TYPE_CODE_128, 2, 100);
									@endphp
									{{ $codigoBarra }}	
								@else
									@php
										$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
										echo $generator->getBarcode($cuit . $comprob . $punto . $caenum . $caefvt, $generator::TYPE_CODE_128, 2, 100);
									@endphp
									{{ $cuit . $comprob . $punto . $caenum . $caefvt }}	
								@endif
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
		<script src="/js/app.js"></script>
	</body>
</html>
 @php
	if(isset($codigoBarra))
	{
		echo '<script type="text/javascript">
			$(document)
			.ready(function() 
			{
				toastr.options = {
					timeOut: "1700",
					positionClass: "toast-top-full-width",
					onHidden: function() { 
						window.print();
						window.close();
					}
				}
				toastr.success("Imprimiendo...");
			});
		</script>';
	}
	else
	{
		echo '<script type="text/javascript">
			$(document)
			.ready(function() 
			{
				toastr.options = {
					timeOut: "1700",
					positionClass: "toast-top-full-width",
					onHidden: function() { 
						window.print();
						window.close();
					}
				}
				toastr.success("Imprimiendo...");
			});
			window.onunload = refreshParent;
			function refreshParent() {
				window.opener.location.reload();
			}
		</script>';
	}
@endphp