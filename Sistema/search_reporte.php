<?php
//Comprobar sesión activa
session_start();		

include "../conexion.php";
if($_SESSION['rol'] != 3){
        header("location: ./");
    }
if(!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])){
		$fecha_de = $_REQUEST['fecha_de'];
		$fecha_a = $_REQUEST['fecha_a'];

		$buscar = '';

		if($fecha_de > $fecha_a){
			header("location: index.php");
		}else if($fecha_de == $fecha_a){
			$where = "f.estatus = 1 AND  f.fecha LIKE '$fecha_de%'";
			$buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
		}else{
			$f_de = $fecha_de.' 00:00:00';
			$f_a = $fecha_a.' 23:59:59';
			$where = "f.estatus = 1 AND f.fecha BETWEEN '$f_de' AND '$f_a'";
			$buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
		}

	}
	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<?php include "include/scripts.php"; ?>
    <script src="js/charts.js"></script>
	<title>Reporte de Ventas</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<!-- Begin Page Content -->
		<br>
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Reporte de Ventas</h1>
	                    <div>
							<h5>Buscar por fecha</h5>
							<form action="search_reporte.php" method="get" class="form-search-date">
								<label>De: </label>
								<input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>" required>
								<label> A </label>
								<input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>" required>
								<button type="submit" class="btn-view"><i class="fa fa-search"></i></button>
							</form>
						</div>
                    </div>
                    <?php
                    	$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
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
                                                Ingresos totales (Total)</div>
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
                                            <script>
                                                const label0 ='Ingresos S/.';
                                                const valores0 = [];
                                                const fecha0 = [];
                                                <?php
                                                $dateTime1 = new dateTime($fecha_de);
                                                $dateTime2 = new dateTime($fecha_a);
                                                $dateDifference = $dateTime1->diff($dateTime2);
                                                $diff0 = $dateDifference->format('%a');
                                                
                                                if($diff0==0){
                                                    $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
                                                    $result = mysqli_num_rows($query);
                                                    $ingresos = 0;
                                                    if($result > 0){
                                                        $ingresos = 0;  
                                                        while($data = mysqli_fetch_assoc($query)){
                                                            $totalfactura = $data['totalfactura'];
                                                            $ingresos = $ingresos+$totalfactura;
                                                        }
                                                    }
                                                    ?>
                                                    fecha0.push("<?php echo date('j/n/Y',strtotime ($fecha_de)); ?>");
                                                    valores0.push(<?php echo $ingresos; ?>); <?php
                                                }else{
                                                while($diff0 >= 0){
                                                $finicio = date('Y/n/j 00:00:00', strtotime($f_a.'- '.$diff0.' days'));
                                                $ffinal = date('Y/n/j 23:59:59', strtotime($f_a.'- '.$diff0.' days'));
                                                $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
                                                    $result = mysqli_num_rows($query);
                                                    $ingresos = 0;
                                                    if($result > 0){
                                                        $ingresos = 0;  
                                                        while($data = mysqli_fetch_assoc($query)){
                                                            $totalfactura = $data['totalfactura'];
                                                            $ingresos = $ingresos+$totalfactura;
                                                        }
                                                    }
                                                    ?>
                                                    fecha0.push("<?php echo date('j/n/Y',strtotime ($finicio)); ?>");
                                                    valores0.push(<?php echo $ingresos; ?>);
                                                    <?php
                                                    $diff0 = $diff0-1;
                                                    } 
                                                }?>
                                            </script>
                                            <button class="btn-chart ingresos" cl="btn-ingresos" disabled onclick="createChart(fecha0,label0,valores0);"><i class="fas fa-calendar fa-2x text-gray-300"></i></button>
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
                                                Ganancias totales (Total)</div>

                                                <?php 
                                                	$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
                    								$result = mysqli_num_rows($query);
                                                	if($result>0){
                                                		$gananciatotal = 0;
						                    		while($data = mysqli_fetch_assoc($query)){
						                    			$noFactura = $data['nofactura'];
						                    			$query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.sku, dt.precio_compra, u.ubicacion, dt.cantidad, dt.precio_venta, (dt.cantidad * dt.precio_venta) as precio_total FROM factura f INNER JOIN detallefactura dt ON f.nofactura = dt.nofactura INNER JOIN producto p ON dt.codproducto = p.codproducto INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE f.nofactura = $noFactura");
														$result_detalle = mysqli_num_rows($query_productos);
														if($result_detalle > 0){
															$gananciasubtotal = 0;
															while ($data_productos = mysqli_fetch_assoc($query_productos)){
																	$codproducto = $data_productos['codproducto'];
																	$cantidad = $data_productos['cantidad'];
																	$precio = $data_productos['precio_venta'];
																	$precio_compra = $data_productos['precio_compra'];
																	$ganancia = ($precio-$precio_compra)*$cantidad;
																	$gananciasubtotal = $ganancia+$gananciasubtotal;
                                                                    }
							                    					$gananciatotal = $gananciasubtotal+$gananciatotal;
                                                                     ?>
																	
																<?php }
							                    		} ?>
						                    		<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $gananciatotal; ?></div><?php 
						                    		}else{ ?>
														<div class="h5 mb-0 font-weight-bold text-gray-800">0.00</div>
															<?php }?>
                                        </div>
                                        <div class="col-auto">
                                            <script>
                                                const label ='Ganancias S/.';
                                                const valores = [];
                                                const fecha = [];
                                                <?php
                                                $dateTime1 = new dateTime($fecha_de);
                                                $dateTime2 = new dateTime($fecha_a);
                                                $dateDifference = $dateTime1->diff($dateTime2);
                                                $diff = $dateDifference->format('%a');
                                                if($diff==0){
                                                $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
                                                $result = mysqli_num_rows($query);
                                                if($result>0){
                                                $gananciatotal = 0;
                                                while($data = mysqli_fetch_assoc($query)){
                                                    $noFactura = $data['nofactura'];
                                                    $query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.sku, dt.precio_compra, u.ubicacion, dt.cantidad, dt.precio_venta, (dt.cantidad * dt.precio_venta) as precio_total FROM factura f INNER JOIN detallefactura dt ON f.nofactura = dt.nofactura INNER JOIN producto p ON dt.codproducto = p.codproducto INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE f.nofactura = $noFactura");
                                                    $result_detalle = mysqli_num_rows($query_productos);
                                                    if($result_detalle > 0){
                                                        $gananciasubtotal = 0;
                                                        while ($data_productos = mysqli_fetch_assoc($query_productos)){
                                                            $codproducto = $data_productos['codproducto'];
                                                            $cantidad = $data_productos['cantidad'];
                                                            $precio_total = $data_productos['precio_total'];
                                                            $precio_compra = $data_productos['precio_compra'];
                                                            $ganancia = $precio_total-($precio_compra*$cantidad);
                                                            $gananciasubtotal = $ganancia+$gananciasubtotal;
                                                            }                                           
                                                            $gananciatotal = $gananciasubtotal+$gananciatotal;
                                                        }
                                                    } 
                                                }
                                                ?>
                                                valores.push(<?php echo $gananciatotal; ?>);
                                                fecha.push("<?php echo date('j/n/Y',strtotime ($fecha_de)); ?> "); <?php
                                                }else{
                                                while($diff >= 0){
                                                $finicio = date('Y/n/j 00:00:00', strtotime($f_a.'- '.$diff.' days'));
                                                $ffinal = date('Y/n/j 23:59:59', strtotime($f_a.'- '.$diff.' days'));
                                                $gananciatotal = 0;
                                                $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
                                                $result = mysqli_num_rows($query);
                                                if($result>0){
                                                $gananciatotal = 0;
                                                while($data = mysqli_fetch_assoc($query)){
                                                    $noFactura = $data['nofactura'];
                                                    $query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.sku, dt.precio_compra, u.ubicacion, dt.cantidad, dt.precio_venta, (dt.cantidad * dt.precio_venta) as precio_total FROM factura f INNER JOIN detallefactura dt ON f.nofactura = dt.nofactura INNER JOIN producto p ON dt.codproducto = p.codproducto INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE f.nofactura = $noFactura");
                                                    $result_detalle = mysqli_num_rows($query_productos);
                                                    if($result_detalle > 0){
                                                        $gananciasubtotal = 0;
                                                        while ($data_productos = mysqli_fetch_assoc($query_productos)){
                                                            $codproducto = $data_productos['codproducto'];
                                                            $cantidad = $data_productos['cantidad'];
                                                            $precio_total = $data_productos['precio_total'];
                                                            $precio_compra = $data_productos['precio_compra'];
                                                            $ganancia = $precio_total-($precio_compra*$cantidad);
                                                            $gananciasubtotal = $ganancia+$gananciasubtotal;
                                                            }                                           
                                                            $gananciatotal = $gananciasubtotal+$gananciatotal;
                                                        }
                                                    } 
                                                }
                                                ?>
                                                valores.push(<?php echo $gananciatotal; ?>);
                                                fecha.push("<?php echo date('j/n/Y',strtotime ($finicio)); ?> ");
                                                <?php
                                                $diff = $diff-1;}
                                                }?>
                                            
                                            </script>
                                            <button class="btn-chart ganancias" cl="btn-ganancias" onclick="createChart(fecha,label,valores);"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></button>
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
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ventas realizadas (Total)
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                	<?php 
                                                	$query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
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
                                            <script>
                                                const label2 ='N° Ventas';
                                                const valores2 = [];
                                                const fecha2 = [];
                                                <?php
                                                $dateTime1 = new dateTime($fecha_de);
                                                $dateTime2 = new dateTime($fecha_a);
                                                $dateDifference = $dateTime1->diff($dateTime2);
                                                $diff2 = $dateDifference->format('%a');
                                                if($diff2==0){
                                                    $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
                                                        $result = mysqli_num_rows($query); 
                                                        if($result>0){
                                                        $ventas = $result;
                                                        ?>
                                                        <?php }
                                                ?>
                                                fecha2.push("<?php echo date('j/n/Y',strtotime ($fecha_de)); ?>");
                                                valores2.push(<?php echo $result; ?>); <?php 
                                                }else{
                                                while($diff2 >= 0){
                                                $finicio = date('Y/n/j 00:00:00', strtotime($f_a.'- '.$diff2.' days'));
                                                $ffinal = date('Y/n/j 23:59:59', strtotime($f_a.'- '.$diff2.' days'));
                                                $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
                                                        $result = mysqli_num_rows($query); 
                                                        if($result>0){
                                                        $ventas = $result;
                                                        ?>
                                                        <?php }
                                                ?>
                                                fecha2.push("<?php echo date('j/n/Y',strtotime ($finicio)); ?>");
                                                valores2.push(<?php echo $result; ?>);
                                                <?php
                                                $diff2 = $diff2-1;
                                                    } 
                                                }?>
                                            </script>
                                            <button class="btn-chart ventas" cl="btn-ventas" onclick="createChart(fecha2,label2,valores2);"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></button>
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
                                                Mayor venta (Total)</div>
                                                <?php 
                                                $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND $where ORDER BY f.totalfactura DESC LIMIT 0,1");
			                    						$result = mysqli_num_rows($query); 
			                    						if($result>0){
			                    							$data = mysqli_fetch_array($query);
                                                    	?><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['totalfactura']; ?></div>
			                    						<?php }else{ ?>
			                    						<div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
			                    						<?php }?>
                                        </div>
                                        <div class="col-auto">
                                            <script>
                                                const label3 ='Top Venta';
                                                const valores3 = [];
                                                const fecha3 = [];
                                                <?php
                                                $dateTime1 = new dateTime($fecha_de);
                                                $dateTime2 = new dateTime($fecha_a);
                                                $dateDifference = $dateTime1->diff($dateTime2);
                                                $diff3 = $dateDifference->format('%a');
                                                if($diff3==0){
                                                    $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.totalfactura DESC LIMIT 0,1");
                                                    $result = mysqli_num_rows($query); 
                                                    $totalfactura = 0;
                                                    if($result>0){
                                                        $data = mysqli_fetch_array($query);
                                                        $totalfactura = $data['totalfactura'];
                                                    }
                                                    ?>
                                                    valores3.push(<?php echo $totalfactura; ?>);
                                                    fecha3.push("<?php echo date('j/n/Y',strtotime ($fecha_de)); ?> "); <?php
                                                }else{
                                                while($diff3 >= 0){
                                                $finicio = date('Y/n/j 00:00:00', strtotime($f_a.'- '.$diff3.' days'));
                                                $ffinal = date('Y/n/j 23:59:59', strtotime($f_a.'- '.$diff3.' days'));
                                                    $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.totalfactura DESC LIMIT 0,1");
                                                    $result = mysqli_num_rows($query); 
                                                    $totalfactura = 0;
                                                    if($result>0){
                                                        $data = mysqli_fetch_array($query);
                                                        $totalfactura = $data['totalfactura'];
                                                    }
                                                    ?>
                                                    valores3.push(<?php echo $totalfactura; ?>);
                                                    fecha3.push("<?php echo date('j/n/Y',strtotime ($finicio)); ?> ");
                                                    <?php 
                                                    $diff3=$diff3-1;
                                                    } 
                                                }
                                                ?>
                                            </script>
                                            <button class="btn-chart top" cl="btn-top" onclick="createChart(fecha3,label3,valores3)"><i class="fa-solid fa-thumbs-up fa-2x text-gray-300"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
					<?php
                    if($fecha_de == $fecha_a){ ?>
                    <div>
                        <h1>El <?php echo date('j/n/Y',strtotime($fecha_de)); ?></h1>
                    </div>
                    <?php
                    }else{ ?>

                    <div>
                        <h1>Desde el <?php echo date('j/n/Y',strtotime($fecha_de)); ?>  hasta <?php echo date ('j/n/Y',strtotime($fecha_a)); ?></h1>
                    </div>
                    <?php }
                    ?> 
                    <div class="div-chart">  
                    <canvas id="myChart"></canvas>
                    </div>
                    <script>
                      let myChart = null;
                      if($('.ingresos').prop('disabled')){
                        const label ='Ingresos S/.';
                        const valores = [];
                        const fecha = [];
                        <?php
                        $dateTime1 = new dateTime($fecha_de);
                        $dateTime2 = new dateTime($fecha_a);
                        $dateDifference = $dateTime1->diff($dateTime2);
                        $diff = $dateDifference->format('%a');
                        if($diff==0){
                            $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE $where ORDER BY f.fecha DESC");
                            $result = mysqli_num_rows($query);
                            $ingresos = 0;
                            if($result > 0){
                                $ingresos = 0;  
                                while($data = mysqli_fetch_assoc($query)){
                                    $totalfactura = $data['totalfactura'];
                                    $ingresos = $ingresos+$totalfactura;
                                    }
                                }
                            ?>
                            fecha.push("<?php echo date('j/n/Y',strtotime ($fecha_de)); ?>");
                            valores.push(<?php echo $ingresos; ?>); <?php
                        }else{
                        while($diff >= 0){
                            $finicio = date('Y/n/j 00:00:00', strtotime($f_a.'- '.$diff.' days'));
                            $ffinal = date('Y/n/j 23:59:59', strtotime($f_a.'- '.$diff.' days'));
                            $query = mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estatus, u.nombre as vendedor, cl.nombres as cliente FROM factura f INNER JOIN cliente cl ON f.codcliente = cl.idcliente INNER JOIN usuario u ON f.usuario = u.idusuario WHERE f.estatus =1 AND f.fecha BETWEEN '$finicio' AND '$ffinal' ORDER BY f.fecha DESC");
                            $result = mysqli_num_rows($query);
                            $ingresos = 0;
                            if($result > 0){
                                $ingresos = 0;  
                                while($data = mysqli_fetch_assoc($query)){
                                    $totalfactura = $data['totalfactura'];
                                    $ingresos = $ingresos+$totalfactura;
                                }
                            }
                            ?>
                            fecha.push("<?php echo date('j/n/Y',strtotime ($finicio)); ?>");
                            valores.push(<?php echo $ingresos; ?>);
                            <?php
                            $diff = $diff-1;
                            } 
                        }?>
                            createChart(fecha,label,valores);
                            }
                      function createChart(fecha,label,valores){
                            if(myChart!=null){
                                destroyChart();
                            }
                        const data = {
                            labels: fecha,
                            datasets: [{
                            label: label,
                            data: valores,
                            backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 0.7
                        }]
                      };
                      const config = {
                        type: 'bar',
                        data,
                        options: {
                          scales: {
                            y: {
                              beginAtZero: true
                            }
                          }
                        }
                      };
                      myChart = new Chart(document.getElementById('myChart'),config);
                      
                    }
                    function destroyChart(){
                        myChart.destroy();
                    }
                    </script>
	</section>
<!-- Bootstrap core JavaScript-->
    
</body>
</html>