<?php
	//Comprobar sesión activa
	session_start();
  include "../conexion.php";


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/scripts.php"; ?>
	<title>Registro de Venta</title>
</head>
<body>
	<?php include "include/header.php"; ?>
	<section id="container">
    <div class="title_page">
      <h1><i class="fas fa-cube"></i> Nueva Venta</h1>
    </div>
    <div class="datos_cliente">
      <div class="action_cliente">
        <h4>Datos del Cliente</h4>
        <a href="#" class="btn-new btn_new_cliente" style="text-align: center;"><i class="fas fa-plus"></i> Nuevo Cliente</a>
        <a href="#" class="btn-save btn_search_cliente" style="text-align: center;"><i class="fas fa-search"></i> Buscar Cliente</a>
        <select class="bol_fac"onchange="bol_fac();">
          <option>Boleta</option>
          <option>Factura</option>
        </select>
        </div>
      <form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
        <input type="hidden" name="action" value="addCliente">
        <input type="hidden" name="idcliente" id="idcliente" value="" required>  
        <div class="wd30">
          <label>DNI </label>
          <input type="text" class="dni_cliente" name="dni_cliente" id="dni_cliente" onkeyup="searchCliente()">
        </div>
        <div class="wd30">
          <label>Nombre</label>
          <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
        </div>
        <div class="wd30">
          <label>Ap. Paterno</label>
          <input type="text" name="ap_pat_cliente" id="ap_pat_cliente" disabled required>
        </div>
        <div class="wd50">
          <br>
          <label>Ap. Materno</label>
          <input type="text" name="ap_mat_cliente" id="ap_mat_cliente" disabled required>
        </div>
        <div id="div_registro_cliente" class="wd50">
          <br>
          <button type="submit" class="btn-save"><i class="far fa-save"></i> Guardar</button>
        </div>
        </form>
    </div>
    <div class="datos_venta">
      <h4>Datos de Venta</h4>
      <div class="datos">
        <div class="wd50">
          <label>Vendedor</label>
          <p><?php echo $_SESSION['nombre']; ?></p>
          <div class="div-cuotas">
            <label>
              Cuotas: 
            </label>
            <input type="number" class="cuotas" name="cuotas" id="cuotas" min="1">
          </div>
        </div>
        <div class="wd50">
          <label>Acciones</label>
          <div id="acciones_venta">
            <a href="#" class="btn-ok textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
            <a href="#" class="btn-new textcenter" id="btn_facturar_venta"><i class="far fa-edit"></i> Procesar</a>
            <a href="#" class="btn-cot textcenter" id="btn_cotizar_venta"><i class="fa-regular fa-paste"></i> Proforma</a>
            <label>Tarjeta:</label>
            <div style="float: right; padding-right: 100px;">
                <input class="input_dark" type="checkbox" id="pago_tarj" name="pago_tarj" onchange="checkPago(this)">
                <label class="label_dark" for="pago_tarj">
                  <i class="fa-regular fa-credit-card" ></i>
                  <i class="fa-solid fa-money-bill"></i>
                </label>       
            </div> 
          </div>
        </div>
      </div>
    </div>
    <table class="table-list tbl_productos display" id="tbl_venta" >
    <thead>  
    <tr>
        <th>Sku</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Existencia</th>
        <th>Proveedor</th>
        <th>Foto</th>
        <th>Acciones</th>
      </tr>
  </thead>
      <?php 
        $query = mysqli_query($conexion,"SELECT p.codproducto,p.sku,u.ubicacion, p.descripcion, p.precio, p.existencia, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE p.estatus = 1 ORDER BY p.codproducto ASC");
        $result = mysqli_num_rows($query);

        if($result > 0){
          while ($data = mysqli_fetch_array($query)) {
            $codproducto = $data['codproducto'];
            $query_foto = mysqli_query($conexion, "SELECT idproducto,foto,idfoto FROM foto WHERE idproducto = $codproducto");
            $data_foto = mysqli_fetch_array($query_foto);
            if($data_foto['foto'] != 'img_producto.png'){
              $foto = 'img/uploads/'.$data_foto['foto'];
            }else{
              $foto = 'img/'.$data_foto['foto'];
            }

        ?>
        <tr class="tab<?php echo $data['codproducto']; ?>">
          <td class="sku"><?php echo $data['sku']; ?></td>
          <td class="celDescripcion"><?php echo $data['descripcion']; ?></td>
          <td class="celPrecio"><input class="inpPrecio precio" type="number" name="inpPrecio" value="<?php echo $data['precio']; ?>"></td>
          <?php if($data['existencia']<=3){?>
            <td class="celExistencia" style="color:red;"><?php echo $data['existencia']; ?></td>
          <?php
          }else{?>
          <td class="celExistencia"><?php echo $data['existencia']; ?></td> <?php } ?>
          <td><?php echo $data['proveedor']; ?></td>
          <td class="img-producto"><a href="info_product.php?id=<?php echo $data['codproducto']; ?>"><img src="<?php echo $foto;?>" alt="<?php echo $data['descripcion'];?>"style="width: 60px; height: auto; margin:auto;"></a></td>
          <td>
            <div class="div-acciones">
              <div>
              <input type="number" class="inp_cantidad cant" name="cantidad" min="0" step="1" value="0" id="<?php echo $data['codproducto']; ?>"></div>
              <div class="div-addcart">
          <button class="btn-view add_cart inactivo" onclick="add_cart(<?php echo $data['codproducto']; ?>);"type="button" disabled><i class="fa fa-cart-shopping" style="font-size:18px;"></i></button></div>
          </div>
          </td>
          </tr>
      <?php
        }
      }
      ?>
    </table>
  
    <table class="table-venta">
      <thead>
        <tr>
          <th>Sku</th>
          <th colspan="2">Descripción</th>
          <th>Cantidad</th>
          <th class="textright">Precio</th>
          <th class="textright ">Precio Total</th>
          <th colspan="2" > Acción</th>
        </tr>
      </thead>
      <tbody id="detalle_venta">
        
      </tbody>
      <tfoot id="detalle_totales">
      </tfoot>     
    </table>
  </section>
  <script>
    /* Initialization of datatables */
    $(document).ready(function () {
      $('table.display').DataTable();
    });
</script>
  <script type="text/javascript">
    $(document).ready(function(){
      var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
      searchForDetalle(usuarioid);
    });
  </script>	
  <?php
  if(!empty($_REQUEST['cl']) && !empty($_REQUEST['f'])){
    $codCliente = $_REQUEST['cl'];
    $noProforma = $_REQUEST['f'];
    ?>
    <script type="text/javascript">
    $(document).ready(function(){
    var codcliente = '<?php echo $codCliente; ?>';
    var noproforma = '<?php echo $noProforma; ?>';
    proformaVenta(codcliente,noproforma);
      });
    </script> 
    
  <?php }
  ?>
</body>
</html>