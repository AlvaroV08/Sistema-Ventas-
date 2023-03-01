<?php
	//Comprobar sesión activa
	session_start();
	if($_SESSION['rol'] == 2){
		header("location: ./");
	}
	include "../conexion.php";
	

	if(!empty($_POST)){
		$alert='';
		if(empty($_POST['marca']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['ubicacion'])|| empty($_POST['sku'])|| empty($_POST['categoria'])){
			$alert='<p class "error"> Todos los campos son obligatorios.</p>';
		}else{
			$proveedor = $_POST['marca'];
			$producto = $_POST['producto'];
			if(!empty($_POST['caracteristica'])){
			$caracteristica = $_POST['caracteristica'];
		}else{
			$caracteristica = '';
			}
			$precio = $_POST['precio'];
			$precio_comp = $_POST['preciocomp'];
			$precio_ref = $_POST['precioref'];
			$cantidad = $_POST['cantidad'];
			$usuario_id = $_SESSION['idUser'];
			$sku = $_POST['sku'];
			$ubicacion = $_POST['ubicacion'];
			$categoria= $_POST['categoria'];
			$foto = $_FILES['foto'];
			$nombre_foto = $foto['name'];
			$type = $foto['type'];
			$url_temp = $foto['tmp_name'];

			$img_producto = 'img_producto.png';

			if($nombre_foto != ''){
				$destino = 'img/uploads/';
				$img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
				$img_producto = $img_nombre.'.jpg';
				$src = $destino.$img_producto;
			}
			$result_sku = 0;
			$query_sku = mysqli_query($conexion,"SELECT * FROM producto where sku = '$sku'");
			
			$result_sku = mysqli_fetch_array($query_sku);

			if($result_sku>0){
				$alert ='<p class="error">El SKU ya existe.</p>';
			}else{
			$query_idfoto = mysqli_query($conexion, "SELECT MAX(codproducto) FROM producto");
			$arrayfoto = mysqli_fetch_array($query_idfoto);
			$idfoto = $arrayfoto['MAX(codproducto)']+1;
			$query_insert = mysqli_query($conexion,"INSERT INTO producto (proveedor,descripcion,caracteristicas,sku,precio,precio_compra,precio_referencial,existencia,usuario_id,ubicacion,categoria,foto) VALUES ('$proveedor','$producto','$caracteristica','$sku','$precio','$precio_comp','$precio_ref','$cantidad','$usuario_id','$ubicacion','$categoria','$img_producto')");
			$query_insertfoto = mysqli_query($conexion,"INSERT INTO foto (foto,idproducto) VALUES ('$img_producto', '$idfoto')");

				if($query_insert){
					if($nombre_foto != ''){
						move_uploaded_file($url_temp,$src);
					}
					$alert= '<p class="saved">Producto guardado correctamente.</p>';
					}else{
					$alert='<p class="error">El producto no se ha podido ingresar</p>';			
					}
				}
			}
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<title>Registro de Producto</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Register Form Start--->
	<section id="container">
		<div class="div-update"><h1>Nuevo Producto</h1>
			<hr>
			<form class="form-update"action="#" method="post" enctype="multipart/form-data">

				<label for="nombre">Proveedor</label>
				<?php
					$query_proveedor = mysqli_query($conexion,"SELECT codproveedor, proveedor FROM proveedor WHERE codproveedor != 0 ORDER BY proveedor ASC");
					$result = mysqli_num_rows($query_proveedor);
					
				?>
				<select name="marca" id="proveedor">
				<?php 
					if($result >0){
						while($proveedor = mysqli_fetch_array($query_proveedor)){
							?>
						<option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor'];?></option><?php
						}
					} ?>
				</select>
				<label for="producto">Producto</label>
				<input type="text" name="producto" id="producto" placeholder="Nombre del producto">
				<label for="producto">Características</label>
				<textarea name="caracteristica" id="caracteristica" placeholder="Detalle del producto" style="width:100%; height:90px;"></textarea>
				<label for="producto">SKU</label>
				<input type="text" name="sku" id="sku" placeholder="000000">
				<label for="precio">Precio de Venta</label>
				<input type="number" name="precio" step="0.10" id="precio" placeholder="100.00" min="0">
				<label for="preciocomp">Precio Compra</label>
				<input type="number" name="preciocomp" step="0.10" id="preciocomp" placeholder="100.00" min="0">
				<label for="precioref">Precio Referencial</label>
				<input type="number" name="precioref" step="0.10" id="precioref" placeholder="100.00" min="0">
				<label for="cantidad">Cantidad</label> 
				<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" min="0">
				<label for="nombre">Ubicacion</label>
				<?php
					$query_ubicacion = mysqli_query($conexion,"SELECT * FROM ubicacion WHERE idubicacion != 0 ORDER BY ubicacion ASC");
					$result_ubi = mysqli_num_rows($query_ubicacion);
				?>
				<select name="ubicacion" id="ubicacion">
				<?php 
					if($result_ubi >0){
						while($ubicacion = mysqli_fetch_array($query_ubicacion)){
							?>
						<option value="<?php echo $ubicacion['idubicacion']; ?>"><?php echo $ubicacion['ubicacion'];?></option><?php
						}
					} ?>
				</select>
				<label for="categoria">Categoria</label>
				<?php
					$query_categoria = mysqli_query($conexion,"SELECT * FROM categoria WHERE idcategoria != 0 ORDER BY categoria ASC");
					$result_cat = mysqli_num_rows($query_categoria);
					mysqli_close($conexion);
				?>
				<select name="categoria" id="categoria">
				<?php 
					if($result_cat >0){
						while($categoria = mysqli_fetch_array($query_categoria)){
							?>
						<option value="<?php echo $categoria['idcategoria']; ?>"><?php echo $categoria['categoria'];?></option><?php
						}
					} ?>
				</select>
				<div class="photo">
					<label for="foto">Foto</label>
				        <div class="prevPhoto">
				        <span class="delPhoto notBlock">X</span>
				        <label for="foto"></label>
				        </div>
				        <div class="upimg">
				        <input type="file" name="foto" id="foto">
				        </div>
				        <div id="form_alert"></div>
				</div>
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-product" value="Guardar Producto"> 
			</form>
		</div>
	</section>
</body>
</html>