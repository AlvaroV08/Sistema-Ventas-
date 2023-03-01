<?php 

	//Greenter Generador de XML
    use Greenter\Model\Response\BillResult;
    use Greenter\Model\Client\Client;
	use Greenter\Model\Company\Company;
	use Greenter\Model\Company\Address;
    use Greenter\Model\Sale\Cuota;
    use Greenter\Model\Sale\Charge;
	use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
    use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
	use Greenter\Model\Sale\Invoice;
	use Greenter\Model\Sale\SaleDetail;
	use Greenter\Model\Sale\Legend;
	use Greenter\Ws\Services\SunatEndpoints;
	use Greenter\See;
    //Numeros a Letras
    use Luecano\NumeroALetras\NumeroALetras;
    //Definir Hora Local
    date_default_timezone_set('America/Lima');
    //Requires
	require_once dirname(__DIR__).'/vendor/autoload.php';


    include "chargeProducts.php";
    include "util.php";

    //Metodo EnviarXML
    function sendXML($docCliente,$cliente,$ruc,$razon_social,$nombre_comercial,$formaPago,$cuotas,$montoImpuestos,$totalSinImpuestos,$descuento,$total,$correlativo,$nofactura,$conexion){
    $fecha = date('Y-m-d h:i:s');
    $date = date('Y-m-d');
    /*for ($i = 0; $i < 3; $i++) {
    echo 'P00'.$i;
    }*/
    $ruta = 'https://api.pse.pe/api/v1/666f7de1819447aea47d488d88c53862a4a5ae851e8f43f48c9558ee5a7b02fc';
   
    $util = Util::getInstance();
    $see = new See();
    $see->setCertificate(file_get_contents(dirname(__DIR__).'/empresa/cert/certificate.pem'));
    $see->setService('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
    $see->setClaveSOL('20600566289', 'CESARLUN', 'Cesarluna147');
    // Cliente
    if(strlen($docCliente)!=8){
    $client = (new Client())
        ->setTipoDoc('6')
        ->setNumDoc($docCliente)
        ->setRznSocial($cliente);        
    $recibo='01';
    $serie='F001';
    }else{
    $client = (new Client())
        ->setTipoDoc('1')
        ->setNumDoc($docCliente)
        ->setRznSocial($cliente);
    $recibo='03';
    $serie='B001';
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

    // Venta
    $invoice = (new Invoice())
        ->setUblVersion('2.1')
        ->setTipoOperacion('0101') // Venta - Catalog. 51
        ->setTipoDoc($recibo) // Factura - Catalog. 01 
        ->setSerie($serie)
        ->setCorrelativo('000'.$correlativo)
        ->setFechaEmision(new DateTime($fecha.'-05:00')) // Zona horaria: Lima
        ->setTipoMoneda('PEN') // Sol - Catalog. 02
        ->setCompany($company)
        ->setClient($client)
        ->setMtoOperGravadas($totalSinImpuestos)
        ->setMtoOperExoneradas(0)
        ->setMtoIGV($montoImpuestos)
        ->setTotalImpuestos($montoImpuestos)
        ->setValorVenta($totalSinImpuestos)
        ->setSubTotal($total)
        ->setMtoImpVenta($total)
        ;
    //Forma de Pago 
    if($formaPago=='contado'){
        $invoice->setFormaPago(new FormaPagoContado()); // FormaPago: Contado
    }else{
        $invoice->setFormaPago(new FormaPagoCredito($total));
        $ventaCuota = chargeCuotas($cuotas,$total); 
        $invoice->setCuotas($ventaCuota)->setTipoMoneda('PEN'); // Sol - Catalog. 02  
        }
    //Comprobar descuento
    if($descuento!=0){
        $invoice->setDescuentos([
            (new Charge())
                ->setCodTipo('02')
                ->setMontoBase($descuento)
                ->setFactor(1)
                ->setMonto($descuento)
        ]);
    }    
    $items = chargeProducts($nofactura,$conexion);
    
    $formatter = new NumeroALetras();
    $totalTexto = 'SON '.$formatter->toInvoice($total, 2, 'soles');
    
    $invoice->setDetails($items)
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue($totalTexto)
    ]);

    //Envio del XML
    $result = $see->send($invoice);

    
    $micarpeta = '../empresa/xml/'.$date;
    if (!file_exists($micarpeta)) {
        mkdir($micarpeta, 0777, true);
    }
    // Guardar XML firmado digitalmente.
     file_put_contents($micarpeta.'/'.$invoice->getName().'.xml',
        $see->getFactory()->getLastXml());
        $hash = $util->getHash($invoice);
    // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
        // Mostrar error al conectarse a SUNAT.
        echo 'Codigo Error: '.$result->getError()->getCode();
        echo 'Mensaje Error: '.$result->getError()->getMessage();
        exit();
    }else{
        //echo $result->getCdrResponse()->getDescription();
    }
    // Guardamos el CDR
     file_put_contents($micarpeta.'/R-'.$invoice->getName().'.zip', $result->getCdrZip());
     
     
     //Retornar HASH
     return $hash;

}
    function chargeCuotas($cuotas,$total){
    $cuota=$total/$cuotas;
    $contador = 0;
    $x=0;
    $dia=30;
    while ($contador != $cuotas) {
        $cuota1 = (new Cuota())
        ->setMonto($cuota)
        ->setFechaPago(new DateTime('+'.$dia.'days'))
        ;
        $totalcuotas[$x] = $cuota1;
        $x = $x+1;
        $dia = $dia+30;
        $contador +=1;
    }   
        return $totalcuotas;
                                     
    }

?>