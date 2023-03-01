<?php
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../../conexion.php";
	include "../../sistema/config.php";
	require_once $ruta_vendor.'/autoload.php';
	use Dompdf\Dompdf;
	use Dompdf\Dompdf\Option;
	use Dompdf\Dompdf\Exception as DOMException;
	use Dompdf\Dompdf\Options;

	if(empty($_REQUEST['cl']) || empty($_REQUEST['p']))
	{
		echo "No es posible generar la proforma.";
	}else{
		
		$codCliente = $_REQUEST['cl'];
		$noProforma = $_REQUEST['p'];
		$anulada = '';
		$descuentos = 0;
		$opInafectas = 0;
		$opExoneradas = 0;
		$query_config   = mysqli_query($conexion,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}


		$query = mysqli_query($conexion,"SELECT p.noproforma, DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(p.fecha,'%H:%i:%s') as  hora, p.codcliente, p.estatus,
												v.nombre as vendedor,
												cl.dni, cl.nombres, cl.direccion, cl.apellido_paterno, cl.apellido_materno, cl.ruc, cl.razon_social
											FROM proforma p
											INNER JOIN usuario v
											ON p.usuario = v.idusuario
											INNER JOIN cliente cl
											ON p.codcliente = cl.idcliente
											WHERE p.noproforma = $noProforma AND p.codcliente = $codCliente  AND p.estatus != 10 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$proforma = mysqli_fetch_assoc($query);
			$no_proforma = $proforma['noproforma'];

			if($proforma['estatus'] == 2){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conexion,"SELECT p.sku, p.codproducto, p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM proforma f
														INNER JOIN detalleproforma dt
														ON f.noproforma = dt.noproforma
														INNER JOIN producto p
														ON dt.codproducto = p.codproducto
														WHERE f.noproforma = $no_proforma ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
			include(dirname('__FILE__').'/proforma.php');
		    $html = ob_get_clean();
		    $imageURL = 'img/logo-tr.png';
			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('proforma'.$noProforma.'.pdf',array('Attachment'=>false));
			exit;
		}
	}

?>