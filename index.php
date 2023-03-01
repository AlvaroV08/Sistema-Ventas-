<?php
	$alert = '';
	//Comprobar sesión activa
	session_start();
	
	if(!empty($_SESSION['active'])){
		header('location: sistema/');
	}else{
		if(!empty ($_POST)){

			//Comprobar campos vacíos
			if(empty($_POST['usuario']) || empty($_POST['clave'])){
				$alert = 'Ingrese un usuario y una clave';	
				}else{
				//Conexión a la base de datos
				require_once "conexion.php";
				
				$user = mysqli_real_escape_string($conexion,$_POST['usuario']);
				$password = md5(mysqli_real_escape_string($conexion,$_POST['clave']));
				$query = mysqli_query($conexion,"SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$password'");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result >0) {
					$data = mysqli_fetch_array($query);
					//Guardar datos del usuario	
					$_SESSION['active'] = true;
					$_SESSION['idUser'] = $data['idusuario'];
					$_SESSION['nombre'] = $data['nombre'];
					$_SESSION['email'] = $data['correo'];
					$_SESSION['user'] = $data['usuario'];
					$_SESSION['rol'] = $data['rol'];
					header('location: sistema/');

					}else{
						$alert = 'El usuario o la clave son incorrectos';
						session_destroy();
					}	
				}
			}
		}
?>	
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login | Inventario</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" href="img/Logo-tr.png">
</head>
<body>
	<section id="container">
		<!-- Formulario Login -->
		<form action="" method="post">
			<h3>INICIAR SESION</h3>
			<img src="img/Logo-tr.png" alt="Login">
			<input type="text" name="usuario" placeholder="Usuario">
			<input type="password" name="clave" placeholder="Clave">
			<div class="alert"><?php echo isset($alert) ? $alert :''; ?></div>
			<input type="submit" value="Ingresar" name="btnIngresar">
		</form>
	</section> 

</body>
</html>