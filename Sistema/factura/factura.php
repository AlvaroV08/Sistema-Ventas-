<?php
	//Numeros a Letras
    use Luecano\NumeroALetras\NumeroALetras;
	$subtotal 	= 0;
	$igv 	 	= 0;
	$impuesto 	= 0;
	$tl_snigv   = 0;
	$total 		= 0;
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script defer src="../js/generateQR.js"></script>
</head>

<style type="text/css">

*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}
p, label, span, table{
	font-family: 'BrixSansRegular';
	font-size: 9pt;
}
img{
	width: 125px;
}
tr td p{
	text-transform: uppercase;
}
.h2{
	font-family: 'BrixSansBlack';
	font-size: 16pt;
}
.h3{
	font-family: 'BrixSansBlack';
	font-size: 12pt;
	display: block;
	background: #0a4661;
	color: #FFF;
	text-align: center;
	padding: 3px;
	margin-bottom: 5px;
}
#page_pdf{
	width: 95%;
	margin: 15px auto 10px auto;
}

#factura_head, #factura_cliente, #factura_detalle{
	width: 100%;
	margin-bottom: 10px;
}
.logo_factura{
	width: 25%;
}
.logo_factura img{
	width: 150px;
}
.info_empresa{
	width: 50%;
	text-align: center;
}
.info_factura{
	border: 1px solid black; 
	border-radius: 5px; 
	width: 100%;
	margin-bottom: 10px;" 
}
.info_cliente{
	width: 98%;
	margin: 2.5px;
}
.datos_cliente{
	width: 100%;
}
.datos_cliente tr td{
	width: 50%;
}
.datos_cliente tr td label{
	width: 140px; 
	display: inline-block;
	padding: 5px 0;
	font-size: 14px;
}
.datos_cliente{
	padding: 0px 10px 0 10px;
}
.datos_cliente tr td p{
	display: inline-block;
	padding: 5px 0;
	font-size: 14px;
}
.textleft{
	text-align: left;
}
.textcenter{
	text-align: center;
}
.textright{
	text-align: right;
}
.textbold{
	font-weight: bold;
}
.info_ruc{
	width: 40%;
}
.ruc{
	padding: 5px;
	border:  1px solid #000000;
	overflow: hidden;
	border-radius: 5px;
}
.ruc p{
	font-size: 18px;
	font-weight: bolder;
	padding: 7px;

}
.cliente{
	border: 1.2px solid black;
	border-radius: 5px;
	padding: 10px; 
	font-size:12px; 
	width: 100%;
}
.red{
	color: red;
}
.cliente label, .cliente p{
	font-size: 12px;
}
.label_gracias{
	font-family: verdana;
	font-weight: bold;
	font-style: italic;
	text-align: center;
	margin-top: 20px;
}
.anulada{
	width: 80%;
	position:absolute; 
	left:50%; 
	top:50%; 
	transform: translateX(-50%) translateY(-50%);
}
.nota{
	border: 1px solid black;
	border-radius: 5px;
}
.nota p{
	padding: 15px;
	font-size: 14px;
	text-align: center;
}
.contenedor{
	width: 20%;
	text-align: center;
}
.contenedor img {
	margin-left: 5px;
}
.contenedor p{
	margin: 0 0 10 15px;
}
.width30{
	width: 30%;
}
.hash{
	margin-left: 30px;
	margin-top: 20px;
	margin-bottom: 10px;
}
#factura_detalle{
	border-collapse: collapse;
}
#factura_detalle thead th{
	background: #000000;
	color: #FFF;
	padding: 5px;
}
#detalle_productos tr td{
	padding: 5px;
}
#detalle_productos tr:nth-child(even) {
    background: #ededed;
}
#detalle_totales span{
	font-family: 'BrixSansBlack';
}
#detalle_totales tr td{
	padding: 2px 7px;
}
</style>

