<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WsCliente extends CI_Controller {  


	function __construct() 
    {
        parent:: __construct ();
		$this->load->library('Nusoap_library');
	         
    }
	
	
	
	function test(){
			$this->nusoap_client = new nusoap_client("http://joventube.com/ServicioWebWs/index.php/soapserverws?wsdl", true);
			
			/*$err = $this->nusoap_client->getError();
      		if ($err) {
         		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
      		} // endif*/
	  		
			//$this->nusoap_client->debug();
			
			
			
			if($this->nusoap_client->fault){
            	$text = 'Error: '.$this->nusoap_client->fault;
        	}else{
				if ($this->nusoap_client->getError())
				{
					$text = 'Error: '.$this->nusoap_client->getError();
				}
				else
				{
					//$params = array("usuario" => 'JONCEBAYL',"clave" => '123');
					$result = $this->nusoap_client->call('area', array());  
					
					//echo htmlspecialchars($this->nusoap_client->request, ENT_QUOTES). '</b></p>';
					//echo htmlspecialchars($this->nusoap_client->response, ENT_QUOTES) . '</b></p>';
					//echo htmlspecialchars($this->nusoap_client->debug_str, ENT_QUOTES) . '</b></p>';
			
					print_r($result);
					
					/*$result = $this->nusoap_client->call('addnumbers', array("a" => 2, "b" => 2));  
					print_r($result);  */
				} 
			}      
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */