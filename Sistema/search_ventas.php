<?php
	//Comprobar sesión activa
	session_start();		
	
	include "../conexion.php";

	$busqueda = '';
	$fecha_de = '';
	$fecha_a =  '';

	if(!empty($_REQUEST['busqueda'])){
		$busqueda = strtolower($_REQUEST['busqueda']);
		$where = "((f.nofactura = '$busqueda' AND f.estatus != 3) OR (cl.nombre LIKE '%$busqueda%' AND f.estatus != 3) OR (u.nombre LIKE '%$busqueda%' AND f.estatus != 3))";
		$buscar = "busqueda=".$busqueda;
	}

	if(!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])){
		$fecha_de = $_REQUEST['fecha_de'];
		$fecha_a = $_REQUEST['fecha_a'];

		$buscar = '';

		if($fecha_de > $fecha_a){
			header("location: list_ventas.php");
		}else if($fecha_de == $fecha_a){
			$where = "fecha LIKE '$fecha_de%'";
			$buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
		}else{
			$f_de = $fecha_de.' 00:00:00';
			$f_a = $fecha_a.' 23:59:59';
			$where = "fecha BETWEEN '$f_de' AND '$f_a'";
			$buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
		}
	}
	
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
				<input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>" required>
				<label> A </label>
				<input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>" required>
				<button type="submit" class="btn-view"><i class="fa fa-search"></i></button>
			</form>
		</div>

		<table class="table-list">
			<tr>
				<th>N°</th>
				<th>Fecha</th>
				<th>Cliente </th>
				<th>Vendedor</th>
				<th>Estado</th>
				<th class="textright">Total Factura</th>
				<th class="textright">Acciones</th>
				
			</tr>
			<?php 
				$sql_register = mysqli_query($conexion, "SELECT f.codcliente, f.usuario, f.nofactura, COUNT(*) AS total_registro, cl.nombres, u.nombre FROM factura f INNER JOIN cliente cl ON cl.idcliente = f.codcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where");
				$result_register = mysqli_fetch_array($sql_register);
				$total_register = $result_register['total_registro'];
				$por_pagina = 10;

				if(empty($_GET['pagina'])){
					$pagina = 1;
				}else{
					$pagina = $_GET['pagina'];
				}
				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_register/$por_pagina);

				$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, cl.razon_social,cl.nombres, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC LIMIT $desde,$por_pagina");
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
				<tr id="row_<?php echo $data['nofactura'];?>">
					<td><?php echo $data['nofactura']; ?></td>
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
					<td class="estado"><?php echo $estado; ?></td>
					<td class="textright totalfactura"><span>S/.</span><?php echo $data['totalfactura']; ?></td>
					
					<td>
						<div class="div-acciones">
							<div>
							<button class="btn-view view-factura" type="button" cl="<?php echo $data["codcliente"]; ?>" f ="<?php echo $data["nofactura"]; ?>"><i class="fa fa-eye" style="font-size:18px;"></i></button>
							</div>
							<div>
							<button class="btn-factura view-ubicacion" type="button" cl="<?php echo $data["codcliente"]; ?>" f ="<?php echo $data["nofactura"]; ?>"><i class="fa fa-location-dot" style="font-size:18px;"></i></button>
							</div>
						<?php if($data["estatus"]==1){?>
						<div class="div-factura">
							<button class="btn-anular anular-factura" fac="<?php echo $data['nofactura'];?>"><i class="fa fa-ban" style="font-size:18px;"></i></button>
						
					<?php } else{ ?>
						<div class="div-factura">
							<button class="btn-anular inactivo"><i class="fa fa-ban" style="font-size:18px;"></i></button>
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
		<div class="paginador">
			<ul>
				<?php
					if($pagina !=1){

					?>
				<li><a href="?pagina=<?php echo 1;?>&<?php echo $buscar ?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>&<?php echo $buscar ?>"><<</a></li>
				<?php
					}
					for ($i=1; $i <= $total_paginas ; $i++) { 
						// code...
						if($i == $pagina){
							echo '<li class="page-selected">'.$i.'</li>';
						}else{
						echo '<li><a href="?pagina='.$i.'&'.$buscar.'">'.$i.'</a><li>';
					}
				}	
				if($pagina != $total_paginas){
					?>
				<li><a href="?pagina=<?php echo $pagina+1 ?>&<?php echo $buscar ?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar ?>">>|</a></li>
				<?php 
			} 
			?>
			</ul>
		</div>
	</section>
</body>
</html>