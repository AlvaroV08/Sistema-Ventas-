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
	<title>Lista de Clientes</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1>Lista de Clientes</h1>
		<a href="register_cliente.php" class="btn-new-user">Nuevo Cliente</a>
		<table class="table-list display">
			<thead>
			<tr>
				<th>ID</th>
				<th>DNI/R.U.C.</th>
				<th>Nombres/Razon Social</th>
				<th>Telefono</th>
				<th>Acciones</th>
			</tr>
			</thead>
			<?php 
				$query = mysqli_query($conexion,"SELECT * FROM cliente  ORDER BY idcliente ASC");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result > 0){
					while ($data = mysqli_fetch_array($query)) {
				?>
					<tr class="row<?php echo $data['idcliente']; ?>">
						<td><?php echo $data['idcliente']; ?></td>
						<td>
						<?php 
						if(empty($data['dni'])){
							echo $data['ruc']; 
						}
						if(empty($data['ruc'])){
							echo $data['dni']; 
						}
						?>
						</td>
						<td>
						<?php 
						if(!empty($data['ruc'])){
							echo $data['razon_social']; 
						}else{
							echo $data['nombres'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'];
						}
						?>
						</td>
						</td>
						<td><?php echo $data['telefono']; ?></td>
						<td>
						<a href="update_cliente.php?id=<?php echo $data['idcliente']; ?>"class="link-edit"><button class="btn-view" type="button"><i class="fa fa-arrows-rotate" style="font-size:18px;"></i></button></a>
						<button class="btn-anular del_cliente" type="button" cliente="<?php echo $data['idcliente']; ?>"><i class="fa fa-trash" style="font-size:18px;"></i></button>
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