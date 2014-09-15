<?php
class SoapServerWs extends CI_Controller{
	function __construct(){
		parent::__construct();
			
		$ns = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/soapserverws';
		$this->load->library("Nusoap_library"); // load nusoap toolkit library in controller
        $this->nusoap_server = new soap_server(); // create soap server object
		$this->nusoap_server->configureWSDL("SOAP Server - NuSOAP CI", $ns); // wsdl cinfiguration
		$this->nusoap_server->wsdl->schemaTargetNamespace = $ns; // server namespace
		
		$input_array1 = array('usuario' => "xsd:string", 'clave' => "xsd:string");
        $return_array1 = array ("return" => "xsd:boolean");
        $this->nusoap_server->register(
		'logeo', $input_array1, 
		$return_array1, 
		"urn:SOAPServerWSDL", 
		"urn:".$ns."/logeo", 
		"document", 
		"literal", 
		"Logeo de usuario");
		
		//
        //$this->nusoap_server->wsdl->schemaTargetNamespace = $ns; // server namespace
		$input_array2 = array ('a' => "xsd:string", 'b' => "xsd:string");
        $return_array2 = array ("return" => "xsd:string");
        $this->nusoap_server->register(
		'addnumbers', $input_array2, 
		$return_array2, 
		"urn:SOAPServerWSDL", 
		"urn:".$ns."/addnumbers", 
		"document", 
		"literal", 
		"Suma de 2 nros");
		
		
		
		
		//$server->configureWSDL(‘WSCourse’, ‘urn:WSCourse’);

// definimos el tipo complejo (arreglo asociativo, =’struct’) de detalles de curso

		/*$this->nusoap_server->wsdl->addComplexType(
		'areaDetalle',
		'complexType',
		'struct',
		'all',
		'',
		array(
			'name'=>'id'  , 'type'=>'xsd:string',
			'name'=>'nombre'  , 'type'=>'xsd:string',
		)
		);*/
		
		//$this->nusoap_server->wsdl->schemaTargetNamespace = $ns; // server namespace
		$this->nusoap_server->wsdl->addComplexType(  'areaLista',
                                'complexType',
                                'struct',
                                'all',
                                '',
                                array('IdArea' => array('name' => 'IdArea','type' => 'xsd:int'),
									  'NomArea' => array('name' => 'NomArea','type' => 'xsd:string')
									 )
		);
		
		$this->nusoap_server->wsdl->addComplexType(
			'area_salida',
			'complexType',
			'array',             //tipo array porque es un arreglo de objetos
			'',
			'SOAP-ENC:Array',    //estructura equivalente en SOAP
			array(),
			array(
				array(
				   'ref' => 'SOAP-ENC:arrayType',
				   'wsdl:arrayType' => 'tns:areaLista[]' //contiene un array de Student
					 )
				),
			'tns:areaLista' // referencia al complextype name
		);
		
		/*$this->nusoap_server->wsdl->addComplexType(
			'Response_area',
			'complexType',
			'struct',
			'all',
			'',
			array
				('responseIdArea'      => array('type' => 'xsd:int'),
				 'responseNomArea'   => array('type' => 'xsd:string'),
				 'data' => array('type' => 'tns: area_salidad_array')
					)
        );*/

		$input_array3 = array();
        //$return_array3 = array ("return" => 'Array');
		$return_array3 = array('return' => 'tns:area_salida');
		//array('return' => 'xsd:Array')
        $this->nusoap_server->register(
		'area', $input_array3, 
		$return_array3, 
		"urn:SOAPServerWSDL", 
		"urn:".$ns."/area", 
		"document", 
		"literal", 
		"Listado de Area");
		
		//$this->nusoap_server->wsdl->schemaTargetNamespace = $ns; // server namespace
		$input_array4 = array();
        $return_array4 = array ("return" => 'Array');
        $this->nusoap_server->register(
		'tipoua', $input_array4, 
		$return_array4, 
		"urn:SOAPServerWSDL", 
		"urn:".$ns."/tipoua", 
		"document", 
		"literal", 
		"Listado de T. Unidad de almacenamiento");
	}
	
	function index(){
	
    	function logeo($usuario, $clave){
			//$resultado = false;
			$CI = &get_instance();
			$CI->load->model('servicio_model');
			
			$aj = $CI->servicio_model->get_logeo($usuario, $clave);
			if($aj==1){
				$resultado = true;
			}else{
				$resultado = false;
			}
			
			return $resultado;
		}
		
		function addnumbers($a,$b){
        	$c = $a + $b;
        	return $c;
    	}
		
		// Define the method as a PHP function
		
		
		function area(){
			//$resultado = false;
			$CI = &get_instance();
			$CI->load->model('servicio_model');
			$area = $CI->servicio_model->get_area();
			
			/*if (!empty($area)){
				$result->data = array();
				foreach ($students as $area	)
					$result->maintenances_data[] = $value;
			}*/
    		
			//return $result;
			//print_r($area);
			foreach($area as $row){
			
				$area_salida[] = array( "IdArea" => $row['IdArea'],
										"NomArea" => $row['NomArea']
									 );
				/*$areaLis[0] = array( "IdArea" => 1,
										"NomArea" => "otro"
									 );
				$areaLis[1] = array( "IdArea" => 2,
										"NomArea" => "otro2"
									 );
				$producto[0] = array('idservicio' =>'1', 
									'id_orden' =>'2');*/
				/*$area_salida[0] = array( "IdArea" => 1,
										"NomArea" => "otro"
									 );
				
				$area_salida[1] = array( "IdArea" => 2,
										"NomArea" => "otro dia"
									 );	*/				
				/*return array(
				'IdArea' => 1,
				'NomArea' => "jol"
				);*/
			}
	
			return $area_salida;
		}
		
		function tipoua(){
			//$resultado = false;
			$CI = &get_instance();
			$CI->load->model('servicio_model');
			$tipo = $CI->servicio_model->get_tipoua();
			
			return $tipo;
		}
		
    	$this->nusoap_server->service(file_get_contents("php://input")); // read raw data from request body
	}

}

	
?>