<body>
<?php echo $anulada; ?>
<div id="page_pdf" style="margin: 15px auto 10px auto;">
	<table id="factura_head" style="margin-bottom: 10px;">
		<tr>
			<td class="logo_factura">
				<div>
					<?php
					$logo = "img/logo-tr.png";
					$imagen_logo = "data:image/png;base64," . base64_encode(file_get_contents($logo));
					?>
					<img src="<?php echo $imagen_logo ?>">
				</div>
			</td>

			<td class="info_empresa">
				<?php 
					if($result_config > 0){
						$igv = $configuracion['igv'];
				 ?>
				<div >
					<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
					<p >AV. JOSE MATIAS MANZANILLA NRO. 750 URB. SAN MIGUEL</p>
					<p >ICA - ICA - ICA</p>
					<p >Email: <?php echo $configuracion['email']; ?></p>
				</div> 
				<?php 
					}
				 ?>
			</td>
			<td class="info_ruc">

				<div class="ruc">
					<?php $ruc ='20600566289';?>
					<p class="textcenter">R.U.C. N° <?php echo $ruc; ?></p>
					<p class="textcenter">BOLETA DE VENTA ELECTRONICA</p>
					<?php 
						if(!empty($factura['f'])){ ?>
						<p  class="textcenter red">N° F001-000<?php echo $factura['f']; ?></p>
						<?php 
						$correlativo = $factura['f'];
						}else{ ?>
						<p  class="textcenter red">N° B001-000<?php echo $factura['b']; ?></p>
						<?php 
						$correlativo = $factura['b'];
						} ?>
				</div> 
			</td>
		</tr>
	</table>
	
	<p style="margin: 5px;">Sucursal: AV. AYABACA 961 - ICA - ICA - ICA</p> 
	<div class="info_cliente">
	<table id="factura_cliente">
		<tr>
			<td>
				<div class="cliente">
					<table class="datos_cliente">
						<?php 
						if(empty($factura['dni'])){
							$dni = $factura['ruc'];
							$nombre = $factura['razon_social'];
							$docCliente = $factura['ruc'];
							$recibo = '01';
							$serie='F001';
							$tipoDoc = '6';
						}
						if(empty($factura['ruc'])){
							$dni = $factura['dni']; 
							$nombre = $factura['nombres'].' '.$factura['apellido_paterno'];
							$docCliente = $factura['dni'];
							$recibo = '03';
							$serie='B001';
							$tipoDoc = '1';
						}
						$fecha = $factura['fecha'];
						$nofactura = '000'.$factura['nofactura'];
						//$descuentos = $factura['descuentos'];
						?>
						<tr>
							<td>
								<label>Fecha de Vencimiento: </label>
								<p><?php  echo $factura['fecha']; ?></p>
							</td>
						</tr>
						<tr>
							<td>
								<label>Fecha de Emision: </label>
								<p><?php  echo $factura['fecha']; ?></p>
							</td>
						</tr>	
						<tr>	
							<td>
								<label>Razon Social: </label>
								<p><?php  echo $nombre ?></p>
							</td>
						</tr>
						<tr>
							<td>
								<label>R.U.C. /DNI: </label>
								<p><?php  echo $dni; ?></p>
							</td>
						</tr>
						<tr>
							<td>
								<label>Condición de Pago: </label>
								<p><?php echo $factura['metodo_pago']?></p>
							</td>
						</tr>
						<tr>
							<td>
								<label>Glosa: </label>
								<p>VENTA - PUNTO DE VENTA</p>
							</td>
						</tr>	
						<tr>
							<td>
								<label>Direccion: </label>
								<p><?php echo $factura['direccion']; ?></p>
							</td>
						</tr>
						<tr>	
							<td>
								<label>Moneda: </label>
								<p>SOLES</p>
							</td>
						</tr>	
					</table>
				</div>
			</td>		
		</tr>
	</table>
	</div>

	<div class="info_factura">
	<table id="factura_detalle">
			<thead>
				<tr>
					
					<th>Codigo</th>
					<th class="textcenter">Descripción</th>
					<th class="textcenter">Cantidad</th>
					<th class="textright">Precio Unit.</th>
					<th class="textright">Valor Unit.</th>
					<th class="textright"> Valor Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

			<?php
				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
			 ?>
				<tr>
					<td class="textcenter" ><?php echo $row['sku']; ?></td>
					<td class="textcenter width30"><?php echo $row['descripcion']; ?></td>
					<td class="textcenter"><?php echo $row['cantidad']; ?></td>
					<td class="textright"><?php echo $row['precio_venta']; ?></td>
					<td class="textright"><?php echo round($row['precio_venta']/1.18,2); ?></td>
					
					<?php $valorTotal = $row['cantidad']*round($row['precio_venta']/1.18,2); ?>
					<td class="textright" style="text-align:right; font-size: 14px;"><?php echo $valorTotal; ?></td>
				</tr>
			<?php
				$descuento = $row['descuento'];
				$descuento_igv =round($descuento/1.18,2);
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}
				$tl_snigv 	= round($subtotal / (1+$igv / 100), 2);
				$subtotal = $subtotal-$descuento;
				$impuesto 	= round($subtotal - $tl_snigv+$descuento_igv,2 );
				$total 		= round($tl_snigv-$descuento_igv + $impuesto,2);
			?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="5" class="textright" style="padding-top:25px;"><span>SUBTOTAL SIN DSCTO S/.</span></td>
					<td style="padding-top:25px;" class="textright"><span><?php  echo $tl_snigv; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>DESCUENTOS S/.</span></td>
					<td class="textright"><span><?php  echo $descuento_igv; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>OP GRAVADAS S/.</span></td>
					<td class="textright"><span><?php  echo $tl_snigv-$descuento_igv; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>OP INAFECTAS S/.</span></td>
					<td class="textright"><span><?php  echo $opInafectas; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>OP EXONERADAS S/.</span></td>
					<td class="textright"><span><?php  echo $opExoneradas; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>SUBTOTAL S/.</span></td>
					<td class="textright"><span><?php  echo $tl_snigv-$descuento_igv+$opExoneradas+$opInafectas; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>IGV (<?php echo $igv; ?> %)</span></td>
					<td class="textright"><span><?php echo $impuesto; ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright textbold"><span>TOTAL S/.</span></td>
					<td class="textright textbold"><span><?php echo $total; ?></span></td>
				</tr> 
			</tfoot>
				
	</table>
	<?php 
		$formatter = new NumeroALetras();
    	$totalTexto = 'SON: '.$formatter->toInvoice($total, 2, 'soles'); ?>
    	<span style="margin:10px;"><?php echo $totalTexto; ?></span>
    <div class="contenedor">
    	<?php
		$QR = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=".$ruc."|".$recibo."|".$serie."|000".$correlativo."|".$impuesto."|".$total."|".$fecha."|".$tipoDoc."|".$docCliente."&choe=UTF-8";
		$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($QR));
		?>
	<img src="<?php echo $imagenBase64 ?>" />
	<p>Representación QR del documento</p>
    </div>
    <div class="hash">
		<span>Hash: <?php echo $factura['hash']; ?></span>
    </div>

	</div>
	<div class="nota" style="border: 1px solid black; border-radius: 5px;">
		<p style="padding: 10px; font-size: 14px; text-align: center;">La consulta del presente documento puede realizarse en la página: http://saas.cloud-is.com/fec/faces/consulta Esta es una representación impresa de la factura electrónica, generada en el Sistema de Facturación Electrónica de CIS. Autorizado mediante la Resolución de Intendencia N° 034-005-0008776/SUNAT</p>
		<h4 class="label_gracias" style="font-family: verdana; font-style: italic; text-align: center; margin-top: 10px;">¡Gracias por su compra!</h4>
	</div>

</div>

</body>
</html>