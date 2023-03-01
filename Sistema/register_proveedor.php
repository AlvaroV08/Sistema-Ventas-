<?php
	//Comprobar sesión activa
	session_start();
	
	include "../conexion.php";
	

	if(!empty($_POST)){
		$alert='';
		if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono'])){
			$alert='<p class "error"> Todos los campos son obligatorios.</p>';
		}else{
			$proveedor = $_POST['proveedor'];
			$contacto = $_POST['contacto'];
			$telefono = $_POST['telefono'];
			$direccion = $_POST['direccion'];

			$result = 0;
			$query = mysqli_query($conexion,"SELECT * FROM proveedor where proveedor = '$proveedor'");
			
			$result = mysqli_fetch_array($query);

			if($result>0){
				$alert ='<p class="error">El nombre del proveedor ya se encuentra registrado.</p>';
			}else{

				$query_insert = mysqli_query($conexion,"INSERT INTO proveedor(proveedor,contacto,telefono,direccion) VALUES ('$proveedor','$contacto','$telefono','$direccion')");
					if($query_insert){
						$alert= '<p class="saved">Proveedor guardado correctamente.</p>';
					}else{
						$alert='<p class="error">El Proveedor no se ha podido guardar</p>';
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
	<title>Registro de Proveedor</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Register Form Start--->
	<section id="container">
		<div class="div-new"><h1>Nuevo Proveedor</h1>
			<hr>
			<form class="form-new"action="#" method="post">
				<label for="proveedor">Nombre de Proveedor</label>
				<input type="text" name="proveedor" id="proveedor" placeholder="Empresa">
				<label for="contacto">Contacto</label>
				<input type="text" name="contacto" id="contacto" placeholder="Nombre del contacto">
				<label for="telefono">Telefono</label>
				<input type="number" name="telefono" id="telefono" placeholder="901234567">
				<label for="direccion">Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion (opcional)">
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-proveedor" value="Guardar Proveedor"> 
			</form>
		</div>
	</section>
</body>
</html>