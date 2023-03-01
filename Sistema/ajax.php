<?php 
	session_start();
	include "../conexion.php";

	//include "cancelXML.php";
if(!empty($_POST)){

	//Extraer Producto 
	if($_POST['action'] == 'infoVenta'){
	
		$producto_id = $_POST['producto'];
		$query = mysqli_query($conexion, "SELECT codproducto,sku,descripcion,existencia,precio,precio_compra,foto FROM producto WHERE codproducto = $producto_id");

		$result = mysqli_num_rows($query);
		if($result>0){
			$data = mysqli_fetch_assoc($query);
			
			
			
			if($data['foto'] != 'img_producto.png'){
							$data['foto'] = 'img/uploads/'.$data['foto'];
						}else{
							$data['foto'] = 'img/'.$data['foto'];
						}
			echo json_encode($data,JSON_UNESCAPED_UNICODE);
			exit;
				
			}
			echo 'error';
			exit;
	}
	//Buscar Producto
	if($_POST['action'] == 'infoBuscar'){
		$tabla='<tr>
				        <th>Sku</th>
				        <th>Descripción</th>
				        <th>Precio</th>
				        <th>Existencia</th>
				        <th>Proveedor</th>
				        <th>Foto</th>
					    <th>Acciones</th>
				</tr>';
		if(empty($_POST['producto'])){
			$query = mysqli_query($conexion, "SELECT p.codproducto,p.sku,u.ubicacion, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE p.estatus = 1 ORDER BY p.codproducto ASC");
			
			$arrayData = array();
			$result = mysqli_num_rows($query);
			if($result>0){
				while($data = mysqli_fetch_assoc($query)){
					$codproducto = $data['codproducto'];
					$query_sku = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $codproducto");
					$sku = mysqli_fetch_array($query_sku);
					$data['sku'] = $sku['sku'];
					$descripcion = $data['descripcion'];
					$ubicacion = $data['ubicacion'];
					$cantidad = $data['existencia'];
					$proveedor = $data['proveedor'];
					if($data['foto'] != 'img_producto.png'){
							$foto = 'img/uploads/'.$data['foto'];
							}else{
							$foto = 'img/'.$data['foto'];
							}
					$tabla.='<tr class="tab'.$data['codproducto'].'">
								<td class="sku'.$codproducto.'">'.$data['sku'].'</td>
								<td class="celDescripcion">'.$data['descripcion'].'</td>
								<td class="celPrecio precio'.$codproducto.'">'.$data['precio'].'</td>
								<td class="celExistencia">'.$data['existencia'].'</td>
						        <td>'.$data['proveedor'].'</td>  
						        <td><img src="'.$foto.'" alt = "'.$data['descripcion'].'"style="width: 60px; height: auto; margin:auto;"></td>
						        <td>
								<input type="number" class="inp_cantidad cant'.$codproducto.'" name="cantidad" min="0" step="1" value="0">
						        <button class="btn-view add_cart inactivo" onclick="add_cart('.$codproducto.');" type="button" disabled product="'.$codproducto.'"><i class="fa fa-cart-shopping" style="font-size:18px;"></i></button>
						        </td>
						      </tr>';

				}
				$arrayData['tabla'] = $tabla;
				echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";
			}
		}else{
			$producto = $_POST['producto'];
			$query = mysqli_query($conexion, "SELECT p.codproducto,p.sku,u.ubicacion, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE (p.sku LIKE '%$producto%' OR p.descripcion LIKE '%$producto%' OR pr.proveedor LIKE '%$producto%') AND p.estatus = 1 ORDER BY p.codproducto ASC");
			$arrayData = array();
			$result = mysqli_num_rows($query);
			if($result>0){
				while($data = mysqli_fetch_assoc($query)){
					$codproducto = $data['codproducto'];
					$query_sku = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $codproducto");
					$sku = mysqli_fetch_array($query_sku);
					$data['sku'] = $sku['sku'];
					$descripcion = $data['descripcion'];
					$ubicacion = $data['ubicacion'];
					$cantidad = $data['existencia'];
					$proveedor = $data['proveedor'];
					$foto = $data['foto'];
					if($data['foto'] != 'img_producto.png'){
							$foto = 'img/uploads/'.$data['foto'];
							}else{
							$foto = 'img/'.$data['foto'];
							}
					$tabla.='<tr class="tab'.$codproducto.'">
								<td class="sku'.$codproducto.'">'.$data['sku'].'</td>
						        <td class="celDescripcion">'.$data['descripcion'].'</td>
						        <td class="celPrecio">'.$data['precio'].'</td>
						        <td class="celExistencia">'.$data['existencia'].'</td>
						        <td>'.$data['proveedor'].'</td>  
						        <td><img src="'.$foto.'" alt = "'.$data['descripcion'].'"style="width: 60px; height: auto; margin:auto;"></td>
								<td>
								<input type="number" class="inp_cantidad cant'.$codproducto.'" name="cantidad" min="0" step="1" value="0">
						        <button class="btn-view add_cart inactivo" onclick="add_cart('.$codproducto.');" type="button" disabled><i class="fa fa-cart-shopping" style="font-size:18px;"></i></button>
						        </td>
						      </tr>';
					}
				$arrayData['tabla'] = $tabla;
				echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			
			}else{
				echo "error";
			}
			exit;
		}
	}
	//Extraer Cliente 
	if($_POST['action'] == 'infoCliente'){
	
		$idcliente = $_POST['cliente'];
		$query = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");

		$result = mysqli_num_rows($query);
		if($result>0){
			$data = mysqli_fetch_assoc($query);
			
			
			echo json_encode($data,JSON_UNESCAPED_UNICODE);
			exit;
				
			}
			echo 'error';
			exit;
	}
	//Extraer Usuario 
	if($_POST['action'] == 'infoUser'){
	
		$iduser = $_POST['user'];
		$query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario = $iduser");

		$result = mysqli_num_rows($query);
		if($result>0){
			$data = mysqli_fetch_assoc($query);
			
			
			echo json_encode($data,JSON_UNESCAPED_UNICODE);
			exit;
				
			}
			echo 'error';
			exit;
	}
	//Extraer Proveedor 
	if($_POST['action'] == 'infoProv'){
	
		$codprov = $_POST['prov'];
		$query = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $codprov");

		$result = mysqli_num_rows($query);
		if($result>0){
			$data = mysqli_fetch_assoc($query);
			
			
			echo json_encode($data,JSON_UNESCAPED_UNICODE);
			exit;
				
			}
			echo 'error';
			exit;
	}
	//Agregar Producto a Entrada
	if($_POST['action'] =='addProduct'){
			
			if(!empty($_POST['cantidad']) || !empty($_POST['producto_id'])){
			
				$cantidad = $_POST['cantidad'];
				$producto_id = $_POST['producto_id'];
				$user_id = $_SESSION['idUser'];
				$precio = $_POST['precio'];
				if($precio == 0){
					$query_precio = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $producto_id");
					$data_precio = mysqli_fetch_array($query_precio);
					$precio = $data_precio['precio_compra']; 
				}
				$query_insert = mysqli_query($conexion, "INSERT INTO entradas(codproducto, cantidad, precio, usuario_id) VALUES ($producto_id,$cantidad, $precio, $user_id)");
			if($query_insert){
		
				$query_upd = mysqli_query($conexion, "CALL actualizar_precio_producto($cantidad,$precio,$producto_id)");
				$result_pro = mysqli_num_rows($query_upd);
				if($result_pro>0){
		
					$data = mysqli_fetch_assoc($query_upd);
					$data['producto_id'] = $producto_id;
					echo json_encode($data, JSON_UNESCAPED_UNICODE);
					exit;
				}
			}else{
				echo 'error';
			} mysqli_close($conexion);
		}else{
			echo 'error';
			}
	}
	//Eliminar Producto
	if($_POST['action'] == 'delProduct'){
		if(empty($_POST['del_producto_id'])){
			echo 'error';
		}else{
			$idproducto = $_POST['del_producto_id'];
			$query_delete = mysqli_query($conexion, "UPDATE producto SET estatus = 2 WHERE codproducto = $idproducto");
			mysqli_close($conexion);
			if($query_delete){
				echo 'ok';
			}else{
				echo 'Error al eliminar';
				}
			}
		}
	//Eliminar Cliente
	if($_POST['action'] == 'delCliente'){
		if(empty($_POST['del_cliente_id'])){
			echo 'error';
		}else{
			$idcliente = $_POST['del_cliente_id'];
			$query_delete = mysqli_query($conexion, "DELETE FROM cliente WHERE idcliente = $idcliente");
			mysqli_close($conexion);
			if($query_delete){
				echo 'ok';
			}else{
				echo 'Error al eliminar';
				}
			}
		}
	//Eliminar Usuario
	if($_POST['action'] == 'delUser'){
		if(empty($_POST['del_usuario_id'])){
			echo 'error';
		}else{
			$iduser = $_POST['del_usuario_id'];
			$query_delete = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = $iduser");
			mysqli_close($conexion);
			if($query_delete){
				echo 'ok';
			}else{
				echo 'Error al eliminar';
				}
			}
		}
	//Eliminar Proveedor
	if($_POST['action'] == 'delProv'){
		if(empty($_POST['del_prov_id'])){
			echo 'error';
		}else{
			$codprov = $_POST['del_prov_id'];
			$query_delete = mysqli_query($conexion, "DELETE FROM proveedor WHERE codproveedor = $codprov");
			mysqli_close($conexion);
			if($query_delete){
				echo 'ok';
			}else{
				echo 'Error al eliminar';
				}
			}
		}		
	//Buscar Cliente
	if($_POST['action'] == 'searchCliente'){
		if(!empty($_POST['cliente' ])){
				$dni = $_POST['cliente'];
				$query = mysqli_query($conexion, "SELECT * FROM cliente WHERE dni LIKE '$dni' OR ruc LIKE '$dni'");
					mysqli_close($conexion) ;
					$result = mysqli_num_rows($query);
					$data = '';
					if($result >0){
						$data = mysqli_fetch_assoc($query);
					}else{
						$data = 0;
					}
					echo json_encode ($data, JSON_UNESCAPED_UNICODE) ;
			}
			exit;
		}
	//Buscar Cliente por DNI API
	if($_POST['action']=='verDocumento'){
		if(!empty($_POST['dni'])){
			if($_POST['tipo']=='Boleta'){
				$dni = $_POST['dni'];
				$token = "e90c169d36cf24583735a363023b59";
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
				    CURLOPT_URL => 'https://utildatos.com/api/dni',
				    CURLOPT_RETURNTRANSFER => true,
				    CURLOPT_ENCODING => '',
				    CURLOPT_MAXREDIRS => 10,
				    CURLOPT_TIMEOUT => 0,
				    CURLOPT_FOLLOWLOCATION => false,
				    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				    CURLOPT_CUSTOMREQUEST => 'POST',
				    CURLOPT_POSTFIELDS => array('dni' => $dni),
				    CURLOPT_HTTPHEADER => array(
				        'Authorization: Bearer '.$token
				    ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				echo json_encode($response,JSON_UNESCAPED_UNICODE);
			}else{
				$ruc = $_POST['dni'];
				$token = "e90c169d36cf24583735a363023b59";

				$curl = curl_init();
				curl_setopt_array($curl, array(
				    CURLOPT_URL => 'https://utildatos.com/api/sunat-reducido',
				    CURLOPT_RETURNTRANSFER => true,
				    CURLOPT_ENCODING => '',
				    CURLOPT_MAXREDIRS => 10,
				    CURLOPT_TIMEOUT => 0,
				    CURLOPT_FOLLOWLOCATION => false,
				    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				    CURLOPT_CUSTOMREQUEST => 'POST',
				    CURLOPT_POSTFIELDS => array('ruc' => $ruc),
				    CURLOPT_HTTPHEADER => array(
				        'Authorization: Bearer '.$token
				    ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				echo json_encode($response,JSON_UNESCAPED_UNICODE);
			
			}

		}else{
			
		}
	}
	//Registrar Cliente - Ventas
	if($_POST['action'] == 'addCliente'){
		if(!empty($_POST['dni_cliente'])){
			$dni = $_POST['dni_cliente'];
			$nombre = $_POST['nom_cliente'];
			$apellido_paterno = $_POST['ap_pat_cliente'];
			$apellido_materno= $_POST['ap_mat_cliente'];
			$usuario_id = $_SESSION['idUser'];
			$query_insert =mysqli_query($conexion,"INSERT INTO cliente(dni,nombres,apellido_paterno,apellido_materno,usuario_id) VALUES ('$dni', '$nombre','$apellido_paterno','$apellido_materno','$usuario_id')");
			if($query_insert){
				$codCliente = mysqli_insert_id($conexion);
				$msg = $codCliente;
				echo $msg;
			}else{
				$msg = 'error';
				echo $msg;
			}
		}else{
			$ruc = $_POST['ruc_cliente'];
			$razon_social = $_POST['raz_cliente'];
			$direccion = $_POST['dir_cliente'];
			$usuario_id = $_SESSION['idUser'];

			$query_insert =mysqli_query($conexion,"INSERT INTO cliente(ruc,razon_social,usuario_id,direccion) VALUES ('$ruc','$razon_social','$usuario_id','$direccion')");
			if($query_insert){
				$codCliente = mysqli_insert_id($conexion);
				$msg = $codCliente;
				echo $msg;
			}else{
				$msg = 'error';
				echo $msg;
			}
		}
		mysqli_close($conexion);
		exit;
	}
	//Agregar al Detalle
	if($_POST['action']=='addProductoDetalle'){
		if(empty ($_POST['producto']) || empty($_POST['cantidad']) || empty($_POST['precio']))
		{
			echo 'error';
		}else{
			$sku = $_POST['producto'];
			$cantidad = $_POST['cantidad'];
			$precio = $_POST['precio'];
			if(!empty($_POST['descuento'])){
			$descuento = $_POST['descuento'];
			}else{
			$descuento = 0;
			}
			$token = md5($_SESSION['idUser']);
			$query_cod = mysqli_query($conexion, "SELECT codproducto,precio_compra FROM producto WHERE sku = '$sku'");
			$cod = mysqli_fetch_array($query_cod);
			$codproducto = $cod['codproducto'];
			$precio_compra = $cod['precio_compra'];
			$query_igv = mysqli_query ($conexion, "SELECT igv FROM configuracion");
			$result_igv = mysqli_num_rows($query_igv);

			$query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp ($codproducto,$cantidad,'$token',$precio,$precio_compra,$sku)");
			$result = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total = 0;
			$igv = 0;
			$total = 0;
			$arrayData = array();

			if($result > 0){
				if($result_igv >0){
					$info_gv = mysqli_fetch_assoc($query_igv);
					$igv = $info_gv['igv'];
				}
				while($data = mysqli_fetch_assoc($query_detalle_temp)){
					$precioTotal = round($data['cantidad']*$data['precio_venta'],2);
					$sub_total = round($sub_total+$precioTotal,2);
					$total = round($total+$precioTotal,2);
					$detalleTabla .= '<tr class="row'.$data['codproducto'].'">
			          <td>'.$data['sku'].'</td>
			          <td colspan="2">'.$data['descripcion'].'</td>
			          <td class="textcenter">'.$data['cantidad'].'</td>
			          <td class="textright">'.$data['precio_venta'].'</td>
			          <td class="textright ">'.$precioTotal.'</td>
			          <td colspan="2" class="textcenter">
			          <a class="link-delete" href="#" onclick="event.preventDefault();del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i> Eliminar</a>
			        	</td>
			      	</tr>';
				}
				$tl_snigv = round($sub_total/ (1+ ($igv/100)),2);
				$impuesto = round($sub_total - $tl_snigv, 2);
				$total = round($tl_snigv+$impuesto, 2);

				$detalleTotales ='<tr>
			        <td colspan="7" class="textright">SUBTOTAL</td>
			        <td class=" textright">'.$tl_snigv.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">IGV('.$igv.'%)</td>
			        <td class=" textright">'.$impuesto.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">DESCUENTO</td>
			        <td class=" textright"><input type="number" name="descuento" class="inpPrecio descuento" id="descuento" value="'.$descuento.'" onchange="actualizarDescuento(this)" ></td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">TOTAL</td>
			        <td class="montotal textright" id="'.$total.'">'.$total.'</td>
			     </tr>';

			     $arrayData['detalle'] = $detalleTabla;
			     $arrayData['totales'] = $detalleTotales;

			     echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";

			}
			mysqli_close($conexion);
			exit;
		}
	}
	//Extraer ID User
	if($_POST['action']=='searchForDetalle'){
		if(!empty($_POST['descuento'])){
			$descuento = $_POST['descuento'];
		}else{
			$descuento = 0;
		}
		if(empty($_POST['user'])){
			echo 'error';
		}else{
			$token = md5($_SESSION['idUser']);
			$query = mysqli_query($conexion, "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE token_user = '$token'");
		$result = mysqli_num_rows($query);

		$query_igv = mysqli_query ($conexion, "SELECT igv FROM configuracion");
		$result_igv = mysqli_num_rows($query_igv);
		
		$detalleTabla = '';
		$sub_total = 0;
		$igv = 0;
		$total = 0;
		$arrayData = array();

		if($result > 0){
			if($result_igv >0){
				$info_gv = mysqli_fetch_assoc($query_igv);
				$igv = $info_gv['igv'];
			}
			while($data =mysqli_fetch_assoc($query)){
					$codproducto = $data['codproducto'];
					$query_sku = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $codproducto");
					$sku = mysqli_fetch_array($query_sku);

					$precioTotal = round($data['cantidad']*$data['precio_venta'],2);
					$sub_total = round($sub_total+$precioTotal,2);
					$total = round($total+$precioTotal,2);

					$detalleTabla .= '<tr class="row'.$data['codproducto'].'">
			          <td>'.$sku['sku'].'</td>
			          <td colspan="2">'.$data['descripcion'].'</td>
			          <td class="textcenter">'.$data['cantidad'].'</td>
			          <td class="textright">'.$data['precio_venta'].'</td>
			          <td class="textright ">'.$precioTotal.'</td>
			          <td colspan="2" class="textcenter">
			          <a class="link-delete" href="#" onclick="event.preventDefault();del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i> Eliminar</a>
			        	</td>
			      	</tr>';
				}

				$tl_snigv = round($sub_total/ (1+ ($igv/100)),2);
				$impuesto = round($sub_total - $tl_snigv, 2);
				$total = round($tl_snigv+$impuesto, 2);

				$detalleTotales ='<tr>
			        <td colspan="7" class="textright">SUBTOTAL</td>
			        <td class=" textright">'.$tl_snigv.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">IGV('.$igv.'%)</td>
			        <td class=" textright">'.$impuesto.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">DESCUENTO</td>
			        <td class=" textright"><input type="number" name="descuento" class="inpPrecio descuento" id="descuento" value="'.$descuento.'" onchange="actualizarDescuento(this)" ></td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">TOTAL</td>
			        <td class="montotal textright" id="'.$total.'">'.$total.'</td>
			     </tr>';

			     $arrayData['detalle'] = $detalleTabla;
			     $arrayData['totales'] = $detalleTotales;

			     echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";

			}
			mysqli_close($conexion);
			exit;
		}
	}
	if($_POST['action']=='delProductoDetalle'){
		if(!empty($_POST['descuento'])){
			$descuento = $_POST['descuento'];
		}else{
			$descuento = 0;
		}
		if(empty($_POST['id_detalle'])){
			echo 'error';
		}else{
			$id_detalle = $_POST['id_detalle'];
			$token = md5($_SESSION['idUser']);
			$query_igv = mysqli_query ($conexion, "SELECT igv FROM configuracion");
			$result_igv = mysqli_num_rows($query_igv);

			$query_detalle_temp = mysqli_query($conexion, "CALL del_detalle_temp ($id_detalle,'$token')");
			$result = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total = 0;
			$igv = 0;
			$total = 0;
			$arrayData = array();

			if($result > 0){
				if($result_igv >0){
					$info_gv = mysqli_fetch_assoc($query_igv);
					$igv = $info_gv['igv'];
				}
				while($data = mysqli_fetch_assoc($query_detalle_temp)){
					$codproducto = $data['codproducto'];
					$precioTotal = round($data['cantidad']*$data['precio_venta'],2);
					$sub_total = round($sub_total+$precioTotal,2);
					$total = round($total+$precioTotal,2);

					$detalleTabla .= '<tr class="row'.$data['codproducto'].'">
								          <td>'.$data['sku'].'</td>
								          <td colspan="2">'.$data['descripcion'].'</td>
								          <td class="textcenter">'.$data['cantidad'].'</td>
								          <td class="textright">'.$data['precio_venta'].'</td>
								          <td class="textright ">'.$precioTotal.'</td>
								          <td colspan="2" class="textcenter">
									          <a class="link-delete" href="#" onclick="event.preventDefault();del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i> Eliminar</a>
									        	</td>
								      	</tr>';
				}

				$tl_snigv = round($sub_total/ (1+ ($igv/100)),2);
				$impuesto = round($sub_total - $tl_snigv, 2);
				$total = round($tl_snigv+$impuesto, 2);

				$detalleTotales ='<tr>
			        <td colspan="7" class="textright">SUBTOTAL</td>
			        <td class=" textright">'.$tl_snigv.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">IGV('.$igv.'%)</td>
			        <td class=" textright">'.$impuesto.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">DESCUENTO</td>
			        <td class=" textright"><input type="number" name="descuento" class="inpPrecio descuento" id="descuento" value="'.$descuento.'" onchange="actualizarDescuento(this)" ></td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">TOTAL</td>
			        <td class="montotal textright" id="'.$total.'">'.$total.'</td>
			     </tr>';

			     $arrayData['detalle'] = $detalleTabla;
			     $arrayData['totales'] = $detalleTotales;

			     echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";

			}
			mysqli_close($conexion);
			exit;
		}
	}
	//Actualizar Total
	if($_POST['action']=='actualizarTotal'){
		$token = md5($_SESSION['idUser']);
		$met = $_POST['met'];
		$query = mysqli_query($conexion, "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE token_user = '$token'");
		$result = mysqli_num_rows($query);
		$query_igv = mysqli_query ($conexion, "SELECT igv FROM configuracion");
		$result_igv = mysqli_num_rows($query_igv);
		
		$detalleTabla = '';
		$sub_total = 0;
		$igv = 0;
		$total = 0;
		$arrayData = array();

		if($result > 0){
			if($result_igv >0){
				$info_gv = mysqli_fetch_assoc($query_igv);
				$igv = $info_gv['igv'];
			}
			while($data =mysqli_fetch_assoc($query)){
					$codproducto = $data['codproducto'];
					$query_sku = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $codproducto");
					$sku = mysqli_fetch_array($query_sku);
					$correlativo = $data['correlativo'];
					if($met=='tarjeta'){
						$precio_venta = $data['precio_venta'];
						$precio_venta = round($precio_venta+$precio_venta*0.05,2);
						$precioTotal  = round($data['cantidad']*$precio_venta,2);
						$query_update =mysqli_query($conexion,"UPDATE detalle_temp SET precio_venta = $precio_venta WHERE correlativo = $correlativo");

					}else{
						$precio_venta = $data['precio_venta'];
						$precio_venta = round($precio_venta/1.05,2);
						$precioTotal  = round($data['cantidad']*$precio_venta,2);
						$query_update = mysqli_query($conexion,"UPDATE detalle_temp SET precio_venta = $precio_venta WHERE correlativo = $correlativo");
					}
					$sub_total = round($sub_total+$precioTotal,2);
					$total = round($total+$precioTotal,2);

					$detalleTabla .= '<tr class="row'.$data['codproducto'].'">
			          <td>'.$sku['sku'].'</td>
			          <td colspan="2">'.$data['descripcion'].'</td>
			          <td class="textcenter">'.$data['cantidad'].'</td>
			          <td class="textright">'.$precio_venta.'</td>
			          <td class="textright ">'.$precioTotal.'</td>
			          <td colspan="2" class="textcenter">
			          <a class="link-delete" href="#" onclick="event.preventDefault();del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i> Eliminar</a>
			        	</td>
			      	</tr>';
				}

				$tl_snigv = round($sub_total/ (1+ ($igv/100)),2);
				$impuesto = round($sub_total - $tl_snigv, 2);
				$total = round($tl_snigv+$impuesto, 2);

				$detalleTotales ='<tr>
			        <td colspan="7" class="textright">SUBTOTAL</td>
			        <td class=" textright">'.$tl_snigv.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">IGV(18%)</td>
			        <td class=" textright">'.$impuesto.'</td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">DESCUENTO</td>
			        <td class=" textright"><input type="number" name="descuento" class="inpPrecio descuento" id="descuento" value="'.$descuento.'" onchange="actualizarDescuento(this)" ></td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">TOTAL</td>
			        <td class="montotal textright" id="'.$total.'">'.$total.'</td>
			     </tr>';

			     $arrayData['detalle'] = $detalleTabla;
			     $arrayData['totales'] = $detalleTotales;

			     echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";

			}
	}
	//Anular Venta
	if($_POST['action'] =='anularVenta'){		
		$token = md5($_SESSION['idUser']);
		$query_del = mysqli_query($conexion,"DELETE FROM detalle_temp WHERE token_user = '$token'");
		
		if($query_del){
			echo 'ok';
		}else{
			echo 'error';
		}
		exit;
	}
	//Procesar Venta
	if($_POST['action'] =='procesarVenta'){
		include "sendXml.php";
		$token = md5($_SESSION['idUser']);
		$usuario = $_SESSION['idUser'];
		$codcliente = $_POST['codcliente'];
		if(!empty($_POST['desc'])){	
			$descuento = $_POST['desc'];
			$descuento_igv = round($descuento/1.18,2);
		}else{
			$descuento = 0;
		}
		$query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
		$result = mysqli_num_rows($query);
		if($_POST['tipo']=='Boleta'){
			$codigoDoc = '03';
		}else{
			$codigoDoc = '01';
		}
		$formaPago = $_POST['pago'];
		if($formaPago =='contado'){
			$cuotas= 0;
		}else{
			$cuotas = $_POST['cuotas'];
		}

		$query_cliente = mysqli_query($conexion,"SELECT * FROM cliente WHERE idcliente = $codcliente");
		$data_cliente = mysqli_fetch_assoc($query_cliente);
		if(!empty($data_cliente['ruc'])){
			$docCliente = $data_cliente['ruc'];
			$nombreCliente = $data_cliente['razon_social']; 
		}else{
			$docCliente = $data_cliente['dni'];
			$nombreCliente = $data_cliente['nombres'].' '.$data_cliente['apellido_paterno'].' '.$data_cliente['apellido_materno'];
		}
		if($result>0){
			$query_procesar = mysqli_query($conexion, "CALL procesar_venta($usuario,$codcliente,'$token',$descuento)");
			while(mysqli_next_result($conexion)){;}
			$result_detalle = mysqli_num_rows($query_procesar);
				if($result_detalle >0){
				$data = mysqli_fetch_assoc($query_procesar);
				$nofactura = $data['nofactura'];
				if($codigoDoc == '03'){
				$repetidor = $nofactura-$nofactura+1;
				$false = false;
					while(!$false){
						$query_b = mysqli_query($conexion,"SELECT * FROM factura WHERE b = $repetidor");
						$num_b = mysqli_num_rows($query_b);
						if($num_b==0){
							$false = true;
							$correlativo = $repetidor;
							mysqli_query($conexion,"UPDATE factura SET b = $correlativo WHERE nofactura = $nofactura");
						}else{
						$repetidor+=1;
						}
					}
				}else{
					$repetidor = $nofactura-$nofactura+1;
					$false = false;
					while(!$false){
						$query_f = mysqli_query($conexion,"SELECT * FROM factura WHERE f = $repetidor");
						$num_f = mysqli_num_rows($query_f);
						if($num_f==0){
							$false = true;
							$correlativo = $repetidor;
							mysqli_query($conexion,"UPDATE factura SET f = $correlativo WHERE nofactura = $nofactura");
						}else{
						$repetidor+=1;
						}
				}
				}
				$nombre_comercial = 'JG MOTOS';
				$razon_social = 'JG MOTOS SOCIEDAD ANONIMA CERRADA';
				$rucVendedor = '20600566289';
				while(mysqli_next_result($conexion)){;}
				$query_factura = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = $nofactura");
				$data_venta = mysqli_fetch_assoc($query_factura);
				$totalfactura = $data_venta['totalfactura'];
				$montoImpuestos = round($totalfactura-$totalfactura/1.18,2);
				$totalSinImpuestos = $totalfactura-$montoImpuestos;
				$hash = sendXML($docCliente,$nombreCliente,$rucVendedor,$razon_social,$nombre_comercial,$formaPago,$cuotas,$montoImpuestos,$totalSinImpuestos,$descuento_igv,$totalfactura,$correlativo,$nofactura,$conexion);
				mysqli_query($conexion,"UPDATE factura SET hash = '$hash' WHERE nofactura = $nofactura");
				mysqli_query($conexion,"UPDATE factura SET metodo_pago ='$formaPago' WHERE nofactura = $nofactura");
				echo json_encode($data,JSON_UNESCAPED_UNICODE);

			}else{
				echo "error";
			}
		}else{
			echo "error";
		}
		mysqli_close($conexion);
	}
	//Procesar Proforma
	if($_POST['action'] =='procesarProforma'){
		$token = md5($_SESSION['idUser']);
		$usuario = $_SESSION['idUser'];
		$codcliente = $_POST['codcliente'];
		$query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
		$result = mysqli_num_rows($query);

		if($result>0){
			$query_procesar = mysqli_query($conexion, "CALL procesar_proforma($usuario,$codcliente,'$token')");
			$result_detalle = mysqli_num_rows($query_procesar);
			if($result_detalle >0){
				$data = mysqli_fetch_assoc($query_procesar);
				echo json_encode($data,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";
			}
		}else{
			echo "error";
		}
		mysqli_close($conexion);
	}
	//Info Factura
	if($_POST['action'] == 'infoFactura'){
		if(!empty($_POST['nofactura'])){
			$nofactura = $_POST['nofactura'];

			$query = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = '$nofactura' AND estatus =1");
			$result = mysqli_num_rows($query);
			if($result >0){
				$data = mysqli_fetch_assoc($query);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		echo "error";
		exit;
	}
	//Anular Factura
	if($_POST['action']=='anularFactura'){
		include "cancelXML.php";
		if(!empty($_POST['noFactura'])){
		$noFactura = $_POST['noFactura'];
		$query_anular = mysqli_query($conexion,"CALL anular_factura($noFactura)");
		$result = mysqli_num_rows($query_anular);
		if($result >0){
			$data = mysqli_fetch_assoc($query_anular);
			$nombre_comercial = 'JG MOTOS';
			$razon_social = 'JG MOTOS SOCIEDAD ANONIMA CERRADA';
			$rucVendedor = '20600566289';
			while(mysqli_next_result($conexion)){;}
			$query_factura = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = $noFactura");
			$data_venta = mysqli_fetch_assoc($query_factura);
			$codcliente = $data_venta['codcliente'];
			$query_cliente = mysqli_query($conexion,"SELECT * FROM cliente WHERE idcliente = $codcliente");
			while(mysqli_next_result($conexion)){;}
			$data_cliente = mysqli_fetch_assoc($query_cliente);
			if(!empty($data_cliente['ruc'])){
				$docCliente = $data_cliente['ruc'];
				$nombreCliente = $data_cliente['razon_social']; 
			}else{
				$docCliente = $data_cliente['dni'];
				$nombreCliente = $data_cliente['nombres'].' '.$data_cliente['apellido_paterno'].' '.$data_cliente['apellido_materno'];
			}
			if($data_venta['b']!=0){
				$correlativo = $data_venta['b'];
				$repetidor = 1;
				$false = false;
				while(!$false){
					$query_b = mysqli_query($conexion,"SELECT * FROM nota_credito WHERE boleta = $repetidor");
					$num_b = mysqli_num_rows($query_b);
					if($num_b==0){
						$false = true;
						$correlativoNC = $repetidor;
						mysqli_query($conexion,"INSERT INTO nota_credito (boleta,nofactura) VALUES ($correlativoNC,$noFactura)");
					}else{
						$repetidor+=1;
					}
				}
			}else{
				$correlativo = $data_venta['f'];
				$repetidor = 1;
				$false = false;
				while(!$false){
					$query_f = mysqli_query($conexion,"SELECT * FROM nota_credito WHERE factura = $repetidor");
					$num_f = mysqli_num_rows($query_f);
					if($num_f==0){
						$false = true;
						$correlativoNC = $repetidor;
						mysqli_query($conexion,"INSERT INTO nota_credito (factura,nofactura) VALUES ($correlativoNC,$noFactura)");
					}else{
						$repetidor+=1;
					}
				}
			}
			$totalfactura = $data_venta['totalfactura'];
			$montoImpuestos = round($totalfactura-$totalfactura/1.18,2);
			$totalSinImpuestos = $totalfactura-$montoImpuestos;
			sendNotaCredito($docCliente,$nombreCliente,$rucVendedor,$razon_social,$nombre_comercial,$montoImpuestos,$totalSinImpuestos,$totalfactura,$correlativo,$correlativoNC,$noFactura,$conexion);
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
			exit;
			}
		}
		echo "error";
		exit;
	}
	//Ver Ubicacion de Venta
	if($_POST['action']=='verDetalle'){
		if(!empty($_POST['codCliente']) && !empty($_POST['noFactura'])){
			$codCliente = $_POST['codCliente'];
			$noFactura = $_POST['noFactura'];
			$arrayData = array();
			$tabla = '';
			$query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.sku, u.ubicacion, dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total FROM factura f INNER JOIN detallefactura dt ON f.nofactura = dt.nofactura INNER JOIN producto p ON dt.codproducto = p.codproducto INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE f.nofactura = $noFactura");
			$result_detalle = mysqli_num_rows($query_productos);
			if($result_detalle > 0){
				while ($data_productos = mysqli_fetch_assoc($query_productos)){
						$codproducto = $data_productos['codproducto'];
						$query_sku = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $codproducto");
						$sku = mysqli_fetch_array($query_sku);
						$data_productos['sku'] = $sku['sku'];
						$descripcion = $data_productos['descripcion'];
						$ubicacion = $data_productos['ubicacion'];
						$cantidad = $data_productos['cantidad'];
						$precio_venta = $data_productos['precio_venta'];
						$precio_total = $data_productos['precio_total'];
						$tabla.='<tr>
												<td>'.$data_productos['sku'].'</td>
												<td>'.$data_productos['descripcion'].'</td>
												<td>'.$data_productos['ubicacion'].'</td>
												<td class="celPrecio">'.$data_productos['precio_venta'].'</td>
												<td class="celExistencia">'.$data_productos['cantidad'].'</td> 
												<td class="celExistencia">'.$data_productos['precio_total'].'</td> 
											</tr>';

				}
						$arrayData['tabla']=$tabla;
						echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}exit;
		}
	}
	//Proforma a Venta
	if($_POST['action']=='seguirVenta'){
		if(empty($_POST['codcliente'])&& empty($_POST['noproforma'])){
			echo 'error';
		}else{
				$token = md5($_SESSION['idUser']);
				$query_del = mysqli_query($conexion,"DELETE FROM detalle_temp WHERE token_user = '$token'");
				$codCliente = $_POST['codcliente'];
				$noProforma = $_POST['noproforma'];
				$arrayData = array();
				$tabla = '';
				
				$query_update = mysqli_query($conexion, "UPDATE proforma SET estatus = 2 WHERE noproforma = $noProforma");
				$query_cliente = mysqli_query($conexion,"SELECT * from cliente WHERE idcliente = $codCliente");
				$data_cliente = mysqli_fetch_assoc($query_cliente);

				$query_igv = mysqli_query ($conexion, "SELECT igv FROM configuracion");
				$result_igv = mysqli_num_rows($query_igv);
				$detalleTabla = '';
				$query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.precio_compra,p.sku, dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total FROM proforma f INNER JOIN detalleproforma dt ON f.noproforma = dt.noproforma INNER JOIN producto p ON dt.codproducto = p.codproducto WHERE f.noproforma = $noProforma");
				$result_detalle = mysqli_num_rows($query_productos);
				$sub_total = 0;
				$igv = 0;
				$total = 0;
				$arrayData = array();

				if($result_detalle > 0){
					if($result_igv >0){
						$info_gv = mysqli_fetch_assoc($query_igv);
						$igv = $info_gv['igv'];
					}
					while ($data = mysqli_fetch_assoc($query_productos)){
						$codproducto = $data['codproducto'];
						$sku = $data['sku'];
						$cantidad = $data['cantidad'];
						$precio = $data['precio_venta'];
						$precio_compra = $data['precio_compra'];
						$query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp ($codproducto,$cantidad,'$token',$precio,$precio_compra,$sku)");
						while(mysqli_next_result($conexion)){;}
						$data_temp = mysqli_fetch_assoc($query_detalle_temp);
							$precioTotal = round($data['cantidad']*$data['precio_venta'],2);
							$sub_total = round($sub_total+$precioTotal,2);
							$total = round($total+$precioTotal,2);
							$detalleTabla .= '<tr>
					          <td>'.$data['sku'].'</td>
					          <td colspan="2">'.$data['descripcion'].'</td>
					          <td class="textcenter">'.$data['cantidad'].'</td>
					          <td class="textright">'.$data['precio_venta'].'</td>
					          <td class="textright ">'.$precioTotal.'</td>
					          <td colspan="2" class="textcenter">
					          <a class="link-delete" href="#" onclick="event.preventDefault();del_product_detalle('.$data_temp['correlativo'].');"><i class="fa fa-trash"></i> Eliminar</a>
					        	</td>
					      	</tr>';
						
					}
					$tl_snigv = round($sub_total/ (1+ ($igv/100)),2);
					$impuesto = round($sub_total - $tl_snigv, 2);
					$total = round($tl_snigv+$impuesto, 2);

					$detalleTotales ='<tr>
				        <td colspan="7" class="textright">SUBTOTAL</td>
				        <td class=" textright">'.$tl_snigv.'</td>
				      </tr>
				      <tr>
				        <td colspan="7" class="textright">IGV('.$igv.'%)</td>
				        <td class=" textright">'.$impuesto.'</td>
				      </tr>
				      <tr>
			        <td colspan="7" class="textright">DESCUENTO</td>
			        <td class=" textright"><input type="number" name="descuento" class="inpPrecio descuento" id="descuento" value="'.$descuento.'" onchange="actualizarDescuento(this)" ></td>
			      </tr>
			      <tr>
			        <td colspan="7" class="textright">TOTAL</td>
			        <td class="montotal textright" id="'.$total.'">'.$total.'</td>
			     </tr>';
				    $arrayData['dni'] = $data_cliente['dni']; 
				    $arrayData['nombre'] = $data_cliente['nombres'];
				    $arrayData['apellido_paterno'] = $data_cliente['apellido_paterno'];
				    $arrayData['apellido_materno'] = $data_cliente['apellido_materno'];
					$arrayData['ruc'] =	$data_cliente['ruc']; 
				    $arrayData['razon_social'] = $data_cliente['razon_social'];
				    $arrayData['idcliente'] = $data_cliente['idcliente'];
				    $arrayData['detalle'] = $detalleTabla;
				    $arrayData['totales'] = $detalleTotales;


				    echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
				}
			mysqli_close($conexion);
			exit;
		}
					
	}
	//Info de la Foto a Eliminar
	if($_POST['action'] == 'infoFoto'){
		if(!empty($_POST['idFoto']) && !empty($_POST['idProducto'])){
			$idFoto = $_POST['idFoto'];
			$idProducto = $_POST['idProducto'];
			$query_fotos = mysqli_query($conexion, "SELECT * FROM foto WHERE idproducto = $idProducto");
			$result_fotos = mysqli_num_rows($query_fotos);
			if($result_fotos >= $_POST['idFoto']){
				$idFoto = $idFoto-1;
				$query_producto = mysqli_query($conexion, "SELECT * FROM foto WHERE idproducto = $idProducto ORDER BY idFoto ASC LIMIT $idFoto,1");
				$data = mysqli_fetch_assoc($query_producto);
				$data['num'] = $idFoto+1;

				echo json_encode($data,JSON_UNESCAPED_UNICODE);
			}else{
				echo "error";
				exit;
			}
		}else{
			echo "error";
		}
		
	}
	//Eliminar Foto
	if($_POST['action'] == 'eliminarFoto'){
		if(empty($_POST['idFoto'])){
			echo 'error';
		}else{
			$idFoto = $_POST['idFoto'];
			$query_delete = mysqli_query($conexion, "DELETE FROM foto WHERE idfoto = $idFoto");
			mysqli_close($conexion);
			if($query_delete){
				echo 'ok';
			}else{
				echo 'error';
				}
			}
		}
}

exit;
?>