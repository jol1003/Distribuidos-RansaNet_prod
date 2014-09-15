<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WsClientCliente extends CI_Controller {  

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
	         
    }
	
	public function index(){
	
        $params = array(
                'a' => 2,
          		'b' => 3,
            );
 
    	//$result = $this->nusoap_client->call('addnumbers', $params);
		
		 //$n_params = array('name' => 'My Name', 'email' => 'my@email.adr');
        /* $client = new nusoap_client('http://localhost/midepa_ci/wsservercliente?wsdl');
         $result = $client->call('addnumbers', $params);
         echo $result;*/
		 
		 $this->nusoap_client = new nusoap_client("http://localhost/midepa_ci/index.php/wsservercliente?wsdl", 'wsdl');
		 
		 
		 if($this->nusoap_client->fault){
        
		     $text = 'Error: '.$this->nusoap_client->fault;
        }else{
		
            if ($this->nusoap_client->getError()){
                 $text = 'Error: '.$this->nusoap_client->getError();
            }else{
                 $row = $this->nusoap_client->call('addnumbers', $params);
		 		 print_r($row);
            }
     	}
		 
    }
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */