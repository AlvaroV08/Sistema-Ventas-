<?php
include "../sistema/config.php";
require_once $ruta_vendor.'/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->setPaper('b7', 'portrait');
ob_start();
include "./generar_ticket.php";
$html = ob_get_clean();
$dompdf->loadHtml($html);
$dompdf->render();
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=documento.pdf");
echo $dompdf->output();
?>