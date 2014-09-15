<?php
require_once(APPPATH . '/controllers/test/Toast.php');  

class Usuario_tests extends Toast
{
	/*function Persona_tests() 
	{
		parent::Toast(__FILE__);
		// Load any models, libraries etc. you need here 
	}*/
	
	function __construct() // change constructor method name
	{
   	parent::__construct(__FILE__); //changed parent constructor method name
	$this->load->model('home_model');
    // Load any models, libraries etc. you need here
	} 

	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {}


	/* TESTS BELOW */

	function test_verificar_usuario_logeado(){
		//$usr = "demo@demo.com";
		$usr = "j_olopez@hotmail.com";
		$pwd = "123456";
		$valida = $this->home_model->model_verificar_login($usr, $pwd);
		
		$this->_assert_equals($valida, 1);
	}
	
	function test_listar_usuario_por_id(){
		$id = 4;
		$valida = $this->home_model->listarUsuarios($id);
		$this->_assert_not_empty($valida);
	}
	
	function test_listar_persona(){
		$id = 4;
		$valida = $this->home_model->get_persona($id);
		$this->_assert_not_empty($valida);
	}
	
}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */