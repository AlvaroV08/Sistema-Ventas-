<?php
	//Comprobar sesión activa
	session_start();	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<?php include "include/scripts.php"; ?>
	<title>Sistema de Ventas</title>
</head>
<body>
	<?php include "../conexion.php"; 
	include "include/header.php";
	 ?>
	<section id="container">
		<!-- Begin Page Content -->
		<br>
                <div class="container-fluid">
                	<?php 
                    	$finicio = date('Y/n/j 00:00:00');
                    	$ffinal = date('Y/n/j 23:59:59');?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Reporte de Ventas</h1>
	                    <div>
							<h5>Buscar por fecha</h5>
							<form action="search_reporte.php" method="get" class="form-search-date">
								<label>De: </label>
								<input type="date" name="fecha_de" id="fecha_de" required>
								<label> A </label>
								<input type="date" name="fecha_a" id="fecha_a" required>
								<button type="submit" class="btn-view"><i class="fa fa-search"></i></button>
							</form>
						</div>
                    </div>
                    <?

                    	$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombre as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
                    	$result = mysqli_num_rows($query);
                    	$ingresos = 0;
                    ?>
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Ingresos totales (Día)</div>
                                                <?php if($result > 0){
						                    		while($data = mysqli_fetch_assoc($query)){
						                    			$totalfactura = $data['totalfactura'];
						                    			$ingresos = $ingresos+$totalfactura;

						                    		}
						                    	?> <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $ingresos; ?></div>
						                    <?php	}else{?>
						                    	 <div class="h5 mb-0 font-weight-bold text-gray-800">0.00</div>
						                    <?php } ?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Ganancias totales (Día)</div>
                                                <?php 
                                                	$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombre as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
                    								$result = mysqli_num_rows($query);
                                                	if($result>0){
                                                		$gananciatotal = 0;
						                    		while($data = mysqli_fetch_assoc($query)){
						                    			$noFactura = $data['nofactura'];
						                    			$query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.sku, p.precio_compra, u.ubicacion, dt.cantidad, dt.precio_venta, (dt.cantidad * dt.precio_venta) as precio_total FROM factura f INNER JOIN detallefactura dt ON f.nofactura = dt.nofactura INNER JOIN producto p ON dt.codproducto = p.codproducto INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE f.nofactura = $noFactura");
														$result_detalle = mysqli_num_rows($query_productos);
														if($result_detalle > 0){
															$gananciasubtotal = 0;
															while ($data_productos = mysqli_fetch_assoc($query_productos)){
																	$codproducto = $data_productos['codproducto'];
																	$cantidad = $data_productos['cantidad'];
																	$precio = $data_productos['precio_venta'];
																	$query_precios = mysqli_query($conexion, "SELECT * from producto WHERE codproducto = $codproducto");
																	$precios = mysqli_fetch_array($query_precios);
																	$precio_compra = $precios['precio_compra'];
																	$ganancia = ($precio-$precio_compra)*$cantidad;
																	$gananciasubtotal = $ganancia+$gananciasubtotal;
																	} ?>
																	
																<?php }
							                    		$gananciatotal = $gananciasubtotal+$gananciatotal;
							                    		} ?>
						                    		<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $gananciatotal; ?></div><?php 
						                    		}else{ ?>
														<div class="h5 mb-0 font-weight-bold text-gray-800">0.00</div>
															<?php }?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                         <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ventas realizadas (Día)
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                	<?php $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombre as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
			                    						$result = mysqli_num_rows($query); 
			                    						if($result>0){
                                                    	?><div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $result; ?></div>
			                    						<?php }else{ ?>
			                    						<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
			                    						<?php }?>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Mayor venta (Día)</div>
                                                <?php $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombre as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.totalfactura DESC LIMIT 0,1");
			                    						$result = mysqli_num_rows($query); 
			                    						if($result>0){
			                    							$data = mysqli_fetch_array($query);
                                                    	?><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['totalfactura']; ?></div>
			                    						<?php }else{ ?>
			                    						<div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
			                    						<?php }?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
	</section>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>

</html>