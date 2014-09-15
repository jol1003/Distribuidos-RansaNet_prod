<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WsServerCliente extends CI_Controller {  

	/**ini
	 * Index Page for this controller. 
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index 
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/ 
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() 
    {
        parent:: __construct ();
		$this->load->library('Nusoap_lib');
		
		$this->nusoap_server = new soap_server();
        $this->nusoap_server->configureWSDL("SOAP Server", 'urn:server');
        $this->nusoap_server->wsdl->schemaTargetNamespace = 'urn:server';
 		$ns = 'server'; 
        //registrando funciones
        $input_array = array ('a' => "xsd:string", 'b' => "xsd:string");
        $return_array = array ("return" => "xsd:string");
        $this->nusoap_server->register(
		'addnumbers', $input_array, 
		$return_array, 
		"urn:SOAPServerWSDL", 
		"urn:".$ns."/addnumbers", 
		"rpc", 
		"encoded", 
		"Suma de 2 nros");
		         
    }
	
	public function index(){
	
        function addnumbers($a,$b){
		
            $c = $a + $b;
            return $c;
        }
 
        $this->nusoap_server->service(file_get_contents("php://input"));
    }
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */