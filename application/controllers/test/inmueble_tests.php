<?php
require_once(APPPATH . '/controllers/test/Toast.php');   

class Inmueble_tests extends Toast
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

	function test_get_inmuebles_detalle(){
		$id = "1";
		$valida = $this->home_model->get_inmuebles_detalle($id);
		$this->_assert_not_empty($valida, 1);
	}
	
	function test_get_inmueble_area_comun(){
		$id = 1;
		$valida = $this->home_model->get_inmueble_area_comun($id);
		$this->_assert_not_empty($valida);
	}
	
	/*function test_insert_inmueble(){
		$id = 4;
		$idd= '';
		//retorna el id del autoincrement
		$valida = $this->home_model->insertInmueble($idd, $id);
		$this->_assert_not_empty($valida);
	}*/
	
	function test_busqueda_inmueble(){
		$comp_alq = 2;
		//$idd= '';
		//retorna el id del autoincrement
		$valida = $this->home_model->busqueda_inm($comp_alq, $casa='', $dormitorios='', $desde='', $hasta='', $dist=0);
		$this->_assert_not_empty($valida);
		
	}
	
}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */