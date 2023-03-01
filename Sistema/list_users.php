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
	<title>Lista de Usuarios</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1>Lista de Usuarios</h1>
		<a href="register.php" class="btn-new-user">Crear Usuario</a>
		<table class="table-list display">
		<thead>	
		<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Correo</th>
				<th>Usuario</th>
				<th>Rol</th>
				<th>Acciones</th>
		</thead>	
		</tr>
			<?php 
				
				$query = mysqli_query($conexion,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol ORDER BY u.idusuario ASC");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result > 0){
					while ($data = mysqli_fetch_array($query)) {
				?>
					<tr class="row<?php echo $data['idusuario']; ?>">
						<td><?php echo $data['idusuario']; ?></td>
						<td><?php echo $data['nombre']; ?></td>
						<td><?php echo $data['correo']; ?></td>
						<td><?php echo $data['usuario']; ?></td>
						<td><?php echo $data['rol']; ?></td>
						<td>
					<?php if($_SESSION['rol'] != 2){

					?>
						<a href="update_user.php?id=<?php echo $data['idusuario']; ?>"class="link-edit"><button class="btn-view" type="button"><i class="fa fa-arrows-rotate" style="font-size:18px;"></i></button></a>
						<?php if($data["idusuario"]!=1){?>
						<button class="btn-anular del_user" type="button" user="<?php echo $data['idusuario']; ?>"><i class="fa fa-trash" style="font-size:18px;"></i></button>
					<?php }
				}?>
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