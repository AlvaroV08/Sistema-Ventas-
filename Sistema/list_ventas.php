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
	
	<title>Lista de Ventas</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1><i class="fa fa-newspaper"></i> Lista de Ventas</h1>
		<a href="register_venta.php" class="btn-new-user">Crear Venta</a>

		<div>
			<h5>Buscar por fecha</h5>
			<form action="search_ventas.php" method="get" class="form-search-date">
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
				<th>Estado</th>
				<th class="textright">Total Factura</th>
				<th class="textright">Acciones</th>
				
			</tr>
	</thead>
			<?php 
				$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, cl.razon_social,cl.nombres,cl.apellido_paterno,cl.apellido_materno, u.nombre  as vendedor, cl.nombres  as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus != 3 AND f.estatus !=4 ORDER BY f.fecha DESC");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result > 0){
					while ($data = mysqli_fetch_array($query)) {
						if($data['estatus']==1){
							$estado = '<span class="pagada">Pagada</span>';
						}else{
							$estado = '<span class="anulada">Anulada</span>';

						}
				?>
				<tr id="row_<?php echo $data['nofactura']; ?>">
					<td><?php echo $data['nofactura']; ?></td>
					<td><?php echo $data['fecha']; ?></td>
					<td ><?php
					if(empty($data['nombres'])){
						echo $data['razon_social']; 
					}
					if(empty($data_cliente['razon_social'])){
						echo $data['nombres'].' '.$data['apellido_paterno']; 
					}
					?></td>
					<td><?php echo $data['vendedor']; ?></td>
					<td class="estado"><?php echo $estado; ?></td>
					<td class="textright totalfactura"><span>S/</span><?php echo $data['totalfactura']; ?></td>
					
					<td>
					
						<div class="div-acciones">
							<div>
							<button class="btn-view view-factura" type="button" cl="<?php echo $data["codcliente"]; ?>" f ="<?php echo $data["nofactura"]; ?>"><i class="fa fa-eye" style="font-size:18px;"></i></button>
							</div>
							<div>
							<a href="view_ubicacion.php?cl=<?php echo $data['codcliente']."&f=".$data['nofactura']; ?>"><button class="btn-factura" type="button"><i class="fa fa-location-dot" style="font-size: 18px;"></i></button></a>
							</div>
						<?php if($data["estatus"]==1){?>
						<div class="div-factura">
							<button class="btn-anular anular-factura" fac="<?php echo $data['nofactura'];?>"><i class="fa fa-ban" style="font-size:18px;"></i></button>
						
					<?php } else{ ?>
						<div class="div-factura">
							<button class="btn-anular inactivo"><i class="fa fa-ban" style="font-size:18px;"></i></button>
						</div>
					<?php } ?>
						</div>
					</td>
				</tr>
			<?php
				}
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