<?php
	
	if(empty($_SESSION['active'])){
		header('location: ../');
	}
	?>

<header>
		<div class="header">
			
			<img src="img/Logo-tr.png"><h1>Sistema de Ventas</h1>
			<div class="optionsBar">
				<p>Fecha <?php echo fechaC(); ?></p>
				<span>|</span>
				<span class="user"><?php echo $_SESSION['user']." - ".$_SESSION['rol']; ?> </span>
				<i class="fa fa-user"></i>
				<a href="include/salir.php"><i class="fa-solid fa-power-off"></i></a>
			</div>
		</div>
		<nav class="nav_dark">
			<ul>
				<li class="principal"><a href="../sistema"> <i class="fa fa-home"></i></a></li>
				<li class="principal"><a href="#">Usuario <i class="fa fa-user"></i></a>
					<ul>
						<li ><a href="register.php">Nuevo Usuario</a></li>
						<li><a href="list_users.php">Lista de Usuarios</a></li>
					</ul>
				</li>
				<li class="principal"><a href="#">Clientes <i class="fa fa-users"></i></a>
					<ul>
						<li><a href="register_cliente.php">Nuevo CLiente</a></li>
						<li><a href="list_clientes.php">Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal"><a href="#">Proveedores <i class="fa fa-building"></i></a>
					<ul>
						<li><a href="register_proveedor.php">Nuevo Proveedor</a></li>
						<li><a href="list_proveedores.php">Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal"><a href="#">Productos <i class="fa fa-screwdriver-wrench"></i></a>
					<ul>
						<li><a href="register_product.php">Nuevo Producto</a></li>
						<li><a href="list_products.php">Lista de Productos</a></li>
						<li><a href="entrada_products.php">Entrada de Productos</a></li>
					</ul>
				</li>
				<li class="principal"><a href="#">Ventas <i class="fa fa-cart-shopping"></i></a>
					<ul>
						<li><a href="register_venta.php">Nueva Venta</a></li>
						<li><a href="list_ventas.php">Lista de Ventas</a></li>
						<li><a href="list_proformas.php">Lista de Proformas</a></li>
					</ul>
				</li>
				  
				  <?php
				  					$noti = mysqli_query($conexion, "SELECT existencia, descripcion, categoria FROM producto WHERE ((existencia <= 1 AND categoria = 1) OR (existencia <= 3 AND categoria = 2) OR (existencia <= 7 AND categoria = 3));");
				  					$count_noti = mysqli_num_rows($noti);
				  					
				  					 ?>
				  <div class="not">
						<li class="icon-button">  	
						 		<i class="fa fa-bell">
							  			<span class="icon-button__badge"><?php echo $count_noti; ?></span>
						  		</i>
							  	<?php if($count_noti !=0){?>

						  	<ul class="dropdown-menu">
							  		
						  			<ul class="menu">
						  				<?php while($num_noti = mysqli_fetch_array($noti)){?>
					  						<li>
							  					<a href="">
							  						El producto <?php echo $num_noti['descripcion']; ?> solo le queda <?php echo $num_noti['existencia']; ?> productos en stock.
							  					</a>
							  				</li>
							  <?php			}
							  		 	}	
							  				?> 
						  				
					  		</ul>
					  	</li>
					</div>
				  </ul>
		</nav>
		
</header>
<div class="modal">
	<div class="bodyModal">
		
	</div>
</div>
