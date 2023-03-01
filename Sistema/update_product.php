<?php
	//Comprobar sesión activa
	session_start();
	if($_SESSION['rol'] == 2){
		header("location: ./");
	}
	include "../conexion.php";
	

	if(!empty($_POST)){
		$alert='';
		if(empty($_POST['marca']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id'])|| empty($_POST['foto_actual']) || empty($_POST['foto_remove']) || empty($_POST['ubicacion']) || empty($_POST['categoria']) || empty($_POST['sku']) || empty($_POST['precioref']) || empty($_POST['preciocomp'])){
			$alert='<p class "error"> Todos los campos son obligatorios.</p>';
		}else{
			$codproducto = $_POST['id'];
			$proveedor = $_POST['marca'];
			$producto = $_POST['producto'];
			if(!empty($_POST['caracteristica'])){
				$caracteristica = $_POST['caracteristica'];
			}else{
				$caracteristica = '';
				}
			$precio = $_POST['precio'];
			$precio_ref = $_POST['precioref'];
			$precio_comp = $_POST['preciocomp'];
			$sku = $_POST['sku'];
			$ubicacion = $_POST['ubicacion'];
			$categoria = $_POST['categoria'];
			$imgProducto = $_POST['foto_actual'];
			$imgRemove = $_POST['foto_remove'];
			$usuario_id = $_SESSION['idUser'];
			$foto = $_FILES['foto'];
			$nombre_foto = $foto['name'];
			$type = $foto['type'];
			$url_temp = $foto['tmp_name'];

			$foto2 = $_FILES['foto2'];
			$nombre_foto2 = $foto2['name'];
			$type2 = $foto2['type'];
			$url_temp2 = $foto2['tmp_name'];

			$img_producto2 = 'img_producto.png';

			if($nombre_foto2 != ''){
				$destino2 = 'img/uploads/';
				$img_nombre2 = 'img_'.md5(date('d-m-Y H:m:s').'2');
				$img_producto2 = $img_nombre2.'.jpg';
				$src2 = $destino2.$img_producto2;
			}
			
			$upd = '';
			if($nombre_foto != ''){
				$destino = 'img/uploads/';
				$img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
				$imgProducto = $img_nombre.'.jpg';
				$src = $destino.$imgProducto;
			}else{
				if($_POST['foto_actual'] != $_POST['foto_remove']){
					$imgProducto = 'img_producto.png';
				}
			}
			$result_sku = 0;
			$query_sku = mysqli_query($conexion,"SELECT * FROM producto WHERE (sku = '$sku' AND codproducto != '$codproducto')");
			$result_sku = mysqli_fetch_array($query_sku);

			if($result_sku>0){
				$alert ='<p class="error">El SKU ya existe.</p>';
			}else{
			$query_update = mysqli_query($conexion,"UPDATE producto SET descripcion = '$producto', caracteristicas = '$caracteristica' , proveedor = $proveedor,sku = '$sku', ubicacion = '$ubicacion', categoria = '$categoria', precio = $precio, precio_compra = '$precio_comp', precio_referencial = '$precio_ref', foto = '$codproducto' WHERE codproducto = $codproducto");

			$query_idfoto = mysqli_query($conexion, "SELECT MIN(idfoto) FROM foto WHERE idproducto = $codproducto");
			$arrayfoto = mysqli_fetch_array($query_idfoto);
			$idfoto = $arrayfoto['MIN(idfoto)'];
			$query_updatefoto = mysqli_query($conexion,"UPDATE foto SET foto = '$imgProducto' WHERE idfoto = '$idfoto'");
			if($img_producto2 != 'img_producto.png'){
				$query_insertfoto = mysqli_query($conexion,"INSERT INTO foto (foto,idproducto) VALUES ('$img_producto2', '$codproducto')");
				if($nombre_foto2 != ''){
						move_uploaded_file($url_temp2,$src2);
					}else{
						$alert='<p class="error">Error el producto no se ha podido actualizar</p>';
					}	
			}
				if($query_update){
					if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])){
							unlink('img/uploads/'.$_POST['foto_actual']);
					}

					if($nombre_foto != ''){
						move_uploaded_file($url_temp,$src);
					}
					$alert= '<p class="saved">Producto actualizado correctamente.</p>';
					}else{
					$alert='<p class="error">Error el producto no se ha podido actualizar</p>';
					}		
				
					
			}
		}
	}
	if(empty($_REQUEST['id'])){
		header("location: list_products.php");
	}else{
		$id_producto = $_REQUEST['id'];
		$query_product = mysqli_query($conexion, "SELECT p.codproducto, p.descripcion, p.caracteristicas, p.sku, p.precio,p.precio_compra,p.precio_referencial, u.idubicacion, u.ubicacion, c.idcategoria, c.categoria, pr.codproveedor, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion INNER JOIN categoria c ON p.categoria = c.idcategoria WHERE p.codproducto = $id_producto");
		$result_producto = mysqli_num_rows($query_product);
		$foto = '';
		$classRemove = 'notBlock';
		if($result_producto >0){
			$data_producto = mysqli_fetch_assoc($query_product);
			$codproducto = $data_producto['codproducto'];
			$query_foto = mysqli_query($conexion, "SELECT * FROM foto WHERE idproducto = '$codproducto'");
			$data_foto = mysqli_fetch_array($query_foto);
			if($data_foto['foto'] != 'img_producto.png'){
				$classRemove = '';
				$foto = '<img id="img" src="img/uploads/'.$data_foto['foto'].'"alt=Producto">';
			}
			
		}else{
			header("location: list_products.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<title>Actualizar Productos</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Register Form Start--->
	<section id="container">
		<div class="div-update"><h1>Actualizar Producto</h1> 
			<input class="inp-Photo2" type="number" name="num_foto" value="2" step="1" min="2">
			<button class="del-Photo2 btn-anular">Quitar Foto #</button>
			<hr>
			<form class="form-update" action="#" method="post" enctype="multipart/form-data">
			<input type="hidden" id="id" name="id" value="<?php echo $data_producto['codproducto']; ?>">
			<input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_foto['foto']; ?>">
			<input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_foto['foto']; ?>">
				<label for="nombre">Proveedor</label>
				<?php
					$query_proveedor = mysqli_query($conexion,"SELECT codproveedor, proveedor FROM proveedor WHERE codproveedor != 0 ORDER BY proveedor ASC");
					$result = mysqli_num_rows($query_proveedor);

				?>
				<select name="marca" id="proveedor" class="notItemOne">
					<option value="<?php echo $data_producto['codproveedor']; ?>"selected><?php echo $data_producto['proveedor']; ?></option>
				<?php 
					if($result >0){
						while($proveedor = mysqli_fetch_array($query_proveedor)){
							?>
						<option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor'];?></option><?php
						}
					} ?>
				</select>
				<label for="producto">Producto</label>
				<input type="text" name="producto" id="producto" placeholder="Nombre del producto" value="<?php echo $data_producto['descripcion']; ?>">
				<label for="producto">Características</label>
				<textarea name="caracteristica" id="caracteristica" placeholder="Detalle del producto" style="width:100%; height:90px;"><?php echo $data_producto['caracteristicas']; ?></textarea>
				<label for="producto">SKU</label>
				<input type="text" name="sku" id="sku" placeholder="000000" value="<?php echo $data_producto['sku']; ?>">
				<label for="precio">Precio de Venta</label>
				<input type="number" name="precio" step="0.01" id="precio" placeholder="100.00" value="<?php echo $data_producto['precio']; ?>">
				<label for="preciocomp">Precio Compra</label>
				<input type="number" name="preciocomp" step="0.10" id="preciocomp" placeholder="100.00" value="<?php echo $data_producto['precio_compra']; ?>" min="0">
				<label for="precioref">Precio Referencial</label>
				<input type="number" name="precioref" step="0.10" id="precioref" placeholder="100.00" value="<?php echo $data_producto['precio_referencial']; ?>" min="0">
				<label for="nombre">Ubicacion</label>
				<?php
					$query_ubicacion = mysqli_query($conexion,"SELECT * FROM ubicacion");
					$result_ubi = mysqli_num_rows($query_ubicacion);
				?>
				<select name="ubicacion" id="ubicacion" class="notItemOne">
					<option value="<?php echo $data_producto['idubicacion']; ?>"selected><?php echo $data_producto['ubicacion']; ?></option>
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
					$query_categoria = mysqli_query($conexion,"SELECT * FROM categoria");
					$result_cat = mysqli_num_rows($query_categoria);
				?>
				<select name="categoria" id="categoria" class="notItemOne">
					<option value="<?php echo $data_producto['idcategoria']; ?>"selected><?php echo $data_producto['categoria'];?></option>
				<?php 
					if($result_cat >0){
						while($categoria = mysqli_fetch_array($query_categoria)){
							?>
						<option value="<?php echo $categoria['idcategoria']; ?>"><?php echo $categoria['categoria'];?></option><?php
						}
					} ?>
				</select>
				<?php

					$query_adic = mysqli_query($conexion, "SELECT * FROM foto WHERE idproducto = $codproducto");
					$result_adic = mysqli_num_rows($query_adic);
					$adicional = $result_adic+1;
				?>
				<div class="photo">
					<label for="foto">Foto</label>
				        <div class="prevPhoto">
				        <span class="delPhoto <?php echo $classRemove; ?>">X</span>
				        <label for="foto"></label>
				        <?php echo $foto; ?> 
				        </div>
				        <div class="upimg">
				        <input type="file" name="foto" id="foto">
				        </div>
				        <label>Foto #<?php echo $adicional; ?></label>
						<input type="file" name="foto2" id="foto2">
				        <div id="form_alert"></div>
				</div>
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-product" value="Actualizar Producto"> 
			</form>
		</div>
		
			
	</section>
</body>
</html>