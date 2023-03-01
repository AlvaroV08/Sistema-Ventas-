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
	
	<title>Lista de Productos</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<h1><i class="fa fa-cubes"></i> Lista de Productos</h1>
		<a href="register_product.php" class="btn-new-user">Crear Producto</a>
		<table class="table-list display">
			<thead>
			<tr>
				<th>Sku</th>
				<th>Descripción</th>
				<th>Precio</th>
				<th>Ubicacion</th>
				<th>Existencia</th>
				<th>Proveedor</th>
				<th>Foto</th>
				<th>Acciones</th>
				</tr>
				</thead>
			<?php 
				/*$sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM producto");
				$result_register = mysqli_fetch_array($sql_register);
				$total_register = $result_register['total_registro'];
				$por_pagina = 100;

				if(empty($_GET['pagina'])){
					$pagina = 1;
				}else{
					$pagina = $_GET['pagina'];
				}
				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_register/$por_pagina);*/

				$query = mysqli_query($conexion,"SELECT p.codproducto,p.sku,u.ubicacion, p.descripcion, p.precio, p.precio_referencial,p.existencia, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE p.estatus = 1 ORDER BY p.codproducto ASC");
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
					<td><?php echo $data['sku']; ?></td>
					<td><?php echo $data['descripcion']; ?></td>
					<td class="celPrecio"><?php echo $data['precio_referencial']; ?></td>
					<td><?php echo $data['ubicacion']; ?></td>
					<?php if($data['existencia']<=3){?>
						<td class="celExistencia" style="color:red; font-weight: bold;"><?php echo $data['existencia']; ?></td>
					<?php
					}else{?>
					<td class="celExistencia"><?php echo $data['existencia']; ?></td> <?php } ?>
					<td><?php echo $data['proveedor']; ?></td>
					<td class="img-producto"><a href="info_product.php?id=<?php echo $data['codproducto']; ?>"><img src="<?php echo $foto;?>" alt="<?php echo $data['descripcion'];?>"style="width: 60px; height: auto; margin:auto;"></a></td>
					<td>
					<?php if($_SESSION["rol"]!=2){?>
					<button class="btn-view add_product" type="button" product="<?php echo $data['codproducto']; ?>"><i class="fa fa-plus" style="font-size:18px;"></i></button>
					<a href="update_product.php?id=<?php echo $data['codproducto']; ?>"><button class="btn-factura" type="button" style="font-size:18px;"><i class="fa fa-arrows-rotate"></i></button></a>
					<button class="btn-anular del_product" type="button" product="<?php echo $data['codproducto']; ?>" style="font-size:18px;" ><i class="fa fa-trash"></i></button>
					
					</i></a>
					</td>
					<?php }?>
					</tr>
			<?php
				}
			}
			?>
		</table>
		<!---<div class="paginador">
			<ul>
				<?php /*
					if($pagina !=1){

					?>
				<li><a href="?pagina=<?php echo 1;?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
				<?php
					}
					for ($i=1; $i <= $total_paginas ; $i++) { 
						// code...
						if($i == $pagina){
							echo '<li class="page-selected">'.$i.'</li>';
						}else{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a><li>';
					}
				}	
				if($pagina != $total_paginas){
					?>
				<li><a href="?pagina=<?php echo $pagina+1 ?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
				<?php 
			} 
			*/?>
			</ul>
		</div>--->
	</section>
	<script>
		/* Initialization of datatables */
		$(document).ready(function () {
			$('table.display').DataTable();
		});
</script>
</body>
</html>