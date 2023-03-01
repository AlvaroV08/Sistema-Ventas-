<?php 
use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Note;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;
use Luecano\NumeroALetras\NumeroALetras;
//Definir Hora Local
date_default_timezone_set('America/Lima');
//Requires
require_once dirname(__DIR__).'/vendor/autoload.php';

include "chargeProducts.php";
include 'util.php';

//Método Anular Boleta O Factura
//sendNotaCredito('73946653','Alvaro Eduardo Vilca Lengua','20600566289','JG MOTOS SOCIEDAD ANONIMA CERRADA','JG MOTOS',717.7,3987.2,4704.9,'06','01',66,$conexion);
function sendNotaCredito($docCliente,$cliente,$ruc,$razon_social,$nombre_comercial,$montoImpuestos,$totalSinImpuestos,$total,$correlativo,$correlativoNC,$nofactura,$conexion){
$fecha = date('Y-m-d h:i:s');
$date = date('Y-m-d');



$util = Util::getInstance();

if(strlen($docCliente)!=8){
	$client = (new Client())
    ->setTipoDoc('6')
    ->setNumDoc($docCliente)
    ->setRznSocial($cliente);        
    $recibo='01';
    $serie='F001';
    $serieNC='FF01';
}else{
	$client = (new Client())
    ->setTipoDoc('1')
    ->setNumDoc($docCliente)
    ->setRznSocial($cliente);
    $recibo='03';
    $serie='B001';
    $serieNC='BB01';
 }

// Emisor
$address = (new Address())
	->setUbigueo('150101')
    ->setDepartamento('ICA')
    ->setProvincia('ICA')
    ->setDistrito('ICA')
    ->setUrbanizacion('-')
    ->setDireccion('AV. JOSE MATIAS MANZANILLA NRO. 750 URB. SAN MIGUEL')
    ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

$company = (new Company())
    ->setRuc($ruc)
    ->setRazonSocial($razon_social)
    ->setNombreComercial($nombre_comercial)
    ->setAddress($address);

$note = new Note();
$note
    ->setUblVersion('2.1')
    ->setTipoDoc('07') // Tipo Doc: Nota de Credito
    ->setSerie($serieNC) // Serie NCR
    ->setCorrelativo($correlativoNC) // Correlativo NCR
    ->setFechaEmision(new DateTime())
    ->setTipDocAfectado($recibo) // Tipo Doc: Boleta
    ->setNumDocfectado($serie.'-'.$correlativo) // Boleta: Serie-Correlativo
    ->setCodMotivo('01') // Catalogo. 09
    ->setDesMotivo('ANULACION DE LA OPERACION')
    ->setTipoMoneda('PEN')
    ->setCompany($company)
    ->setClient($client)
    ->setMtoOperGravadas($totalSinImpuestos)
    ->setMtoIGV($montoImpuestos)
    ->setTotalImpuestos($montoImpuestos)
    ->setMtoImpVenta($total)
    ;
$items = chargeProducts($nofactura,$conexion);
    
$formatter = new NumeroALetras();
$totalTexto = 'SON '.$formatter->toInvoice($total, 2, 'soles');
    
$note->setDetails($items)
    ->setLegends([
    (new Legend())
        ->setCode('1000')
        ->setValue($totalTexto)
    ]);
// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($note);
$util->writeXml($note, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    exit();
}

/**@var $res BillResult*/
$cdr = $res->getCdrResponse();
$util->writeCdr($note, $res->getCdrZip());

$util->showResponse($note, $cdr);
}

?>