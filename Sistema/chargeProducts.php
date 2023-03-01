<?php 
//Cargar Productos
use Greenter\Model\Sale\SaleDetail;
function chargeProducts($nofactura,$conexion){
$query_productos = mysqli_query($conexion,"SELECT p.codproducto, p.descripcion, p.sku, u.ubicacion, dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total FROM factura f INNER JOIN detallefactura dt ON f.nofactura = dt.nofactura INNER JOIN producto p ON dt.codproducto = p.codproducto INNER JOIN ubicacion u ON p.ubicacion = u.idubicacion WHERE f.nofactura = $nofactura");
$result_detalle = mysqli_num_rows($query_productos);
$x = 0;
$p = 1;
        
    if($result_detalle > 0){
        while ($data_productos = mysqli_fetch_assoc($query_productos)){
            $unidSIGV = round($data_productos['precio_venta']/1.18,2);
            $totalSIGV = $unidSIGV*$data_productos['cantidad'];
            $impTotal = round($data_productos['precio_total']-$data_productos['precio_total']/1.18,2);
            $item1 = (new SaleDetail())
            ->setCodProducto('P00'.$p)
            ->setUnidad('NIU')
            ->setDescripcion($data_productos['descripcion'])
            ->setCantidad($data_productos['cantidad'])
            ->setMtoValorUnitario($unidSIGV)//Monto unitario sin IGV
            ->setMtoValorVenta($totalSIGV)//Monto de venta sin IGV
            ->setMtoBaseIgv($totalSIGV)
            ->setPorcentajeIgv(18)
            ->setIgv($impTotal)
            ->setTipAfeIgv('10') // Catalog: 07
            ->setTotalImpuestos($impTotal)
            ->setMtoPrecioUnitario($data_productos['precio_venta'])//Monto unitario con IGV
            ;
                                            
            $productos[$x] = $item1;
            $x = $x+1;
            $p = $p+1;
            }
        }
        return $productos;
}
?>   