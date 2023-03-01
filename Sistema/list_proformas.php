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
	
	<title>Lista de Proformas</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1><i class="fa fa-paste"></i> Lista de Proformas</h1>
		<a href="register_venta.php" class="btn-new-user">Crear Proforma</a>

		<div>
			<h5>Buscar por fecha</h5>
			<form action="search_proformas.php" method="get" class="form-search-date">
				<label>De: </label>
				<input type="date" name="fecha_de" id="fecha_de" required>
				<label> A </label>
				<input type="date" name="fecha_a" id="fecha_a" required>
				<button type="submit" class="btn-view"><i class="fa fa-search"></i></button>
			</form>
		</div>

		<table class="table-list display">
		<thead>	
		<tr>
				<th>N°</th>
				<th>Fecha</th>
				<th>Cliente </th>
				<th>Vendedor</th>
				<th class="textright">Total Proforma	</th>
				<th class="textright">Acciones</th>
				
			
		</tr>
		</thead>
			<?php 
				$query = mysqli_query($conexion,"SELECT p.noproforma, p.fecha, p.codcliente, p.totalproforma, p.estatus,cl.razon_social,cl.nombres, u.nombre as vendedor, cl.nombres as cliente FROM proforma p INNER JOIN cliente cl ON p.codcliente = cl.idcliente INNER JOIN usuario u ON p.usuario = u.idusuario ORDER BY p.fecha DESC");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result > 0){
					while ($data = mysqli_fetch_array($query)) {
						
				?>
				<tr id="row_<?php echo $data['noproforma'];?>">
					<td><?php echo $data['noproforma']; ?></td>
					<td><?php echo $data['fecha']; ?></td>
					<td ><?php
					if(empty($data['nombres'])){
						echo $data['razon_social']; 
					}
					if(empty($data_cliente['razon_social'])){
						echo $data['nombres']; 
					}
					?></td>
					<td><?php echo $data['vendedor']; ?></td>
					<td class="textright totalproforma"><span>S/</span><?php echo $data['totalproforma']; ?></td>
					
					<td>
						<div class="div-acciones">
							<div>
								<button class="btn-view view-proforma" type="button" cl="<?php echo $data["codcliente"]; ?>" f ="<?php echo $data["noproforma"]; ?>"><i class="fa fa-eye" style="font-size: 18px;"></i></button>
							</div>
							<div>
								<?php if($data["estatus"]==1){?>
						<div class="div-factura">
							<a href="register_venta.php?cl=<?php echo $data['codcliente']."&f=".$data['noproforma']; ?>"><button class="btn-factura" type="button"><i class="fa fa-pencil" style="font-size: 18px;"></i></button></a>
					<?php } else{ ?>
								<button class="btn-factura inactivo" type="button"><i class="fa fa-pencil" style="font-size: 18px;"></i></button>
							</div>
					<?php }
				}?>
						</div>
					</td>
				</tr>
			<?php
				}
			?>
		</table>
	</section>
	<script>
		/* Initialization of datatables */
		$(document).ready(function () {
			$('table.display').DataTable();
		});
</script>
</body>
</html>