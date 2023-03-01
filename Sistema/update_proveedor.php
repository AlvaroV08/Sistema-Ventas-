<?php
	//Comprobar sesión activa
	session_start();		
	include "../conexion.php";

	if(!empty($_POST)){
		$alert='';
		if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono'])){
			$alert='<p class "error"> Todos los campos son obligatorios.</p>';
		}else{
			$codproveedor = $_POST['id'];
			$proveedor = $_POST['proveedor'];
			$contacto = $_POST['contacto'];
			$telefono = $_POST['telefono'];
			$direccion = $_POST['direccion'];
			
			$query = mysqli_query($conexion,"SELECT * FROM proveedor WHERE (proveedor = '$proveedor' AND codproveedor != $codproveedor)");
			$result = mysqli_fetch_array($query);

			if($result>0){
				$alert ='<p class="error">El nombre del proveedor ya se encuentra registrado.</p>';
			}else{
				$sql_update = mysqli_query($conexion, "UPDATE proveedor SET proveedor = '$proveedor', contacto ='$contacto',telefono = '$telefono', direccion = '$direccion' WHERE codproveedor = $codproveedor");
				
				if($sql_update){
						$alert= '<p class="saved">Proveedor actualizado correctamente.</p>';
					}else{
						$alert='<p class="error">El proveedor no se ha podido actualizar</p>';
					}
				}
			}
			mysqli_close($conexion);
		}
	//Mostrar Datos
	if(empty($_GET['id'])){
		header('Location:list_clientes.php');
	}
	$codproveedor = $_REQUEST['id'];
	include "../conexion.php";
	$sql= mysqli_query($conexion,"SELECT * FROM proveedor WHERE codproveedor = $codproveedor");
	$result = mysqli_num_rows($sql);
	if($result== 0){
		header('Location: list_proveedores.php');
	}else{
		while ($data = mysqli_fetch_array($sql)){
			$codproveedor = $data['codproveedor'];
			$proveedor = $data['proveedor'];
			$contacto = $data['contacto'];
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
	<title>Actualizar Proveedor</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Update Form Start--->
	<section id="container">
		<div class="div-new"><h1>Actualizar Proveedor</h1>
			<hr>
			<form class="form-new"action="#" method="post">
				<input type="hidden" name="id" value="<?php echo $codproveedor; ?>">
				<label for="proveedor">Proveedor</label>
				<input type="text" name="proveedor" id="proveedor" placeholder="12345678" value="<?php echo $proveedor; ?>">
				<label for="contacto">Contacto</label>
				<input type="text" name="contacto" id="contacto" placeholder="Nombre del contacto" value="<?php echo $contacto; ?>">
				<label for="telefono">Telefono</label>
				<input type="number" name="telefono" id="telefono" placeholder="901234567" value="<?php echo $telefono; ?>">
				<label for="direccion">Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion (opcional)" value="<?php echo $direccion; ?>">
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-proveedor" value="Actualizar Proveedor"> 
			</form>
		</div>
	</section>
</body>
</html>