<?php
	include "../conexion.php";
	include "sendXml.php";

	
	$nofactura = 120;
	$docCliente = '73946653';
	$cliente = 'Alvaro Vilca';
	$nombre_comercial = 'JG MOTOS';
	$razon_social = 'JG MOTOS SOCIEDAD ANONIMA CERRADA';
	$ruc = '20600566289';
	$forma_pago = 'contado';
	$montoImpuestos = '46.75';
	$totalSinImpuestos = '259.75';
	$total = '306.5';
	$correlativo = '6';
	$cuotas = 0;
	$descuento = 15;
	sendXML($docCliente,$cliente,$ruc,$razon_social,$nombre_comercial,$forma_pago,$cuotas,$montoImpuestos,$totalSinImpuestos,$descuento,$total,$correlativo,$nofactura,$conexion);

?>