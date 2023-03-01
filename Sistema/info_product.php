<?php
	//Comprobar sesión activa
	session_start();
	include "../conexion.php";
	
	if(empty($_REQUEST['id'])){
		header("location: list_products.php");
	}else{
		$id_producto = $_REQUEST['id'];
		$query_product = mysqli_query($conexion, "SELECT p.codproducto, p.descripcion, p.caracteristicas,p.existencia, p.sku, p.precio, p.precio_referencial,u.idubicacion, u.ubicacion, c.idcategoria, c.categoria, pr.codproveedor, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion INNER JOIN categoria c ON p.categoria = c.idcategoria WHERE p.codproducto = $id_producto");
		$result_producto = mysqli_num_rows($query_product);
		$foto = '';
		$data = mysqli_fetch_array($query_product);
		}
			
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<title>Información de Producto</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Register Form Start--->
	<section id="container">
		<h1><i class="fa fa-circle-info"></i> Información de Productos</h1>
		<a href="javascript: history.go(-1)" class="btn-new-user">Volver <i class="fa fa-reply"></i></a>
			
		<div class="div-info">
		<div class="div-img">
			<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
			  <div class="carousel-inner">
			 
				<?php 
				if($result_producto != 0){
					$codproducto = $data['codproducto'];
					$query_foto = mysqli_query($conexion, "SELECT idproducto,foto,idfoto FROM foto WHERE idproducto = $codproducto");
					$x=1;
					while($data_foto = mysqli_fetch_array($query_foto)){
						
						if($data_foto['foto'] != 'img_producto.png'){
						$foto = 'img/uploads/'.$data_foto['foto'];
						}
						else{
						$foto = 'img/'.$data_foto['foto'];
						}
						if($x!=1){
						?>	
						<div class="carousel-item">
					      <img src="<?php echo $foto; ?>" class="" alt="<?php echo $data['descripcion'];?>">
					    </div>	
						<?php }else{ ?>
								<div class="carousel-item active">
							      <img class="" src="<?php echo $foto; ?>" alt="<?php echo $data['descripcion'];?>">
							    </div>
						<?php } 
						$x=$x+1;
					}
				}
					
				?>
			 </div>
				  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
				    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
				    <span class="visually-hidden">Previous</span>
				  </button>
				  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
				    <span class="carousel-control-next-icon" aria-hidden="true"></span>
				    <span class="visually-hidden">Next</span>
				  </button>
			</div>
			<div class="div-info-esp">
				<label class="info">Especificaciones:</label>
				<h2><?php echo $data['caracteristicas']; ?></h2>
			</div>
		</div>
		<div class="div-info-desc">
			<label class="info">Producto:</label>
			<h2><?php echo $data['descripcion']; ?></h2>
			<label class="info">Existencia:</label>
			<h2><?php echo $data['existencia']; ?></h2>
			<label class="info">Ubicacion:</label>
			<h2><?php echo $data['ubicacion']; ?></h2>
			<label class="info">Categoria:</label>
			<h2><?php echo $data['categoria']; ?></h2>
			<label class="info">Proveedor:</label>
			<h2><?php echo $data['proveedor']; ?></h2>
			<label class="info">Precio:</label>
			<h2><?php echo $data['precio']; ?></h2>
			<label class="info">Precio Referencial:</label>
			<h2><?php echo $data['precio_referencial']; ?></h2>
		</div>
		</div>
	</section>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

</body>
</html>