<?php
	//Comprobar sesión activa
	session_start();
	include "../conexion.php";
	if(!empty($_POST)){
		$alert='';
		if(($_POST['documento'])=='DNI'){	
			if(empty($_POST['dni']) || empty($_POST['nombre']) || empty($_POST['apellido_paterno'])){
				$alert='<p class="error"> Complete los campos obligatorios.</p>';
			}else{
				$dni = $_POST['dni'];
				$nombre = $_POST['nombre'];
				if(!empty($_POST['apellido_paterno'])){
				$apellido_paterno = $_POST['apellido_paterno'];
				}else{
					$apellido_paterno = '';
				}
				if(!empty($_POST['apellido_materno'])){
				$apellido_materno = $_POST['apellido_materno'];
				}else{
					$apellido_materno ='';
				}
				if(!empty($_POST['telefono'])){
				$telefono = $_POST['telefono'];
				}else{
					$telefono ='';
				}
				$usuario_id = $_SESSION['idUser'];

				$result = 0;
				$query = mysqli_query($conexion,"SELECT * FROM cliente where dni = '$dni'");
				
				$result = mysqli_fetch_array($query);

				if($result>0){
					$alert ='<p class="error">El número de Documento de identidad ya existe.</p>';
				}else{
					$query_insert = mysqli_query($conexion,"INSERT INTO cliente(dni,nombres,apellido_paterno,apellido_materno,telefono,usuario_id) VALUES ('$dni','$nombre','$apellido_paterno','$apellido_materno','$telefono','$usuario_id')");
						if($query_insert){
							$alert= '<p class="saved">Cliente guardado correctamente.</p>';
						}else{
							$alert='<p class="error">El cliente no se ha podido guardar</p>';
						}
					}
				}
		}
		if(($_POST["documento"])=='RUC'){
			if(empty($_POST['ruc']) || empty($_POST['razon_social'])){
				$alert = '<p class="error">Complete los  obligatorios.</p>';
				}else{
				$ruc = $_POST['ruc'];
				$razon_social = $_POST['razon_social'];
				if(!empty($_POST['direccion'])){
				$direccion = $_POST['direccion'];
				}else{
					$direccion ='';
				}
				$usuario_id = $_SESSION['idUser'];

				$result = 0;
				$query = mysqli_query($conexion,"SELECT * FROM cliente where ruc = '$ruc'");
				$result = mysqli_fetch_array($query);

				if($result>0){
					$alert ='<p class="error">El número de RUC ya existe.</p>';
				}else{
					$query_insert = mysqli_query($conexion,"INSERT INTO cliente(ruc,razon_social,direccion,usuario_id) VALUES ('$ruc','$razon_social','$direccion','$usuario_id')");
						if($query_insert){
							$alert= '<p class="saved">Cliente guardado correctamente.</p>';
						}else{
							$alert='<p class="error">El cliente no se ha podido guardar</p>';
					}
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
	<title>Registro de Cliente</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Register Form Start--->
	<section id="container">
		<div class="div-new"><h1>Nuevo Cliente</h1>
			<hr>
			<form class="form-new"action="#" method="post">
				<label for="tipo_documento">Tipo de Documento</label>
				<select class="select-documento" name="documento" id="documento" onchange="cambioDocumento()">
					<option class="tipo-documento">DNI</option>
					<option class="tipo-documento">RUC</option>
				</select>
				<label for="dni">Documento de Identidad</label>
	            <input type="number" name="dni" id="dni" placeholder="12345678">
	            <label for="nombre">Nombres</label>
	            <input type="text" name="nombre" id="nombre" placeholder="Nombre">
	            <label for="nombre">Apellido paterno</label>
	            <input type="text" name="apellido_paterno" id="apellido_paterno" placeholder="Apellido Paterno">
	            <label for="nombre">Apellido Materno</label>
	            <input type="text" name="apellido_materno" id="apellido_materno" placeholder="Apellido Materno">
				<label for="telefono">Telefono (opcional)</label>
				<input type="number" name="telefono" id="telefono" placeholder="901234567">
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-client" value="Guardar Cliente"> 
			</form>
		</div>
	</section>
</body>
</html>