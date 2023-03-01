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
	
	<title>Entrada de Productos</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1><i class="fa fa-cubes"></i> Entrada de Productos</h1>
		<table class="table-list display">
			<thead>
			<tr>
				<th>Fecha de entrada</th>
				<th>Sku</th>
				<th>Descripcion</th>
				<th>Cantidad</th>
				<th>Precio de Compra</th>
				<th>Foto</th>
				<th>Usuario</th>
				</tr>
				</thead>
			<?php 
				$query = mysqli_query($conexion,"SELECT e.codproducto,p.sku, p.descripcion,e.cantidad,e.precio,e.fecha,u.nombre FROM entradas e INNER JOIN producto p ON e.codproducto = p.codproducto INNER JOIN usuario u ON u.idusuario = e.usuario_id ORDER BY e.codproducto ASC");
				$result = mysqli_num_rows($query);

				if($result > 0){
					while ($data = mysqli_fetch_array($query)) {
						$codproducto = $data['codproducto'];
						$query_foto = mysqli_query($conexion, "SELECT idproducto,foto,idfoto FROM foto WHERE idproducto = $codproducto");
						$data_foto = mysqli_fetch_array($query_foto);
						if($data_foto['foto'] != 'img_producto.png'){
							$foto = 'img/uploads/'.$data_foto['foto'];
						}else{
							$foto = 'img/'.$data_foto['foto'];
						}
				?>
				<tr class="row<?php echo $data['codproducto']; ?>">
					<td><?php echo $data['fecha']; ?></td>
					<td><?php echo $data['sku']; ?></td>
					<td><?php echo $data['descripcion']; ?></td>
					<td><?php echo $data['cantidad']; ?></td>
					<td class="celPrecio"><?php echo $data['precio']; ?></td>
					<td class="img-producto"><a href="info_product.php?id=<?php echo $data['codproducto']; ?>"><img src="<?php echo $foto;?>" alt="<?php echo $data['descripcion'];?>"style="width: 60px; height: auto; margin:auto;"></a></td>
					<td><?php echo $data['nombre']; ?></td> 
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