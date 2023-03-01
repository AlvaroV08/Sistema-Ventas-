<?php
	//Comprobar sesión activa
	session_start();
	if($_SESSION['rol'] == 2){
		header("location: ./");
	}
	include "../conexion.php";
	

	if(!empty($_POST)){
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol'])){
			$alert='<p class "error"> Todos los campos son obligatorios.</p>';
		}else{
			$nombre = $_POST['nombre'];
			$email = $_POST['correo'];
			$usuario = $_POST['usuario'];
			$clave = md5($_POST['clave']);
			$rol = $_POST['rol'];
			$query = mysqli_query($conexion,"SELECT * FROM usuario where usuario = '$usuario' OR correo = '$email'");
			
			$result = mysqli_fetch_array($query);

			if($result>0){
				$alert ='<p class="error">El correo o nombre de usuario ya existe.</p>';
			}else{

				$query_insert = mysqli_query($conexion,"INSERT INTO usuario(nombre,correo,usuario,clave,rol) VALUES ('$nombre','$email','$usuario','$clave','$rol')");
					if($query_insert){
						$alert= '<p class="saved">Usuario registrado correctamente.</p>';
					}else{
						$alert='<p class="error">El usuario no se ha podido registrar</p>';
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
	<title>Registro de Usuario</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Register Form Start--->
	<section id="container">
		<div class="div-new"><h1>Nuevo Usuario</h1>
			<hr>
			<form class="form-new"action="#" method="post">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre y Apellido">
				<label for="correo">Correo</label>
				<input type="text" name="correo" id="correo" placeholder="ejemplo@gmail.com">
				<label for="usuario">Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Username">
				<label for="clave">Contraseña</label>
				<input type="password" name="clave" id="clave" placeholder="Password">
				<label for="rol">Rol</label>
				
				<?php
					include "../conexion.php";
					$query_rol = mysqli_query($conexion, "SELECT * FROM rol");
					mysqli_close($conexion);
					$result_rol = mysqli_num_rows($query_rol);
				?>	 
				<select  name="rol" id="rol">
					<?php
						if($result_rol>0){
							while ($rol = mysqli_fetch_array($query_rol)){
								if($rol['idrol']!=3){ ?>
								<option value="<?php echo $rol["idrol"];?>"><?php echo $rol["rol"];?></option>
							<?php }
							}
						}
						?> 
				
				</select>
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-user" value="Registrar Usuario"> 
			</form>
		</div>
	</section>
</body>
</html>