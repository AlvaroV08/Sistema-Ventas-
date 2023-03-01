<?php
	//Comprobar sesión activa
	session_start();		
	include "../conexion.php";

	if(!empty($_POST)){
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])){
			$alert='<p class "error"> Todos los campos son obligatorios.</p>';
		}else{
			$iduser = $_POST['idUsuario'];
			$nombre = $_POST['nombre'];
			$email = $_POST['correo'];
			$usuario = $_POST['usuario'];
			$clave = md5($_POST['clave']);
			$rol = $_POST['rol'];
			
			$query = mysqli_query($conexion,"SELECT * FROM usuario where (usuario = '$usuario' AND idusuario != $iduser) OR (correo = '$email' AND idusuario != $iduser)");
			$result = mysqli_fetch_array($query);

			if($result>0){
				$alert ='<p class="error">El correo o nombre de usuario ya existe.</p>';
			}else{
				if(empty($_POST['clave'])){
					$sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre', correo ='$email',usuario= '$usuario', rol = '$rol' WHERE idusuario = $iduser");
				}else{
					$sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre', correo ='$email',usuario= '$usuario',clave = '$clave', rol = '$rol' WHERE idusuario = $iduser");
				}
				if($sql_update){
						$alert= '<p class="saved">Usuario actualizado correctamente.</p>';
					}else{
						$alert='<p class="error">El usuario no se ha podido actualizar</p>';
					}
				}
			}
			mysqli_close($conexion);
		}
	//Mostrar Datos
	if(empty($_GET['id'])){
		header('Location:list_users.php');
	}
	$iduser = $_GET['id'];
	include "../conexion.php";
	$sql= mysqli_query($conexion,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM usuario u INNER JOIN rol r on u.rol = r.idrol WHERE idusuario = $iduser");
	$result = mysqli_num_rows($sql);
	if($result== 0){
		header('Location: list_users.php');
	}else{
		$option = '';
		while ($data = mysqli_fetch_array($sql)){
			$iduser = $data['idusuario'];
			$nombre = $data['nombre'];
			$correo = $data['correo'];
			$usuario = $data['usuario'];
			$idrol = $data['idrol'];
			$rol = $data['rol'];

			if($idrol == 1){
				$option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
			}else if($idrol == 2){
				$option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<title>Actualización de Usuario</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<!---Update Form Start--->
	<section id="container">
		<div class="div-new"><h1>Actualizar Usuario</h1>
			<hr>
			<form class="form-new"action="#" method="post">
				<input type="hidden" name="idUsuario" value="<?php echo $iduser; ?>">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre y Apellido" value="<?php echo $nombre; ?>">
				<label for="correo">Correo</label>
				<input type="text" name="correo" id="correo" placeholder="ejemplo@gmail.com" value="<?php echo $correo; ?>">
				<label for="usuario">Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Username" value="<?php echo $usuario; ?>">
				<label for="clave">Contraseña</label>
				<input type="password" name="clave" id="clave" placeholder="Password">
				<label for="rol">Rol</label>
				<?php
					include "../conexion.php";
					$query_rol = mysqli_query($conexion, "SELECT * FROM rol");
					mysqli_close($conexion);
					$result_rol = mysqli_num_rows($query_rol);
				?>	 
				<select  name="rol" id="rol" class="notItemOne">
					<?php
						echo $option;
						if($result_rol>0){
							while ($rol = mysqli_fetch_array($query_rol)){
								?>
								<option value="<?php echo $rol["idrol"];?>"><?php echo $rol["rol"];?></option>
							<?php
						}
					}
						?> 
					</select>
				<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
				<input class="btnRegister"type="submit" name="sumbit-user" value="Actualizar Usuario" >
			</form>
		</div>
	</section>
</body>
</html>