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
	<title>Lista de Proveedores</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1>Lista de Proveedores</h1>
		<a href="register_proveedor.php" class="btn-new-user">Nuevo Proveedor</a>
		<table class="table-list display">
			<thead>
			<tr>
				<th>ID</th>
				<th>Proveedor</th>
				<th>Contacto</th>
				<th>Telefono</th>
				<th>Dirección</th>
				<th>Acciones</th>
			</tr>
			</thead>
			<?php 
				$query = mysqli_query($conexion,"SELECT * FROM proveedor  ORDER BY codproveedor ASC");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result > 0){
					while ($data = mysqli_fetch_array($query)) {
				?>
					<tr class="row<?php echo $data['codproveedor']; ?>">
						<td><?php echo $data['codproveedor']; ?></td>
						<td><?php echo $data['proveedor']; ?></td>
						<td><?php echo $data['contacto']; ?></td>
						<td><?php echo $data['telefono']; ?></td>
						<td><?php echo $data['direccion']; ?></td>
						<td>
						<a href="update_proveedor.php?id=<?php echo $data['codproveedor']; ?>"class="link-edit"><button class="btn-view" type="button"><i class="fa fa-arrows-rotate" style="font-size:18px;"></i></button></a>
						<button class="btn-anular del_proveedor" type="button" proveedor="<?php echo $data['codproveedor']; ?>"><i class="fa fa-trash" style="font-size:18px;"></i></button>
						</td>
					</tr>
					
				<?php } 

			}?>
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