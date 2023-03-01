<?php
	//Comprobar sesión activa
	session_start();		
	include "../conexion.php";

	if(!empty($_POST)){
		$alert='';
		if(($_POST['documento'])=='DNI'){
			if(empty($_POST['dni']) || empty($_POST['nombre']) || empty($_POST['apellido_paterno'])){
				$alert='<p class "error">Complete los campos obligatorios.</p>';
			}else{
				$idcliente = $_POST['id'];
				$dni       = $_POST['dni'];
				$nombre    = $_POST['nombre'];
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
				$query = mysqli_query($conexion,"SELECT * FROM cliente WHERE (dni = '$dni' AND idcliente != $idcliente)");
				$result = mysqli_fetch_array($query);

				if($result>0){
					$alert ='<p class="error">El Documento de Identidad del cliente ya existe.</p>';
				}else{
					$sql_update = mysqli_query($conexion, "UPDATE cliente SET dni = '$dni', nombres ='$nombre', apellido_paterno = '$apellido_paterno', apellido_materno = '$apellido_materno',telefono= '$telefono' WHERE idcliente = $idcliente");
					
					if($sql_update){
							$alert= '<p class="saved">Cliente actualizado correctamente.</p>';
						}else{
							$alert='<p class="error">El cliente no se ha podido actualizar</p>';
						}
					}
				}
		}
		if(($_POST['documento'])=='RUC'){
			if(empty($_POST['ruc']) || empty($_POST['razon_social'])){
				$alert = '<p class="error">Complete los campos obligatorios.</p>';
				}else{
				$idcliente = $_POST['id'];
				$ruc = $_POST['ruc'];
				$razon_social = $_POST['razon_social'];
				if(!empty($_POST['direccion'])){
				$direccion = $_POST['direccion'];
				}else{
					$direccion ='';
				}
				$query = mysqli_query($conexion,"SELECT * FROM cliente WHERE (ruc = '$ruc' AND idcliente != $idcliente)");
				$result = mysqli_fetch_array($query);

				if($result>0){
					$alert ='<p class="error">El número de RUC ya existe.</p>';
				}else{
					$query_insert = mysqli_query($conexion,"UPDATE cliente SET ruc = '$ruc', razon_social = '$razon_social', direccion = '$direccion' WHERE idcliente = $idcliente");
						if($query_insert){
							$alert= '<p class="saved">Cliente actualizado correctamente.</p>';
						}else{
							$alert='<p class="error">El cliente no se ha podido actualizar</p>';
					}
				}
			}
		}
			mysqli_close($conexion);
		}
	//Mostrar Datos
	if(empty($_GET['id'])){
		header('Location:list_clientes.php');
	}
	$idcliente = $_REQUEST['id'];
	include "../conexion.php";
	$sql= mysqli_query($conexion,"SELECT * FROM cliente WHERE idcliente = $idcliente");
	$result = mysqli_num_rows($sql);
	if($result== 0){
		header('Location: list_clientes.php');
	}else{
		while ($data = mysqli_fetch_array($sql)){
			$idcliente = $data['idcliente'];
			$dni = $data['dni'];
			$nombre = $data['nombres'];
			$apellido_paterno = $data['apellido_paterno'];
			$apellido_materno = $data['apellido_materno'];
			$ruc = $data['ruc'];
			$razon_social = $data['razon_social'];
			$telefono = $data['telefono'];
			$direccion = $data['direccion'];
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Update Form Start--->
	<section id="container">
		<div class="div-new"><h1>Actualizar Cliente</h1>
			<hr>
			<form class="form-new"action="#" method="post">
				<label for="tipo_documento">Tipo de Documento</label>
				<?php
					if(!empty($dni)){ ?>
					<select class="select-documento" name="documento" id="documento" onchange="cambioDocumento()">
						<option class="tipo-documento">DNI</option>
						<option class="tipo-documento">RUC</option>
					</select>
					<input type="hidden" name="id" value="<?php echo $idcliente; ?>">	
					<label for="dni">Documento de Identidad</label>
					<input type="number" name="dni" id="dni" placeholder="12345678" value="<?php echo $dni; ?>">
					<label for="nombre">Nombres</label>
		            <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
		            <label for="nombre">Apellido paterno</label>
		            <input type="text" name="apellido_paterno" id="apellido_paterno" placeholder="Apellido Paterno" value="<?php echo $apellido_paterno; ?>">
		            <label for="nombre">Apellido Materno</label>
		            <input type="text" name="apellido_materno" id="apellido_materno" placeholder="Apellido Materno" value="<?php echo $apellido_materno; ?>">
		            <label for="telefono">Telefono (opcional)</label>
					<input type="number" name="telefono" id="telefono" placeholder="901234567" value="<?php echo $telefono; ?>">
					<?php }
				if(!empty($ruc)){ ?>
					<select class="select-documento" name="documento" id="documento" onchange="cambioDocumento()">
						<option class="tipo-documento">RUC</option>
						<option class="tipo-documento">DNI</option>
					</select>
					<input type="hidden" name="id" value="<?php echo $idcliente; ?>">
					<label for="ruc">RUC</label>
            		<input type="number" name="ruc" id="ruc" placeholder="10123456780" value="<?php echo $ruc; ?>">
            		<label for="nombre">Razon Social</label>
            		<input type="text" name="razon_social" id="razon_social" placeholder="Razon Social" value="<?php echo $razon_social; ?>">
					<label for="telefono">Direccion (opcional)</label>
					<input type="text" name="direccion" id="direccion" placeholder="Av. Las Lomas 123" value="<?php echo $direccion; ?>">
					<?php }?>
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-client" value="Actualizar Cliente"> 
			</form>
		</div>
	</section>
</body>
</html>