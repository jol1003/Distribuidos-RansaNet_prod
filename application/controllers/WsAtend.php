<?php
//ini_set ("display_errors","1" );
//error_reporting(E_ALL);
require_once '../Libs/Conext/Conexion.cls.php';
require_once '../Libs/Conext/Consulta.cls.php';
require_once '../Libs/Conext/BigConsulting.php';
require_once '../Libs/lib/nusoap.php';
$Contenido = new contenido();

//require_once '../Libs/Conext/BigConsulting.php';
//$Contenido = new contenido();

//$miURL = 'http://10.4.1.26/sip_pilar/server/WsAtend';
$miURL = 'http://antecedentes.inpe.gob.pe/sip_pilar/server/WsAtend';

$server = new soap_server();
$server->configureWSDL('ws_ATJO', $miURL);
//$server->configureWSDL('SIPANTECEDENTESBNwsdl2', 'urn:SIPANTECEDENTESBNwsdl2');
$server->wsdl->schemaTargetNamespace=$miURL;


$server->register('SIPANTECEDENTESContAten', // Nombre de la funcion
				   array('parametro' => 'xsd:string'), // Parametros de entrada
				   array('return' => 'xsd:string'), // Parametros de salida
				   $miURL);

function SIPANTECEDENTESContAten($parametro) {
    global $Contenido;
    $retorna = $Contenido->ControlarAtend($parametro);
    //$retorna = 34;
    return new soapval('return', 'xsd:string', $retorna);
    //return $retorna;
}

/*
$server->register('SIPANTECEDENTESAtendidosR', // method name
        array('atendidos' => 'tns:Array'), // input parameters
        array('return' => 'xsd:Array') // output parameters
        
);

function SIPANTECEDENTESAtendidosR($atendidos) {
    global $Contenido;
    $retorna = $Contenido->RegistraAtendidosRESAGADOS($atendidos);
    return $retorna;
}
*/
//$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>