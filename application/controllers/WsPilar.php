<?php

require_once '../Libs/Conext/Conexion.cls.php';
require_once '../Libs/Conext/Consulta.cls.php';
require_once '../Libs/Conext/BigConsulting.php';
require_once '../Libs/lib/nusoap.php';

$Contenido = new contenido();
/* * ****************************************************************************************************************************************************** */
//Creamos la instancia al servidor.
$server = new nusoap_server();
/* * ****************************************************************************************************************************************************** */
//-- NOMBRE DE LA WEB SERVICE
$server->configureWSDL('SIPANTECEDENTESwsdl2', 'urn:SIPANTECEDENTESwsdl2');
/* * ****************************************************************************************************************************************************** */
//-- Registra la estructura de datos Personas
$server->wsdl->addComplexType(
        'Personas',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'p_idsol' => array('name' => 'p_idsol', 'type' => 'xsd:int'),
            'p_apepat' => array('name' => 'p_apepat', 'type' => 'xsd:string'),
            'p_apemat' => array('name' => 'p_apemat', 'type' => 'xsd:string'),
            'p_nombres' => array('name' => 'p_nombres', 'type' => 'xsd:string'),
            'p_tipdocu' => array('name' => 'p_tipdocu', 'type' => 'xsd:string'),
            'p_numdocu' => array('name' => 'p_numdocu', 'type' => 'xsd:string'),
            'p_tipo' => array('name' => 'p_fechasol', 'type' => 'xsd:int'), //para que quiere la solicitud
            'p_desc' => array('name' => 'p_desc', 'type' => 'xsd:string'),
            'p_fechasol' => array('name' => 'p_fechasol', 'type' => 'xsd:string'),
            'tipo_pago' => array('name' => 'tipo_pago', 'type' => 'xsd:string'), //o recibo o voucher
            'tipo_img' => array('name' => 'tipo_img', 'type' => 'xsd:string'), //numero de recibo y/o voucher
            'fec_pago' => array('name' => 'fec_pago', 'type' => 'xsd:string'),
            'des_ip_maquina' => array('name' => 'des_ip_maquina', 'type' => 'xsd:string'),
            'cod_user' => array('name' => 'cod_user', 'type' => 'xsd:int'),
            'id_local' => array('name' => 'id_local', 'type' => 'xsd:int')
            //'nom_ofi' => array('name' => 'nom_ofi', 'type' => 'xsd:string') //nuevo
        )
);

//-- Registra el array a devolver (array de Personas).
$server->wsdl->addComplexType(
        'PersonasList',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
            array('ref' => 'SOAP:ENC:arrayType',
                'wsdl:arrayType' => 'tns:Personas[]')
        ),
        'tns:courseDetails'
);

/******************************************************************************************************************************************************** */
//-- Registra el método SIPANTECEDENTES
$server->register('SIPANTECEDENTES', // method name
        array('person' => 'tns:PersonasList'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTES', // soapaction
        'rpc', // style
        'encoded', // use
        'REGISTRA LAS SOLICITUDES EN EL SERVIDOR'    // documentation
);

function SIPANTECEDENTES($person) {
    global $Contenido;
    $retorna = $Contenido->RegistraAntecedenteClWs($person); 
    return $retorna;
}

/* * ****************************************************************************************************************************************************** */
//-- Registra el método SIPANTECEDENTESBuscador
$server->register('SIPANTECEDENTESBuscador', // method name
        array('Id_local' => 'xsd:int'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTESBuscador', // soapaction
        'rpc', // style
        'encoded', // use
        'BUSCA RESULTADOS EN EL SERVIDOR'    // documentation
);

function SIPANTECEDENTESBuscador($id_local) {
    global $Contenido;
    $retorna = $Contenido->BuscaResultadosClWs($id_local);
    return $retorna;
}
/* * ****************************************************************************************************************************************************** */
//-- Registra el método SIPANTECEDENTESObservados
$server->register('SIPANTECEDENTESObservados', // method name
        array('observados' => 'tns:PersonasList'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTESObservados', // soapaction
        'rpc', // style
        'encoded', // use
        'REGISTRA OBSERVADOS EN EL SERVIDOR'    // documentation
);

function SIPANTECEDENTESObservados($observados) {
    global $Contenido;
    $retorna = $Contenido->RegistraObservados($observados);
    return $retorna;
}
/* * ****************************************************************************************************************************************************** */

//-- Registra el método SIPANTECEDENTESAtendidos
$server->register('SIPANTECEDENTESAtendidos', // method name
        array('atendidos' => 'tns:PersonasList'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTESAtendidos', // soapaction
        'rpc', // style
        'encoded', // use
        'REGISTRA ATENDIDOS EN EL SERVIDOR'    // documentation
);

function SIPANTECEDENTESAtendidos($atendidos) {
    global $Contenido;
    $retorna = $Contenido->RegistraAtendidos($atendidos);
    return $retorna;
}

//-- Registra el método SIPANTECEDENTESAtendidos
$server->register('SIPANTECEDENTESAtendidosInfra', // method name
        array('atendidos' => 'tns:PersonasList'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTESAtendidosInfra', // soapaction
        'rpc', // style
        'encoded', // use
        'REGISTRA ATENDIDOS EN EL SERVIDOR INFRA'    // documentation
);

function SIPANTECEDENTESAtendidosInfra($atendidos) {
    global $Contenido;
    $retorna = $Contenido->RegistraAtendidosOK($atendidos);
    return $retorna;
}

$server->register('SIPANTECEDENTESAtendidosResa', // method name
        array('atendidos' => 'tns:PersonasList'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTESAtendidosResa', // soapaction
        'rpc', // style
        'encoded', // use
        'REGISTRA ATENDIDOS EN EL SERVIDOR RESAGADOS'    // documentation
);

function SIPANTECEDENTESAtendidosResa($atendidos) {
    global $Contenido;
    $retorna = $Contenido->RegistraAtendidosRESAGADOS($atendidos);
    return $retorna;
}

/* * ****************************************************************************************************************************************************** */
//-- Registra el método SIPANTECEDENTESInvalidados
$server->register('SIPANTECEDENTESInvalidados', // method name
        array('invalidos' => 'tns:PersonasList'), // input parameters
        array('return' => 'xsd:Array'), // output parameters
        'urn:SIPANTECEDENTESwsdl2', // namespace
        'urn:SIPANTECEDENTESwsdl2#SIPANTECEDENTESInvalidados', // soapaction
        'rpc', // style
        'encoded', // use
        'REGISTRA INVALIDADOS EN EL SERVIDOR'    // documentation
);

function SIPANTECEDENTESInvalidados($invalidos) {
    global $Contenido;
    $retorna = $Contenido->RegistraInvalidos($invalidos);
    return $retorna;
}



/* * ****************************************************************************************************************************************************** */
//-- PROCESA LA SOLICITUD Y DEVUELVE LA RESPUESTA
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>