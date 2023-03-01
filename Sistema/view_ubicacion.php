<?php
	//Comprobar sesión activa
	session_start();		
	
	include "../conexion.php";
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	
	<title>Ubicacion de Venta</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1><i class="fa fa-location-dot"></i> Ubicacion de Venta </h1>
		<a href="list_ventas.php" class="btn-new-user">Volver <i class="fa fa-reply"></i></a>
		<div class="ubic">
			<div class="bodyUbi">
				
			</div>
		</div>
	</section>
	<script>
    /* Initialization of datatables */
    $(document).ready(function () {
      $('table.display').DataTable();
    });
</script>
	<?php
  if(!empty($_REQUEST['cl']) && !empty($_REQUEST['f'])){
    $codCliente = $_REQUEST['cl'];
    $noFactura = $_REQUEST['f'];
    ?>
    <script type="text/javascript">
    $(document).ready(function(){
    var codcliente = '<?php echo $codCliente; ?>';
    var nofactura = '<?php echo $noFactura; ?>';
    viewUbicacion(codcliente,nofactura);
      });
    </script> 
    
  <?php }
  ?>
</body>
</html>